<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Models\UserSetting;
use App\Services\UserDeletionService;

class ProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        return response()->json($request->user());
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'regex:/^[\pL\s\-]+$/u'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return response()->json(['message' => 'Profile updated']);
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (!Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 422);
        }

        $user->password = Hash::make($validated['new_password']);
        $user->save();

        return response()->json(['message' => 'Password updated']);
    }

    public function destroy(Request $request, UserDeletionService $service): JsonResponse
    {
        $request->validate([
            'password' => ['required']
        ]);

        $user = $request->user();

        if (!Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages([
                'password' => ['Incorrect password.']
            ]);
        }

        $service->delete($user);

        return response()->json(['message' => 'Account deleted.']);
    }

    public function getSettings(Request $request): JsonResponse
    {
        $settings = $request->user()->settings;

        return response()->json([
            'notification_frequency' => $settings?->notification_frequency ?? 'daily',
            'change_threshold' => $settings?->change_threshold ?? null,
        ]);
    }

    public function updateSettings(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'notification_frequency' => ['required', Rule::in(['instant', 'daily', 'none'])],
            'change_threshold' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $user = $request->user();
        $settings = $user->settings()->firstOrCreate([]);
        $settings->update($validated);

        return response()->json(['message' => 'Notification settings updated.']);
    }
}
