<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Contracts\Providers\auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    // Meski terdeteksi sebagai error, JANGAN DIHAPUS!
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except' => ['login', 'register']
        ]);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User berhasil terdaftar',
                'user' => $user
            ], 201);
        }

        return response()->json([
            'success' => false,
            'message' => 'User sudah terdaftar'
        ], 409);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password anda salah'
            ], 422);
        }

        $user = auth('api')->user();
        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token
        ], 200);
    }

    public function me()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        try {
            auth('api')->logout();
            return response()->json([
                'success' => true,
                'message' => 'Logout berhasil'
            ]);
        } catch (JWTException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Logout gagal, silahkan coba lagi'
            ], 500);
        }
    }

    public function refresh()
    {
        try {
            $newToken = auth('api')->refresh();

            if (!$newToken) {
                return response()->json([
                    'message' => 'Gagal me-refresh token'
                ], 401);
            }

            $user = auth('api')->user();
            return response()->respondWithToken($newToken, $user);
        } catch (TokenBlacklistedException $e) {
            return response()->json([
                'message' => 'Token sudah di-blacklist dan tidak bisa di-refresh lagi'
            ], 401);
        } catch (TokenInvalidException $e) {
            return response()->json([
                'message' => 'Token tidak valid dan tidak dapat di-refresh lagi'
            ], 401);
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Tidak dapat di-refresh lagi' . $e->getMessage()
            ], 401);
        }
    }

    public function respondWithToken($token, $user, $statusCode = 200)
    {
        $ttlInMinutes = null;

        if (auth('api')->factory() && method_exist(auth('api')->factory(), 'getTTL')) {
            $ttlInMinutes = auth('api')->factory()->getTTL();
        } else {
            $ttlInMinutes = config('jwt.ttl');
        }

        $expiresInSeconds = $ttlInMinutes ? $ttlInMinutes * 60 : null;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $expiresInSeconds,
            'user' => $user
        ], $statusCode);
    }
}
