<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientController extends Controller
{


    /**
     * Display the user's profile form.
     */
    public function list(Request $request): View
    {
        return view('client', [
            'clients' => Client::all(),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function create(Request $request): View
    {
        return view('client.create');
    }

    /**
     * Display the user's profile form.
     */
    public function store(Request $request)
    {
        $validated = $request->validateWithBag('createClient', [
            'company_code' => [
                'required',
                Rule::unique('clients')->where(fn ($q) => $q->where('type_code', $request->input('type_code'))->where('client_code', $request->input('client_code')))
            ],
            'client_code' => [
                'required',
                Rule::unique('clients')->where(fn ($q) => $q->where('type_code', $request->input('type_code')))
            ],
            'type_code' => [
                'required',
                Rule::unique('clients')->where(fn ($q) => $q->where('client_code', $request->input('client_code')))
            ],
            'url' => [
                'required',
                'url',
            ],
            'token' => [
                'required',
                'string',
            ]
        ]);

        $client = new Client();
        $client->forceFill($validated)->save();
        return redirect()->route('client')->with('success', 'Client has been created.');
    }

    /**
     * Display the user's profile form.
     */
    public function edit($id): View
    {
        return view('client.edit', [
            'client' => Client::findOrFail($id),
        ]);
    }

    /**
     * Display the user's profile form.
     */
    public function delete($id)
    {
        $item = Client::findOrFail($id);
        $item->delete();
        return redirect()->route('client')->with('success', 'Client has been deleted.');
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $client = Client::findOrFail($request->input('id'));
        $validated = $request->validateWithBag('updateClient', [
            'id' => [
                'required',
                Rule::exists('clients', 'id')
            ],
            'company_code' => [
                'required',
                Rule::unique('clients')->where(fn ($q) => $q->where('type_code', $request->input('type_code'))->where('client_code', $request->input('client_code')))
            ],
            'client_code' => [
                'required',
                Rule::unique('clients')->where(fn ($q) => $q->where('type_code', $request->input('type_code')))
            ],
            'type_code' => [
                'required',
                Rule::unique('clients')->where(fn ($q) => $q->where('client_code', $request->input('client_code')))
            ],
            'url' => [
                'required',
                'url',
            ],
            'token' => [
                'required',
                'string',
            ]
        ]);

        $client->forceFill($validated)->save();
        return redirect()->route('client')->with('success', 'Client has been updated.');
    }
}
