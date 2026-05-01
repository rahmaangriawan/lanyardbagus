<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $settings = Setting::all()->pluck('value', 'key');
            return response()->json($settings);
        } catch (\Exception $e) {
            return response()->json([], 200);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'settings' => ['required', 'array'],
                'settings.*.key' => ['required', 'string'],
                'settings.*.value' => ['nullable', 'string'],
                'settings.*.group' => ['required', 'string'],
            ]);

            foreach ($validated['settings'] as $setting) {
                Setting::set($setting['key'], $setting['value'], $setting['group']);
            }

            return response()->json(['message' => 'Settings updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update settings', 'error' => $e->getMessage()], 500);
        }
    }
}
