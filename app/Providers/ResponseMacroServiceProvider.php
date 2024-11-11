<?php

namespace App\Providers;

use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ResponseMacroServiceProvider extends ServiceProvider {
    /**
     * Register services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        Response::macro('apiResponse', function ($payload, $message = 'Operation successful', $status = true, $statusCode = 200) {
            return response()->json([
                'status' => $status,
                'message' => $message,
                'payload' => $payload,
            ], $statusCode);
        });
    }
}
