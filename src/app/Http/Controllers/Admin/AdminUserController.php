<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminUserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $cacheKey = 'admin_users:' . md5(json_encode($request->all()));

        if (Cache::tags(['admin_users'])->has($cacheKey)) {
            \Log::info('[CACHE HIT] /admin/users', ['key' => $cacheKey, 'params' => $request->all()]);
        } else {
            \Log::info('[CACHE MISS] /admin/users', ['key' => $cacheKey, 'params' => $request->all()]);
        }

        $users = Cache::tags(['admin_users'])->remember($cacheKey, now()->addMinutes(10), function () use ($request) {
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

            return $query->paginate(10);
        });

        return response()->json($users);
    }

    public function destroy(User $user): JsonResponse
    {
        if ($user->is_admin) {
            return response()->json(['message' => 'Cannot delete admin'], 403);
        }

        $user->delete();
        $this->clearAdminUserCache();

        return response()->json(['message' => 'User deleted']);
    }

    public function toggleBan(User $user): JsonResponse
    {
        $user->is_banned = !$user->is_banned;
        $user->save();

        $this->clearAdminUserCache();

        return response()->json([
            'message' => $user->is_banned ? 'User banned' : 'User unbanned'
        ]);
    }

    public function verifyEmail(User $user): JsonResponse
    {
        $user->email_verified_at = now();
        $user->save();

        $this->clearAdminUserCache();

        return response()->json(['message' => 'Email verified']);
    }

    private function clearAdminUserCache(): void
    {
        Cache::tags(['admin_users'])->flush();
    }
}
