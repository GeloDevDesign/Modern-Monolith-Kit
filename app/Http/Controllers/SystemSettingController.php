<?php

namespace App\Http\Controllers;

use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SystemSettingController extends Controller
{
    public function edit()
    {
        $settings = SystemSetting::first() ?? new SystemSetting();

        return Inertia::render('Settings/General', [
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'dashboard_text' => 'nullable|string',
            'terms_and_conditions' => 'nullable|string',
            'notification_emails' => 'nullable|string',
            'maintenance_mode' => 'boolean',
        ]);

        $settings = SystemSetting::firstOrNew();
        $settings->fill($data);
        $settings->save();

        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
