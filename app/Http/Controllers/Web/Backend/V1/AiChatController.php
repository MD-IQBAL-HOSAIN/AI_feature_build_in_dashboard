<?php

namespace App\Http\Controllers\Web\Backend\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\AiConversation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Client\RequestException;
use Laravel\Ai\Ai;
use Laravel\Ai\Exceptions\FailoverableException;
use Laravel\Ai\Messages\Message as AiMessage;

/**
 * Handles the backend AI chat widget.
 *
 * Route definitions live in routes/v1/backend.php under the admin/v1 prefix.
 * The browser-side widget calls these endpoints from
 * resources/views/backend/partials/ai-chat-script.blade.php.
 */
class AiChatController extends Controller
{
    /**
     * Persist the current message history.
     *
     * Used by chat() after fake, successful, and failed AI responses. Authenticated
     * users store conversations in ai_conversations; guests fall back to session.
     */
    private function persistConversation(?AiConversation $convModel, array $conversation): void
    {
        if ($convModel) {
            $convModel->messages = $conversation;
            $convModel->last_activity = now();
            $convModel->save();

            return;
        }

        session()->put('ai_conversation', $conversation);
    }

    /**
     * Resolve the Laravel AI provider's default text model.
     *
     * Used as the fallback when no model is selected in the chat widget.
     */
    private function defaultChatModel(): string
    {
        return Ai::textProvider()->defaultTextModel();
    }

    /**
     * Allow only text/chat-capable model ids in the selector and chat request.
     *
     * Used by openAiChatModels() to filter the OpenAI /models response and by
     * requestedModel() to validate the selected model submitted from the widget.
     */
    private function isChatModelId(string $modelId): bool
    {
        if (Str::contains($modelId, [
            'audio',
            'dall-e',
            'embedding',
            'image',
            'moderation',
            'realtime',
            'search',
            'speech',
            'tts',
            'transcribe',
            'whisper',
        ])) {
            return false;
        }

        return Str::startsWith($modelId, [
            'chatgpt-',
            'gpt-4.1',
            'gpt-4o',
            'gpt-5',
            'o1',
            'o3',
            'o4',
        ]);
    }

    /**
     * Build a sortable priority key so newer chat families appear first.
     *
     * Used by openAiChatModels() before returning the model dropdown options.
     */
    private function chatModelSortKey(string $modelId): string
    {
        $score = match (true) {
            Str::startsWith($modelId, 'gpt-5.5') => 900,
            Str::startsWith($modelId, 'gpt-5.4') => 880,
            Str::startsWith($modelId, 'gpt-5.3') => 860,
            Str::startsWith($modelId, 'gpt-5.2') => 840,
            Str::startsWith($modelId, 'gpt-5.1') => 820,
            Str::startsWith($modelId, 'gpt-5') => 800,
            Str::startsWith($modelId, 'gpt-4.1') => 700,
            Str::startsWith($modelId, 'gpt-4o') => 680,
            Str::startsWith($modelId, 'o4') => 620,
            Str::startsWith($modelId, 'o3') => 600,
            Str::startsWith($modelId, 'o1') => 580,
            default => 100,
        };

        return sprintf('%03d-%s', $score, $modelId);
    }

