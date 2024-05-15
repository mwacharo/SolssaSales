<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class UsersApiController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'message' => 'Users fetched successfully',
            'data' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|max:15|unique:users',
        ]);

        $password = Str::random(8);
        $validatedData['password'] = Hash::make($password);

        $user = User::create($validatedData);

        return response()->json([
            'message' => 'User created successfully',
            'user' => $user,
            'password' => $password,
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'string|email',
            'phone' => 'sometimes|max:15',
            'password' => 'nullable|string|min:8',
        ]);

        if (!$request->filled('password')) {
            unset($validatedData['password']);
        } else {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user,
        ]);
    }

    public function toggleStatus($id)
    {
        $user = User::find($id);

        if (!$user) {
            return Response::json(['message' => 'User not found'], 404);
        }

        // Toggle the status
        $user->status = $user->status ? 0 : 1;
        $user->save();

        return Response::json([
            'message' => 'User status toggled successfully',
            'data' => $user,
        ], 200);
    }
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 204);
    }
}