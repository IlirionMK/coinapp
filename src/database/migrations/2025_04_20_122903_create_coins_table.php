<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coins', function (Blueprint $table) {
            $table->id();
            $table->string('coingecko_id')->unique();
            $table->string('symbol');
            $table->string('name');
            $table->string('image')->nullable();
            $table->decimal('price', 20, 8)->nullable();
            $table->bigInteger('market_cap')->nullable();
            $table->integer('market_cap_rank')->nullable();
            $table->decimal('price_change_percentage_24h', 10, 4)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coins');
    }
};
