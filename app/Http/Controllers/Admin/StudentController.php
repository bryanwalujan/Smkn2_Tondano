<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\User;
use App\Models\Classroom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('classroom', 'user')->get();
        return view('admin.students.index', compact('students'));
    }

    public function create()
    {
        $classrooms = Classroom::all();
        return view('admin.students.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:students',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(8)),
            'role' => 'student',
        ]);

        Student::create([
            'nis' => $request->nis,
            'name' => $request->name,
            'barcode' => Str::random(12),
            'classroom_id' => $request->classroom_id,
            'user_id' => $user->id,
        ]);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function edit(Student $student)
    {
        $classrooms = Classroom::all();
        return view('admin.students.edit', compact('student', 'classrooms'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'nis' => 'required|unique:students,nis,' . $student->id,
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $student->user_id,
            'classroom_id' => 'required|exists:classrooms,id',
        ]);

        $student->update([
            'nis' => $request->nis,
            'name' => $request->name,
            'classroom_id' => $request->classroom_id,
        ]);

        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return redirect()->route('students.index')->with('success', 'Siswa berhasil diperbarui.');
    }

    public function destroy(Student $student)
    {
        $student->user->delete(); // Hapus akun pengguna terkait
        $student->delete(); // Hapus data siswa
        return redirect()->route('students.index')->with('success', 'Siswa berhasil dihapus.');
    }
}