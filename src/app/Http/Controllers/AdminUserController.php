<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AdminUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        if ($sort = $request->input('sort')) {
            $dir = $request->input('direction', 'asc');
            $query->orderBy($sort, $dir);
        }

        $users = $query->paginate(10);

        return response()->json($users);
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->is_admin) {
            return response()->json(['message' => 'Cannot delete admin'], 403);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted']);
    }

    public function toggleBan(User $user): JsonResponse
    {
        $user->is_banned = !$user->is_banned;
        $user->save();

        return response()->json([
            'message' => $user->is_banned ? 'User banned' : 'User unbanned'
        ]);
    }

    public function verifyEmail(User $user): JsonResponse
    {
        $user->email_verified_at = now();
        $user->save();

        return response()->json(['message' => 'Email verified']);
    }
}
