<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Transaction; // Assuming you have a transaction model that relates to users
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Display a listing of users
    public function index()
    {
        // Get all users
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    // Show details of a specific user
    public function show($id)
    {
        $user = User::findOrFail($id);
        $transactions = Transaction::where('user_id', $user->id)->get(); // Get transactions for this user

        return view('admin.users.show', compact('user', 'transactions'));
    }

    // Update user status (active/inactive)
    public function updateStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->is_active = $request->status; // Assuming 'status' is passed with 1 or 0 for active/inactive
        $user->save();

        return redirect()->route('admin.users.index')->with('status', 'User status updated successfully');
    }

    // Show the form for editing a user (if needed)
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    // Update user details (example for editing a user)
    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . $id,
        'role' => 'required|in:admin,customer,courier',
        // 'is_active' => 'required|boolean',
    ]);

    $user = User::findOrFail($id);
    $user->update($request->only('name', 'email', 'role'));

    return redirect()->route('admin.users.index')->with('status', 'User berhasil diperbarui');
}


    public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|unique:users,email',
        'password' => 'required|string|min:6',
        'role' => 'required|in:customer,admin,courier',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        // 'is_active' => true,
    ]);

    return redirect()->route('admin.users.index')->with('status', 'Pengguna berhasil ditambahkan!');
}
}
