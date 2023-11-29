<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Integration;
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
}
