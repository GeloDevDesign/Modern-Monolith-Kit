<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSetting::firstOrCreate(
            ['id' => 1], // Assuming we only want one row for settings
            [
                'dashboard_text' => 'Welcome to the Dashboard',
                'terms_and_conditions' => 'Standard terms and conditions apply.',
                'notification_emails' => 'admin@example.com',
                'maintenance_mode' => false,
            ]
        );
    }
}