    /**
     * Load available OpenAI chat models for the configured API key.
     *
     * Used by models(), which is called from the footer model selector in the
     * chat widget. It never exposes the API key to the browser.
     */
    private function openAiChatModels(): array
    {
        $providerConfig = config('ai.providers.openai', []);
        $apiKey = $providerConfig['key'] ?? null;
        $baseUrl = rtrim($providerConfig['url'] ?? 'https://api.openai.com/v1', '/');
        $defaultModel = $this->defaultChatModel();

        if (blank($apiKey)) {
            return [
                'models' => [$defaultModel],
                'default' => $defaultModel,
                'warning' => 'OPENAI_API_KEY is not configured.',
            ];
        }

        $response = Http::withToken($apiKey)
            ->acceptJson()
            ->timeout(15)
            ->get($baseUrl . '/models');

        if (!$response->successful()) {
            return [
                'models' => [$defaultModel],
                'default' => $defaultModel,
                'warning' => 'Could not load OpenAI models for this key.',
            ];
        }

        $models = collect($response->json('data', []))
            ->pluck('id')
            ->filter(fn ($id) => is_string($id) && $this->isChatModelId($id))
            ->unique()
            ->sortByDesc(fn ($id) => $this->chatModelSortKey($id))
            ->values()
            ->all();

        if (!in_array($defaultModel, $models, true)) {
            array_unshift($models, $defaultModel);
            $models = array_values(array_unique($models));
        }

        return [
            'models' => $models,
            'default' => $defaultModel,
            'warning' => null,
        ];
    }

    /**
     * Resolve and validate the model requested by the widget.
     *
     * Used by chat() so every message can be sent with the user's selected model.
     */
    private function requestedModel(Request $request): string
    {
        $model = trim((string) $request->input('model', ''));

        if ($model === '') {
            return $this->defaultChatModel();
        }

        abort_unless(
            preg_match('/^[A-Za-z0-9._:-]+$/', $model) === 1 && $this->isChatModelId($model),
            422,
            'Invalid chat model selected.'
        );

        return $model;
    }

