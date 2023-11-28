<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserDeleteRequest;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    /**
     * 
     *
     * @param [type] $id
     * @return void
     */
    function index()
    {
        $users = User::isAdmin(false)->get();
        return view('pages.users', compact(['users']));
    }

    /**
     * 
     *
     * @param UserStoreRequest $request
     * @return void
     */
    function store(UserStoreRequest $request)
    {
        $validatedData = $request->validated();
        if ($validatedData['password']) $validatedData['password'] = Hash::make($validatedData['password']);

        if ((new User())->forceFill($validatedData)->save())
            Session::flash('success', 'User stored successfully!');
        else
            Session::flash('failed', 'Failed to store user!');

        return redirect('users');
    }

    /**
     * 
     *
     * @param Request $request
     * @return void
     */
    function update(UserUpdateRequest $request)
    {
        $validatedData = $request->validated();
        if (@$validatedData['password']) $validatedData['password'] = Hash::make($validatedData['password']);

        if (User::find($request->id)->forceFill($validatedData)->save())
            Session::flash('success', 'User stored successfully!');
        else
            Session::flash('failed', 'Failed to store user!');

        return redirect('users');
    }

    /**
     * 
     *
     * @param Request $request
     * @return void
     */
    function destroy(UserDeleteRequest $request)
    {
        if (User::find($request->id)->delete())
            Session::flash('success', 'User deleted successfully!');
        else
            Session::flash('failed', 'Failed to Delete user!');

        return redirect('users');
    }
}
