<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Show all users
    public function index()
    {
        $users = DB::table('users')->get();
        return view('welcome', ['users' => $users]);
    }

    // Store new user
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'password' => 'required|min:6',
        ]);

        DB::table('users')->insert([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
        ]);

        return redirect('/')->with('success', 'User added successfully!');
    }

    // Delete user
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();

        return redirect('/')->with('success', 'User deleted successfully!');
    }
}