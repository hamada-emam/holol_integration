<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\Provider\ProviderDeleteRequest;
use App\Http\Requests\Provider\ProviderStoreRequest;
use App\Http\Requests\Provider\ProviderUpdateRequest;
use App\Models\Provider;
use Illuminate\Support\Facades\Session;

class ProviderController extends Controller
{
    /**
     * 
     *
     * @param [type] $id
     * @return void
     */
    function index()
    {
        $providers = Provider::all();
        return view('pages.providers', compact(['providers']));
    }

    /**
     * 
     *
     * @param ProviderStoreRequest $request
     * @return void
     */
    function store(ProviderStoreRequest $request)
    {
        $validatedData = $request->validated();

        if ((new Provider())->forceFill($validatedData)->save())
            Session::flash('success', 'Provider stored successfully!');
        else
            Session::flash('failed', 'Failed to store Provider!');

        return redirect('providers');
    }

    /**
     * 
     *
     * @param ProviderUpdateRequest $request
     * @return void
     */
    function update(ProviderUpdateRequest $request)
    {
        $validatedData = $request->validated();

        if (Provider::find($request->id)->forceFill($validatedData)->save())
            Session::flash('success', 'Provider Updated successfully!');
        else
            Session::flash('failed', 'Failed to Update provider!');

        return redirect('providers');
    }

    /**
     * 
     *
     * @param ProviderDeleteRequest $request
     * @return void
     */
    function destroy(ProviderDeleteRequest $request)
    {
        if (Provider::find($request->id)->delete())
            Session::flash('success', 'Provider deleted successfully!');
        else
            Session::flash('failed', 'Failed to Delete provider!');

        return redirect('providers');
    }
}
