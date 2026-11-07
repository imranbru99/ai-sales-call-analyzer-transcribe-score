<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', function () {
        return response()->json([
            'status' => 'ok',
            'product' => 'AI Sales Call Analyzer',
            'version' => '1.0.0',
            'timestamp' => now()->toIso8601String(),
        ]);
    });
});
