<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Exports\UsersExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $role = $request->query('role', 'admin');
        $users = User::where('role', $role)->get();
        return view('admin.users', compact('users', 'role'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required'
        ]);

        // Password: 4 karakter awal email + nomor urut
        $emailPrefix = substr($request->email, 0, 4);
        $userCount = User::count() + 1;
        $generatedPassword = $emailPrefix . $userCount;

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($generatedPassword),
            'password_changed_at' => null, // Pastikan null agar terbaca password default di Excel
        ]);

        return back()->with('success', "Akun berhasil dibuat! Password default: " . $generatedPassword);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Jika password baru diisi (optional)
        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
            $user->password_changed_at = now(); // Penanda untuk Excel
        }

        $user->save();
        return back()->with('success', 'Data akun berhasil diperbarui!');
    }

    public function editProfile()
    {
        // Mengambil data user yang sedang login
        $user = Auth::user();
        // Menggunakan view yang sudah ada (admin/users_edit.blade.php)
        return view('admin.users_edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('new_password')) {
            $user->password = Hash::make($request->new_password);
            $user->password_changed_at = now();
        }

        $user->save();
        return back()->with('success', 'Profile updated successfully!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Akun berhasil dihapus.');
    }

     public function exportExcel($role)
     {
        return Excel::download(new UsersExport($role), 'admin-accounts-' . $role . '.xlsx');
     }
}