    /**
     * Detect whether a failed AI request should retry with the default model.
     */
    private function shouldRetryWithDefault(\Throwable $e): bool
    {
        if ($e instanceof FailoverableException) {
            return true;
        }

        $message = strtolower($e->getMessage() ?? '');
        $errorType = '';
        $errorCode = '';

        if ($e instanceof RequestException && $e->response) {
            $message = strtolower((string) ($e->response->json('error.message') ?? $message));
            $errorType = strtolower((string) ($e->response->json('error.type') ?? ''));
            $errorCode = strtolower((string) ($e->response->json('error.code') ?? ''));
        }

        $patterns = [
            'model_not_found',
            'model not found',
            'does not exist',
            'invalid model',
            'unsupported',
            'not supported',
            'context_length_exceeded',
            'maximum context length',
            'too many tokens',
            'token limit',
            'rate limit',
            'insufficient_quota',
            'quota',
        ];

        foreach ($patterns as $pattern) {
            if (str_contains($message, $pattern) || str_contains($errorType, $pattern) || str_contains($errorCode, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Summarize an AI error into a short, user-facing reason.
     */
    private function aiErrorReason(\Throwable $e): ?string
    {
        $message = $e->getMessage() ?? '';

        if ($e instanceof RequestException && $e->response) {
            $message = (string) ($e->response->json('error.message') ?? $message);
        }

        $message = trim($message);
        if ($message === '') {
            return null;
        }

        if (mb_strlen($message) > 180) {
            $message = mb_substr($message, 0, 177) . '...';
        }

        return $message;
    }

    /**
     * Build a short notice when auto-switching models.
     */
    private function fallbackNotice(string $selectedModel, string $fallbackModel, ?string $reason): string
    {
        $reasonText = $reason ? ' Reason: ' . $reason : '';

        return "Note: Selected model {$selectedModel} is unavailable or limited. Switched to {$fallbackModel}.{$reasonText}";
    }

    /**
     * Check whether the stored title is still a default placeholder.
     */
    private function shouldUpdateConversationTitle(?string $title): bool
    {
        $title = trim((string) $title);

        if ($title === '') {
            return true;
        }

        return Str::startsWith($title, 'Conversation ') || $title === 'New conversation';
    }

    /**
     * Return available chat models for the dropdown.
     *
     * Route: GET admin/v1/ai/models, name: ai.models.
     * Called by loadModels() in ai-chat-script.blade.php.
     */
    public function models()
    {
        try {
            return response()->json($this->openAiChatModels());
        } catch (\Throwable $e) {
            Log::error('OpenAI models load error: ' . $e->getMessage());

            return response()->json([
                'models' => [$this->defaultChatModel()],
                'default' => $this->defaultChatModel(),
                'warning' => 'Could not load OpenAI models for this key.',
            ], 500);
        }
    }

    /**
     * Send a user message to the selected AI model and persist the reply.
     *
     * Route: POST admin/v1/ai/chat, name: ai.chat.
     * Called when the widget Send button or Enter key submits a message.
     */
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
            'model' => 'nullable|string|max:100',
        ]);

        $message = $request->input('message');
        $conversationId = $request->input('conversation_id');
        $selectedModel = $this->requestedModel($request);

        // Prefer DB-backed conversation if authenticated and id provided
        $convModel = null;
        if ($conversationId && Auth::check()) {
            $convModel = AiConversation::where('id', $conversationId)->where('user_id', Auth::id())->first();
        }

        if ($convModel) {
            $conversation = $convModel->messages ?? [];
        } elseif (Auth::check()) {
            $conversation = session()->get('ai_conversation', []);
            $convModel = AiConversation::create([
                'user_id' => Auth::id(),
                'title' => Str::limit($message, 60),
                'messages' => $conversation,
                'last_activity' => now(),
            ]);
            session()->forget('ai_conversation');
        } else {
            // Conversation stored in session under 'ai_conversation'
            $conversation = session()->get('ai_conversation', []);
        }

        $isFirstMessage = count($conversation) === 0;

        // push user message
        $conversation[] = ['role' => 'user', 'content' => $message];

        if ($convModel && $isFirstMessage && $this->shouldUpdateConversationTitle($convModel->title ?? '')) {
            $convModel->title = Str::limit($message, 60);
            $convModel->save();
        }

        // If dev fake toggle is enabled, return a canned reply and persist it
        if (env('AI_FAKE')) {
            $reply = "(AI fake) You said: " . $message . "\n\nTry: For real message you should buy ai and update your environment variables.";
            $conversation[] = ['role' => 'assistant', 'content' => $reply];

            $this->persistConversation($convModel, $conversation);

            return response()->json([
                'reply' => $reply,
                'conversation_id' => $convModel?->id,
                'model' => $selectedModel,
            ]);
        }

        try {
            $provider = Ai::textProvider();

            // Convert stored history into Laravel AI message objects.
            $messages = array_map(function ($m) {
                return new AiMessage($m['role'], $m['content']);
            }, $conversation);

            // Include the selected model in instructions so the assistant can
            // answer "which model/version am I using?" accurately.
            $instructions = "You are running inside this application's AI chat widget. The current OpenAI API model selected for this response is {$selectedModel}. If the user asks what model, version, or AI version they are using, answer with this exact model id.";

            $usedModel = $selectedModel;
            try {
                $response = $provider->textGateway()->generateText(
                    $provider,
                    $selectedModel,
                    $instructions,
                    $messages
                );

                $reply = (string) $response;
            } catch (\Throwable $e) {
                $fallbackModel = $this->defaultChatModel();
                $canFallback = $selectedModel !== $fallbackModel && $this->shouldRetryWithDefault($e);

                if ($canFallback) {
                    $reason = $this->aiErrorReason($e);
                    $fallbackInstructions = "You are running inside this application's AI chat widget. The current OpenAI API model selected for this response is {$fallbackModel}. If the user asks what model, version, or AI version they are using, answer with this exact model id.";

                    $fallbackResponse = $provider->textGateway()->generateText(
                        $provider,
                        $fallbackModel,
                        $fallbackInstructions,
                        $messages
                    );

                    $reply = $this->fallbackNotice($selectedModel, $fallbackModel, $reason)
                        . "\n\n" . (string) $fallbackResponse;
                    $usedModel = $fallbackModel;
                } else {
                    throw $e;
                }
            }

            // persist assistant reply in conversation
            $conversation[] = ['role' => 'assistant', 'content' => $reply];
            $this->persistConversation($convModel, $conversation);

            return response()->json([
                'reply' => $reply,
                'conversation_id' => $convModel?->id,
                'model' => $usedModel,
            ]);
        } catch (\Throwable $e) {
            Log::error('AI chat error: ' . $e->getMessage());

            // persist error message as assistant response
            $errReply = 'AI service unavailable.';
            $conversation[] = ['role' => 'assistant', 'content' => $errReply];
            $this->persistConversation($convModel, $conversation);

            return response()->json([
                'reply' => $errReply . ' ' . ($e->getMessage() ?? ''),
                'conversation_id' => $convModel?->id,
                'model' => $selectedModel,
            ], 500);
        }
    }

    /**
     * Return the active conversation history.
     *
     * Route: GET admin/v1/ai/history, name: ai.history.
     * Called when the widget opens or switches to a saved conversation.
     */
    public function history(Request $request)
    {
        $conversationId = $request->query('conversation_id');

        // If authenticated and conversation id provided, load from DB
        if ($conversationId && Auth::check()) {
            $conv = AiConversation::where('id', $conversationId)->where('user_id', Auth::id())->first();
            if ($conv) {
                return response()->json(['messages' => $conv->messages ?? [], 'conversation' => ['id' => $conv->id, 'title' => $conv->title]]);
            }
        }

        // fallback to session
        $conversation = session()->get('ai_conversation', []);

        return response()->json(['messages' => $conversation]);
    }

    /**
     * List conversations for current user.
     *
     * Route: GET admin/v1/ai/conversations, name: ai.conversations.
     * Called by the History panel in the chat widget.
     */
    public function conversations(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['conversations' => []]);
        }

