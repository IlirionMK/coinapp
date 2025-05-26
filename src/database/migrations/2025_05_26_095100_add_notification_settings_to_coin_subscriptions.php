<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('coin_subscriptions', function (Blueprint $table) {
            $table->string('notification_frequency')
                ->default('instant')
                ->checkIn(['instant', 'daily', 'none']);

            $table->decimal('change_threshold', 5, 2)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('coin_subscriptions', function (Blueprint $table) {
            $table->dropColumn(['notification_frequency', 'change_threshold']);
        });
    }
};
