<?php
namespace App\Http\Controllers\Web\Backend\V1\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class StripeSettingsController extends Controller
{
    public function index()
    {
        return view("backend.v1.settings.payments-settings");
    }

    public function update(Request $request)
    {
        $request->validate([
            'stripe_key'              => 'nullable|string',
            'stripe_secret'           => 'nullable|string',
            // field name corrected: use `stripe_webhook_secret` (not websocket)
            'stripe_webhook_secret'   => 'nullable|string',
            'payment_success_url'     => 'nullable|string',
            'payment_cancel_url'      => 'nullable|string',
        ]);
        // 'mail_username'     => 'nullable|string',

        try {
            $stripeKey           = str_replace(' ', '', $request->stripe_key);
            $stripeSecret        = str_replace(' ', '', $request->stripe_secret);
            $stripeWebhookSecret = str_replace(' ', '', $request->stripe_webhook_secret);
            $paymentSuccessUrl   = str_replace(' ', '', $request->payment_success_url);
            $paymentCancelUrl    = str_replace(' ', '', $request->payment_cancel_url);

            // Read .env content
            $envPath    = base_path('.env');
            $envContent = File::exists($envPath) ? File::get($envPath) : '';
            $lineBreak  = PHP_EOL;

            // Use anchored, multiline-safe patterns to replace only exact lines.
            $patterns = [
                '/^STRIPE_KEY=.*$/m',
                '/^STRIPE_SECRET=.*$/m',
                '/^STRIPE_WEBHOOK_SECRET=.*$/m',
                '/^PAYMENT_SUCCESS_URL=.*$/m',
                '/^PAYMENT_CANCEL_URL=.*$/m',
            ];

            $replacements = [
                'STRIPE_KEY=' . $stripeKey,
                'STRIPE_SECRET=' . $stripeSecret,
                'STRIPE_WEBHOOK_SECRET=' . $stripeWebhookSecret,
                'PAYMENT_SUCCESS_URL=' . $paymentSuccessUrl,
                'PAYMENT_CANCEL_URL=' . $paymentCancelUrl,
            ];

            // If a key is missing, append it rather than relying solely on preg_replace
            foreach (['STRIPE_KEY','STRIPE_SECRET','STRIPE_WEBHOOK_SECRET','PAYMENT_SUCCESS_URL','PAYMENT_CANCEL_URL'] as $i => $k) {
                if (!preg_match("/^{$k}=.*$/m", $envContent)) {
                    $envContent = rtrim($envContent, "\r\n") . $lineBreak . $replacements[$i] . $lineBreak;
                }
            }

            $envContent = preg_replace($patterns, $replacements, $envContent);

            // Atomic write: write to temp file then move
            $tmpPath = $envPath . '.tmp';
            File::put($tmpPath, $envContent);
            File::move($tmpPath, $envPath);

            // Update runtime environment so the current request immediately sees changes
            putenv('STRIPE_KEY=' . $stripeKey);
            putenv('STRIPE_SECRET=' . $stripeSecret);
            putenv('STRIPE_WEBHOOK_SECRET=' . $stripeWebhookSecret);
            putenv('PAYMENT_SUCCESS_URL=' . $paymentSuccessUrl);
            putenv('PAYMENT_CANCEL_URL=' . $paymentCancelUrl);

            $_ENV['STRIPE_KEY'] = $stripeKey;
            $_ENV['STRIPE_SECRET'] = $stripeSecret;
            $_ENV['STRIPE_WEBHOOK_SECRET'] = $stripeWebhookSecret;
            $_ENV['PAYMENT_SUCCESS_URL'] = $paymentSuccessUrl;
            $_ENV['PAYMENT_CANCEL_URL'] = $paymentCancelUrl;

            // Also set config values used by the app (does not persist across requests)
            config()->set('services.stripe.key', $stripeKey);
            config()->set('services.stripe.secret', $stripeSecret);
            config()->set('services.stripe.webhook_secret', $stripeWebhookSecret);

            session()->flash('t-success', 'Stripe settings updated successfully.');
            return response()->json([
                'success' => true,
                'message' => 'Stripe settings updated successfully.',
            ], 200);

        } catch (\Exception $e) {
            $message = 'Failed to update Stripe settings. ' . $e->getMessage();
            session()->flash('t-error', $message);

            return response()->json([
                'success' => false,
                'message' => $message,
            ], 422);
        }
    }
}