        $list = AiConversation::where('user_id', Auth::id())->orderBy('last_activity', 'desc')->get(['id', 'title', 'updated_at']);

        return response()->json(['conversations' => $list]);
    }

    /**
     * Create a new conversation for user.
     *
     * Route: POST admin/v1/ai/conversations, name: ai.conversations.create.
     * Called by the History panel's New button.
     */
    public function createConversation(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $title = $request->input('title') ?: 'New conversation';

        $conv = AiConversation::create([
            'user_id' => Auth::id(),
            'title' => $title,
            'messages' => [],
            'last_activity' => now(),
        ]);

        return response()->json(['conversation' => ['id' => $conv->id, 'title' => $conv->title]]);
    }

    /**
     * Delete a conversation.
     *
     * Route: DELETE admin/v1/ai/conversations/{id}, name: ai.conversations.delete.
     * Called by each conversation row's Delete button in the History panel.
     */
    public function deleteConversation(Request $request, int $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $conv = AiConversation::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$conv) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $conv->delete();

        return response()->json(['ok' => true]);
    }

    /**
     * Select a conversation: return its messages. If the conversation has no messages
     * but session has messages, merge session into it automatically and clear session.
     *
     * Route: POST admin/v1/ai/conversations/{id}/select, name: ai.conversations.select.
     * Called when a user clicks a saved conversation in the History panel.
     */
    public function selectConversation(Request $request, int $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $conv = AiConversation::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$conv) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $messages = $conv->messages ?? [];

        // If DB conversation is empty but session has messages, merge automatically
        $sessionMessages = session()->get('ai_conversation', []);
        if ((empty($messages) || count($messages) === 0) && !empty($sessionMessages)) {
            $messages = array_merge($messages, $sessionMessages);
            $conv->messages = $messages;
            $conv->last_activity = now();
            $conv->save();

            // clear session copy since we've imported
            session()->forget('ai_conversation');
        }

        return response()->json(['messages' => $messages, 'conversation' => ['id' => $conv->id, 'title' => $conv->title]]);
    }
}
