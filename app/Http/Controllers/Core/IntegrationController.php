<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IntegrationController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        $integrations = Integration::all();
        return view('integrations', compact(['integrations']));
    }

    // /**
    //  * Display the user's profile form.
    //  */
    // public function edit(Request $request): View
    // {
    //     return view('integration.edit', [
    //         'user' => $request->user(),
    //         'setting' => Integration::all()->first(),
    //         'userType' => array_column(ProviderTypeCode::cases(), 'value')
    //     ]);
    // }

    // /**
    //  * Update the user's password.
    //  */
    // public function update(Request $request): RedirectResponse
    // {
    //     $setting = Integration::all()->first();

    //     $validated = $request->validateWithBag('updateSetting', [
    //         'url' => ['required'],
    //         'token' => ['required'],
    //         'type_code' => ['required'],
    //     ]);

    //     $setting->forcefill([
    //         'url' => $validated['url'],
    //         'token' => $validated['token'],
    //         'type_code' => $validated['type_code'],
    //     ]);

    //     $setting->save();
    //     return back()->with('status', 'setting-updated');
    // }
}
