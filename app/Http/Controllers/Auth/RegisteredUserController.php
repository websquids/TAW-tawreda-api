<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Notifications\SignUpNotification;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;


class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(RegisterRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $inputs = $request->validated();

            $user = User::create($inputs['user']);
            $user->assignRole($inputs['role']);
            $token = $user->createToken('web_login')->plainTextToken;
            DB::commit();
            event(new Registered($user));
            $user->notify(new SignUpNotification());

            return $this->sendResponse([
                'user' => $user,
                'token' => [
                    'access_token' => $token,
                    'token_type' => 'bearer',
                ],
            ], __('registered-successfully'));
        } catch (\Throwable $th) {
            DB::rollBack();
            return $this->sendError($th->getMessage(), 500);
        }
    }
    public function sendResponse($message): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,

        ], 200);
    }
    public function sendError($message, $code): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
        ], $code);
    }
}
