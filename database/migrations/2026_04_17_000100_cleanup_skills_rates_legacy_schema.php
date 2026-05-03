<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Safely drop legacy skills/rates schema for already-migrated databases.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        try {
            // Drop pivot/translation tables first, then base tables.
            foreach (['worker_skills', 'skill_translations', 'worker_rates', 'rate_translations', 'employee_addresses'] as $table) {
                if (Schema::hasTable($table)) {
                    Schema::drop($table);
                }
            }

            if (Schema::hasTable('skills')) {
                Schema::drop('skills');
            }

            if (Schema::hasTable('rate')) {
                Schema::drop('rate');
            }

            // Drop onboarding flags if they still exist on legacy databases.
            if (Schema::hasTable('users')) {
                $dropColumns = [];

                foreach (['is_skills_completed', 'is_rates_completed'] as $column) {
                    if (Schema::hasColumn('users', $column)) {
                        $dropColumns[] = $column;
                    }
                }

                if ($dropColumns !== []) {
                    Schema::table('users', function (Blueprint $table) use ($dropColumns): void {
                        $table->dropColumn($dropColumns);
                    });
                }
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    /**
     * This cleanup migration is intentionally irreversible.
     */
    public function down(): void
    {
        // No-op.
    }
};
