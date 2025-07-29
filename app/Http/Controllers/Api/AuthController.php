<?php

namespace App\Http\Controllers\Api;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PasswordResetRequest;
use App\Http\Requests\PasswordResetConfirmRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'address' => $request->input('address') ?? null,
        ]);

        $user->assignRole(Role::USER->value);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'referral_code' => $user->referral_code,
            ],
            'token' => $token,
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'credentials' => ['Invalid email or password.'],
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'referral_code' => $user->referral_code,
                'address' => $user->address,
            ],
            'token' => $token,
        ], 200);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        return response()->json([
            'message' => 'User details retrieved successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'referral_code' => $user->referral_code,
                'roles' => $user->getRoleNames(),
                'permissions' => $user->getAllPermissions()->pluck('name'),
            ],
            'token' => $request->bearerToken(),
        ], 200);
    }

    public function passwordResetRequest(PasswordResetRequest $request)
    {
        $user = User::where('email', $request->input('email'))->first();
        $code = Str::random(6);

        PasswordResetToken::updateOrCreate(
            ['email' => $user->email],
            [
                'token' => $code,
                'created_at' => now(),
            ]
        );

        Mail::raw("Your password reset code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Password Reset Code');
        });

        return response()->json([
            'message' => 'Password reset code sent to your email.',
        ], 200);
    }

    public function passwordResetConfirm(PasswordResetConfirmRequest $request)
    {
        $token = PasswordResetToken::where('email', $request->input('email'))
            ->where('token', $request->input('code'))
            ->where('created_at', '>=', now()->subMinutes(60))
            ->first();

        if (!$token) {
            throw ValidationException::withMessages([
                'code' => ['The reset code is invalid or has expired.'],
            ]);
        }

        $user = User::where('email', $request->input('email'))->first();
        $user->password = Hash::make($request->input('password'));
        $user->save();

        PasswordResetToken::where('email', $request->input('email'))->delete();

        return response()->json([
            'message' => 'Password reset successfully.',
        ], 200);
    }

    public function googleRedirect()
    {
        $url = Socialite::driver('google')->stateless()->redirect()->getTargetUrl();

        return response()->json([
            'url' => $url,
        ], 200);
    }

    public function googleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(20)),
                ]
            );

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Google login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'referral_code' => $user->referral_code,
                ],
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'google' => ['Google authentication failed.'],
            ]);
        }
    }

    public function googleLogin(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->input('access_token'));

            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'google_id' => $googleUser->id,
                    'password' => Hash::make(Str::random(20)),
                ]
            );

            $user->referral_code = strtoupper('GDV' . Str::random(4));
            $user->save();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Google login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'referral_code' => $user->referral_code,
                ],
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'google' => ['Google authentication failed.'],
            ]);
        }
    }


    public function appleRedirect()
    {

        $url = Socialite::driver('apple')->stateless()->redirect()->getTargetUrl();

        // $url = Socialite::driver('apple')
        //     ->stateless()
        //     ->redirectUrl(config('services.apple.redirect'))
        //     ->redirect()
        //     ->getTargetUrl();

        return response()->json([
            'url' => $url,
        ], 200);
    }

    public function appleCallback(Request $request)
    {
        try {
            $appleUser = Socialite::driver('apple')->stateless()->user();

            $user = User::updateOrCreate(
                ['email' => $appleUser->email],
                [
                    'name' => $appleUser->name ?? 'Apple User',
                    'apple_id' => $appleUser->id,
                    'password' => Hash::make(Str::random(20)),
                ]
            );

            $user->referral_code = strtoupper('GDV' . Str::random(4));
            $user->save();

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Apple login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'referral_code' => $user->referral_code,
                ],
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'apple' => ['Apple authentication failed.'],
            ]);
        }
    }

    public function appleLogin(Request $request)
    {
        try {
            $appleUser = Socialite::driver('apple')->stateless()->userFromToken($request->input('access_token'));

            $user = User::updateOrCreate(
                ['email' => $appleUser->email],
                [
                    'name' => $appleUser->name ?? 'Apple User',
                    'apple_id' => $appleUser->id,
                    'password' => Hash::make(Str::random(20)),
                ]
            );

            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'message' => 'Apple login successful',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'referral_code' => $user->referral_code,
                ],
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'apple' => ['Apple authentication failed.'],
            ]);
        }
    }
}
