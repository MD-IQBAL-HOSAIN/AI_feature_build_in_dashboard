<?php

namespace Database\Seeders;

use App\Models\Language;
use Illuminate\Database\Seeder;

class LanguageSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $french = Language::where('code', 'fr')->first();

        if (!$french) {
            $french = new Language();
            $french->name = 'Français';
            $french->code = 'fr';
            $french->status = 'active';
            $french->save();
        } else {
            $this->command->info('Language (fr) already exists, skipped seeding.');
        }

        $english = Language::where('code', 'en')->first();

        if (!$english) {
            $english = new Language();
            $english->name = 'English';
            $english->code = 'en';
            $english->status = 'active';
            $english->save();
        } else {
            $this->command->info('Language (en) already exists, skipped seeding.');
        }
    }
}
