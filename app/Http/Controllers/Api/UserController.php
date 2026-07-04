<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BloodBank;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::paginate(25));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|string',
        ]);
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return response()->json($user, 201);
    }

    public function assignToBank(Request $request, $userId)
    {
        $request->validate(['blood_bank_id' => 'required|exists:blood_banks,id']);
        $user = User::findOrFail($userId);
        $user->bloodBanks()->syncWithoutDetaching([$request->blood_bank_id]);
        return response()->json(['message' => 'Assigned']);
    }

    public function removeFromBank(Request $request, $userId)
    {
        $request->validate(['blood_bank_id' => 'required|exists:blood_banks,id']);
        $user = User::findOrFail($userId);
        $user->bloodBanks()->detach($request->blood_bank_id);
        return response()->json(['message' => 'Removed']);
    }
}
