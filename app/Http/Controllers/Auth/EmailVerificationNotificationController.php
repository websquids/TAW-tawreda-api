<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['status' => __('already-verified')]);
        }

        $request->user()->sendEmailVerificationNotification();

        return $this->sendResponse('', __('verification-link-sent'));
    }
    public function sendResponse($message): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,

        ], 200);
    }
}
