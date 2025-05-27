<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->text('title')->change();
            $table->text('url')->change();
            $table->text('source')->change();
            $table->text('summary')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->string('title', 255)->change();
            $table->string('url', 255)->change();
            $table->string('source', 255)->change();
            $table->string('summary', 255)->nullable()->change();
        });
    }
};
