<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Actions\CreateUserAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterRequest;
use Illuminate\Validation\ValidationException;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request, CreateUserAction $createUserAction): JsonResponse
    {
        $data = $request->validated();

        try {
                DB::beginTransaction();

                $createUserAction->executeUserCreation($data);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'msg' => 'Account created successfully',
                    'data' => [],
                ], Response::HTTP_CREATED);

        } catch (\Throwable $th) {
            DB::rollback();
            Log::error($th);
            return response()->json([
                'status' => 'fail',
                'msg' => 'Something went wrong, please try again',
                'data' => [],
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are incorrect.'],
            ]);
        }

        //Delete all current tokens
        $user->tokens()->delete();

        $token = $user->createToken("$user->name token")->accessToken;

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' => new UserResource($user),
                'token'  => $token,
            ],
        ], Response::HTTP_OK);
    }
}
