<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        // Ambil data user lengkap dengan nama jabatan dan levelnya
        $users = DB::table('users')
            ->leftJoin('user_jabatan', 'users.id_jabatan', '=', 'user_jabatan.id_jabatan')
            ->leftJoin('user_level', 'users.id_level', '=', 'user_level.id_level')
            ->select(
                'users.*',
                'user_jabatan.nama_jabatan',
                'user_level.nama_level'
            )
            ->orderBy('users.created_at', 'desc')
            ->get(); // Pakai get() karena pagination bakal dihandle sama DataTable di sisi client

        return view('users.index', compact('users'));
    }

    public function create()
    {
        $jabatans = DB::table('user_jabatan')->get();
        $levels = DB::table('user_level')->get();
        return view('users.create', compact('jabatans', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed|min:8',
            'id_jabatan' => 'required',
            'id_level' => 'required',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_jabatan' => $request->id_jabatan, // Simpan ID-nya
            'id_level' => $request->id_level,     // Simpan ID-nya
        ]);

        return redirect()->route('users.index')->with('success', 'Staff baru berhasil didaftarkan!');
    }

    public function edit(User $user)
    {
        $jabatans = DB::table('user_jabatan')->get();
        $levels = DB::table('user_level')->get();
        return view('users.edit', compact('user', 'jabatans', 'levels'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => ['confirmed', Rules\Password::defaults()]]);
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'Data user berhasil diupdate!');
    }

    public function destroy(User $user)
    {
        try {
            // Cegah hapus diri sendiri
            if ($user->id === auth()->id()) {
                return redirect()->route('users.index')->with('error', 'Nggak bisa hapus akun sendiri!');
            }

            $user->delete();
            return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('users.index')->with('error', 'Gagal menghapus user: ' . $e->getMessage());
        }
    }
}