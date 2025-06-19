<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\ClassSession;
use App\Models\Assignment;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class StudentLmsController extends Controller
{
    public function index()
    {
        $student = Auth::user()->student;
        $classSessions = ClassSession::where('classroom_id', $student->classroom_id)
            ->with(['materials', 'assignments'])
            ->get()
            ->groupBy('subject_name');
        return view('student.lms.index', compact('classSessions'));
    }

    public function showSession(ClassSession $classSession)
    {
        $this->authorizeStudent($classSession);
        $classSession->load([
            'materials',
            'assignments.submissions' => function ($query) {
                $query->where('student_id', Auth::user()->student->id);
            }
        ]);
        $classSession = $classSession->fresh(['materials', 'assignments.submissions']); // Pastikan data terbaru
        return view('student.lms.show_session', compact('classSession'));
    }

    public function createSubmission(Assignment $assignment)
    {
        $this->authorizeStudent($assignment->classSession); // Perbaikan: akses instance ClassSession
        $existingSubmission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', Auth::user()->student->id)
            ->first();
        return view('student.lms.create_submission', compact('assignment', 'existingSubmission'));
    }

    public function storeSubmission(Request $request, Assignment $assignment)
    {
        $this->authorizeStudent($assignment->classSession); // Perbaikan: akses instance ClassSession
        $request->validate([
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
            'notes' => 'nullable|string|max:500',
        ]);

        $student = Auth::user()->student;
        $existingSubmission = Submission::where('assignment_id', $assignment->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existingSubmission) {
            return back()->withErrors(['file' => 'Anda sudah mengumpulkan tugas ini.']);
        }

        if ($assignment->deadline < now()) {
            return back()->withErrors(['deadline' => 'Tenggat waktu pengumpulan telah lewat.']);
        }

        $data = [
            'assignment_id' => $assignment->id,
            'student_id' => $student->id,
            'notes' => $request->notes,
        ];

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('submissions', 'public');
        }

        Submission::create($data);

        return redirect()->route('student.lms.show_session', $assignment->classSession)
            ->with('success', 'Tugas berhasil dikumpulkan.');
    }

    protected function authorizeStudent(ClassSession $classSession)
    {
        if ($classSession->classroom_id !== Auth::user()->student->classroom_id) {
            abort(403, 'Unauthorized');
        }
    }
}