<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the settings already exist
        $setting = Setting::first();

        if ($setting) {
            echo "Settings already seeded.";
        } else {
            Setting::create([
                'site_title' => 'softvence',
                'app_name' => 'venzor',
                'admin_name' => 'admin panel',
                'copyright' => '© 2025 Softvence. All rights reserved.',
                'contact' => '+123456789',
                'email' => 'info@softvence.com',
                'about' => 'Softvence is a leading software development company providing innovative solutions.',
            ]);

            echo "Settings have been seeded successfully.";
        }
    }
}
