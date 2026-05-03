<?php

use App\Http\Controllers\Web\Backend\V1\DashboardController;
use App\Http\Controllers\Web\Backend\V1\AiChatController;
use App\Http\Controllers\Web\Backend\V1\DynamicPageController;
use App\Http\Controllers\Web\Backend\V1\FaqController;
use App\Http\Controllers\Web\Backend\V1\LanguageController;
use App\Http\Controllers\Web\Backend\V1\SystemUserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
|  With prefix route for backend (admin) panel v1
|--------------------------------------------------------------------------
|
*/

//! Dashboard route
Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

//! AI Chat endpoint (AJAX)
Route::post('/ai/chat', [AiChatController::class, 'chat'])->name('ai.chat');
Route::get('/ai/models', [AiChatController::class, 'models'])->name('ai.models');
Route::get('/ai/history', [AiChatController::class, 'history'])->name('ai.history');
Route::get('/ai/conversations', [AiChatController::class, 'conversations'])->name('ai.conversations');
Route::post('/ai/conversations', [AiChatController::class, 'createConversation'])->name('ai.conversations.create');
Route::delete('/ai/conversations/{id}', [AiChatController::class, 'deleteConversation'])->name('ai.conversations.delete');
Route::post('/ai/conversations/{id}/select', [AiChatController::class, 'selectConversation'])->name('ai.conversations.select');

//! Language routes (resource CRUD + custom status action)
Route::patch('/language/{id}/status', [LanguageController::class, 'status'])->name('language.status');
Route::resource('language', LanguageController::class)->parameters([
    'language' => 'id',
]);

//! FAQ routes (resource CRUD + custom status action)
Route::patch('/faq/{id}/status', [FaqController::class, 'status'])->name('faq.status');
Route::resource('faq', FaqController::class)->parameters([
    'faq' => 'id',
]);

//! User routes (resource CRUD + custom status action)
Route::post('system-user/status/{id}', [SystemUserController::class, 'status'])
    ->name('system-user.status');

Route::resource('system-user', SystemUserController::class)
    ->except(['show']);

//! Dynamic Page routes (resource CRUD + custom status action)
Route::patch('/dynamic/{id}/status', [DynamicPageController::class, 'status'])->name('dynamic.status');
Route::resource('dynamic', DynamicPageController::class)->parameters([
    'dynamic' => 'id',
]);

/*
|--------------------------------------------------------------------------
|  With additional backend routes (Settings, Queue, etc.)
|--------------------------------------------------------------------------
|
*/

require_once __DIR__ . '/../queue.php';
require_once __DIR__ . '/settings.php';
