<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = Teacher::with('user')->get();
        return view('admin.teachers.index', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teachers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:teachers',
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(8)),
            'role' => 'teacher',
        ]);

        Teacher::create([
            'nip' => $request->nip,
            'name' => $request->name,
            'barcode' => Str::random(12),
            'user_id' => $user->id,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }
}