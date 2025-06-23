<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth; // <-- PENTING! Tambahkan baris ini

class UserController extends Controller
{
    public function index()
    {
        // =======================================================
        // ==> PERUBAHAN DI SINI: dari auth()->id() menjadi Auth::id()
        // =======================================================
        $users = User::where('id', '!=', Auth::id())->latest()->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        // ... (method store tidak ada perubahan)
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'in:admin,atasan,kasir'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);
        $path = null;
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
        }
        User::create([
            'name' => $request->name, 'email' => $request->email, 'role' => $request->role,
            'password' => Hash::make($request->password), 'profile_photo_path' => $path,
        ]);
        return redirect()->route('admin.users.index')->with('success', 'User baru berhasil dibuat.');
    }

    public function edit($user)
    {
        $user = User::findOrFail($user);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $user)
    {
        // ... (method update tidak ada perubahan)
        $userObject = User::findOrFail($user);
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$userObject->id],
            'role' => ['required', 'in:admin,atasan,kasir'],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'profile_photo' => ['nullable', 'image', 'max:2048'],
        ]);
        $userData = $request->only(['name', 'email', 'role']);
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        if ($request->hasFile('profile_photo')) {
            if ($userObject->profile_photo_path) {
                Storage::disk('public')->delete($userObject->profile_photo_path);
            }
            $userData['profile_photo_path'] = $request->file('profile_photo')->store('profile-photos', 'public');
        }
        $userObject->update($userData);
        return redirect()->route('admin.users.index')->with('success', 'Data user berhasil diperbarui.');
    }

    public function destroy($user)
    {
        $user = User::findOrFail($user);

        // =======================================================
        // ==> PERUBAHAN DI SINI: dari auth()->id() menjadi Auth::id()
        // =======================================================
        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}
