<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Classroom;
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
        $classrooms = Classroom::all();
        return view('admin.teachers.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nip' => 'required|unique:teachers',
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'subjects' => 'required|string',
            'classrooms' => 'required|array',
            'classrooms.*' => 'exists:classrooms,id',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make(Str::random(8)),
            'role' => 'teacher',
        ]);

        $teacher = Teacher::create([
            'nip' => $request->nip,
            'name' => $request->name,
            'barcode' => Str::random(12),
            'user_id' => $user->id,
        ]);

        $subjects = array_map('trim', explode(',', $request->subjects));
        foreach ($request->classrooms as $classroom_id) {
            foreach ($subjects as $subject_name) {
                if (!empty($subject_name)) {
                    $teacher->classrooms()->attach($classroom_id, ['subject_name' => $subject_name]);
                }
            }
        }

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil ditambahkan.');
    }

    public function edit(Teacher $teacher)
    {
        $classrooms = Classroom::all();
        $selectedClassrooms = $teacher->classrooms->pluck('id')->toArray();
        $selectedSubjects = $teacher->classrooms()->pluck('teacher_classroom_subject.subject_name')->unique()->toArray();
        return view('admin.teachers.edit', compact('teacher', 'classrooms', 'selectedClassrooms', 'selectedSubjects'));
    }

    public function update(Request $request, Teacher $teacher)
    {
        $request->validate([
            'nip' => 'required|unique:teachers,nip,' . $teacher->id,
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $teacher->user_id,
            'subjects' => 'required|string',
            'classrooms' => 'required|array',
            'classrooms.*' => 'exists:classrooms,id',
        ]);

        $teacher->update([
            'nip' => $request->nip,
            'name' => $request->name,
        ]);

        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $subjects = array_map('trim', explode(',', $request->subjects));
        $pivotData = [];
        foreach ($request->classrooms as $classroom_id) {
            foreach ($subjects as $subject_name) {
                if (!empty($subject_name)) {
                    $pivotData[$classroom_id] = ['subject_name' => $subject_name];
                }
            }
        }
        $teacher->classrooms()->sync($pivotData);

        return redirect()->route('teachers.index')->with('success', 'Guru berhasil diperbarui.');
    }

    public function destroy(Teacher $teacher)
    {
        $teacher->user->delete();
        $teacher->delete();
        return redirect()->route('teachers.index')->with('success', 'Guru berhasil dihapus.');
    }
}