<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // SHOW USERS
    public function index()
    {
        $users = DB::table('users')->get();
        return view('welcome', compact('users'));
    }

    // ADD USER (AJAX)
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'name' => 'required',
            'password' => 'required|min:6',
        ]);

        $imageName = null;

        // IMAGE STORE
        if($request->hasFile('profile_image'))
        {
            $image = $request->file('profile_image');

            // CREATE UNIQUE NAME
            $imageName = time().'.'.$image->getClientOriginalExtension();

            // STORE IMAGE
            $image->storeAs('users', $imageName, 'public');
        }

        $id = DB::table('users')->insertGetId([
            'email' => $request->email,
            'name' => $request->name,
            'password' => Hash::make($request->password),
            'profile' => $imageName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User added successfully!',
            'user' => [
                'id' => $id,
                'email' => $request->email,
                'name' => $request->name
            ]
        ]);
    }

    // UPDATE USER (AJAX)
    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $id,
            'name' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $data = [
            'email' => $request->email,
            'name' => $request->name,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        DB::table('users')->where('id', $id)->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully!',
            'user' => [
                'id' => $id,
                'email' => $request->email,
                'name' => $request->name
            ]
        ]);
    }

    // DELETE USER (AJAX)
    public function destroy($id)
    {
        DB::table('users')->where('id', $id)->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully!'
        ]);
    }
}