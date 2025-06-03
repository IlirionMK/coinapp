<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserDeletionService
{
    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            $user->coinSubscriptions()->detach();
            $user->settings()?->delete();
            $user->delete();
            Cache::tags(['admin_users'])->flush();

            Log::info('User deleted', ['user_id' => $user->id]);
        });
    }
}
