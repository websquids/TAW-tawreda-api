<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Models\FcmToken;
use Illuminate\Http\Request;

class FcmTokenController extends Controller
{
    public function edit(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required|string',
                'is_active' => 'required|boolean'
            ]);
            $fcmToken = FcmToken::where('fcm_token', $request->token)->first();

            if (!$fcmToken) {
                return response()->json([
                    'status' => false,
                    'message' => 'FCM token not found'
                ], 404);
            }

            $fcmToken->update([
                'is_active' => $request->is_active
            ]);

            return response()->json([
                'status' => true,
                'message' => 'FCM token status updated successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating FCM token status: ' . $e->getMessage()
            ], 500);
        }
    }
}
