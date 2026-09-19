<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function edit()
    {
        $settings = Setting::pluck('value', 'key')->toArray();

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_address' => 'nullable|string|max:500',
            'business_phone' => 'nullable|string|max:20',
            'business_email' => 'nullable|email|max:255',
            'tax_rate' => 'required|numeric|min:0|max:100',
            'currency' => 'required|string|max:10',
            'receipt_footer' => 'nullable|string',
            'low_stock_threshold' => 'required|integer|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $old = [];
            foreach ($validated as $key => $value) {
                $old[$key] = Setting::get($key);
                Setting::set($key, $value);
            }
            AuditLogHelper::logGlobal('settings_updated', $old, $validated);
        });

        return back()->with('success', 'Settings updated successfully.');
    }
}
