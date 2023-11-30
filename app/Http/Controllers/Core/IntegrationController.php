<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Integration\IntegrationDeleteRequest;
use App\Http\Requests\Integration\IntegrationStoreRequest;
use App\Http\Requests\Integration\IntegrationUpdateRequest;
use App\Models\Integration;
use App\Models\Provider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class IntegrationController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index(Request $request): View
    {
        $integrations = Integration::with('user')->get();
        $providers = Provider::active()->get();
        $users = User::isAdmin(false)->get();
        $isAdmin = auth()->user()->isAdmin;;
        return view('pages.integrations', compact(['integrations', 'providers', 'isAdmin', 'users']));
    }

    /**
     * 
     *
     * @param IntegrationStoreRequest $request
     * @return void
     */
    function store(IntegrationStoreRequest $request)
    {
        $validatedData = $request->validated();

        if ((new Integration())->forceFill($validatedData)->save())
            Session::flash('success', 'Integration stored successfully!');
        else
            Session::flash('failed', 'Failed to store Integration!');

        return redirect('integrations');
    }

    /**
     * 
     *
     * @param IntegrationUpdateRequest $request
     * @return void
     */
    function update(IntegrationUpdateRequest $request)
    {
        $validatedData = $request->validated();

        if (Integration::find($request->id)->forceFill($validatedData)->save())
            Session::flash('success', 'Integration Updated successfully!');
        else
            Session::flash('failed', 'Failed to Update Integration!');

        return redirect('integrations');
    }

    /**
     * 
     *
     * @param IntegrationDeleteRequest $request
     * @return void
     */
    function destroy(IntegrationDeleteRequest $request)
    {
        if (Integration::find($request->id)->delete())
            Session::flash('success', 'Integration deleted successfully!');
        else
            Session::flash('failed', 'Failed to Delete Integration!');

        return redirect('integrations');
    }
}
