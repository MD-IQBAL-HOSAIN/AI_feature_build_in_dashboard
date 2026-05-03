<?php

use App\Mail\TestMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

// Simple test route to send email
    Route::get('/test-mail', function () {
        try {
            Mail::to('test@example.com')->send(new TestMail());
            
            return response()->json([
                'message' => 'Email sent successfully!',
                'status' => 'Check your Mailtrap inbox'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to send email',
                'message' => $e->getMessage()
            ], 500);
        }
    });
// Simple Queue test route to send email
    Route::get('/test-mail-queue', function () {
        try {
            Mail::to('test@example.com')->queue(new TestMail());
            
            return response()->json([
                'message' => 'Email sent successfully!',
                'status' => 'Check your Mailtrap inbox'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to send email',
                'message' => $e->getMessage()
            ], 500);
        }
    });

// Test immediate vs queued sending
    Route::get('/compare-email-methods', function () {
        
        
        $queueStart = microtime(true);
        // Queued sending
        Mail::to('queue@example.com')->queue(new TestMail());
        $queuedTime = microtime(true) - $queueStart;
        
        $startTime = microtime(true);
        
        // Immediate sending (if using sync driver)
        Mail::to('send@example.com')->send(new TestMail());
        $immediateTime = microtime(true) - $startTime;
        return response()->json([
            'immediate_send_time' => round($immediateTime, 4) . ' seconds',
            'queue_dispatch_time' => round($queuedTime, 4) . ' seconds',
            'performance_improvement' => round(($immediateTime - $queuedTime) / $immediateTime * 100, 2) . '% faster dispatch'
        ]);
    });


// Multiple queued emails example
    Route::get('/test-bulk-emails', function () {
        $users = [
            ['name' => 'Alice', 'email' => 'alice@example.com'],
            ['name' => 'Bob', 'email' => 'bob@example.com'],
            ['name' => 'Charlie', 'email' => 'charlie@example.com'],
        ];
        
        foreach ($users as $user) {
            Mail::to($user['email'])->queue(new TestMail(['name' => $user['name']]));
        }
        
        return response()->json([
            'message' => 'Bulk emails queued!',
            'users_count' => count($users),
            'queue_driver' => config('queue.default')
        ]);
    });
