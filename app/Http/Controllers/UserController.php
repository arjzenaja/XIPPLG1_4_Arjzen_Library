<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'phone' => 'required|string',
        ]);

        $validatedData['password'] = bcrypt($validatedData['password']);
        
        $user = User::create($validatedData);

        return response()->json([
            'message' => 'User berhasil dibuat.',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        if(!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 400);
        }

        return response()->json([
            "message" => "User ditemukan",
            "user" => [
                'id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->all());

        return response()->json( [
            'message' => 'User berhasil di update',
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus', 
            'user' => [
                'id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ]
        ], 200);
    }
}
