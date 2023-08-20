<?php

namespace App\Http\Controllers\Core;

use App\Enums\UserTypeCode;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('setting.edit', [
            'user' => $request->user(),
            'setting' => Setting::all()->first(),
            'userType' => array_column(UserTypeCode::cases(), 'value')
        ]);
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $setting = Setting::all()->first();

        $validated = $request->validateWithBag('updateSetting', [
            'url' => ['required'],
            'token' => ['required'],
            'type_code' => ['required'],
        ]);
        $setting->forcefill([
            'url' => $validated['url'],
            'token' => $validated['token'],
            'type_code' => $validated['type_code'],
        ]);
        $setting->save();
        return back()->with('status', 'setting-updated');
    }
}
