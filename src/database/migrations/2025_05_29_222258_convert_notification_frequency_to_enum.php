<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
         DB::statement("ALTER TABLE coin_subscriptions ALTER COLUMN notification_frequency DROP DEFAULT");

         DB::statement("DO $$
            BEGIN
                IF NOT EXISTS (SELECT 1 FROM pg_type WHERE typname = 'notification_frequency_enum') THEN
                    CREATE TYPE notification_frequency_enum AS ENUM ('instant', 'daily', 'none');
                END IF;
            END
        $$;");

         DB::statement("ALTER TABLE coin_subscriptions
            ALTER COLUMN notification_frequency
            TYPE notification_frequency_enum
            USING notification_frequency::notification_frequency_enum");
    }

    public function down(): void
    {
         DB::statement("ALTER TABLE coin_subscriptions
            ALTER COLUMN notification_frequency
            TYPE VARCHAR(255)");

        // 2. Удаляем тип enum
        DB::statement("DROP TYPE IF EXISTS notification_frequency_enum");
    }
};
