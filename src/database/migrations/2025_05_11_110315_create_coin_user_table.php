<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coin_user', function (Blueprint $table) {
            $table->foreignId('coin_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['coin_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coin_user');
    }
};
