<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('user_settings');
    }

    public function down(): void
    {
        Schema::create('user_settings', function ($table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('notification_frequency')->default('daily');
            $table->double('change_threshold')->nullable();
            $table->timestamps();
        });
    }
};
