<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\StudentAttendance;
use App\Models\TeacherAttendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function showScanPage()
    {
        return view('attendance.scan');
    }

    public function scanBarcode(Request $request)
    {
        $request->validate([
            'barcode' => 'required|string',
        ]);

        try {
            $barcode = $request->barcode;
            $today = now()->toDateString();
            $currentTime = now()->toTimeString();

            // Cek di tabel students
            $student = Student::where('barcode', $barcode)->first();
            if ($student) {
                $result = $this->processAttendance(
                    StudentAttendance::class,
                    'student_id',
                    $student->id,
                    $today,
                    $currentTime,
                    $student->name
                );
                return response()->json($result, $result['success'] ? 200 : 400);
            }

            // Cek di tabel teachers
            $teacher = Teacher::where('barcode', $barcode)->first();
            if ($teacher) {
                $result = $this->processAttendance(
                    TeacherAttendance::class,
                    'teacher_id',
                    $teacher->id,
                    $today,
                    $currentTime,
                    $teacher->name
                );
                return response()->json($result, $result['success'] ? 200 : 400);
            }

            return response()->json([
                'success' => false,
                'message' => 'Barcode tidak valid atau tidak terdaftar'
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error scanning barcode: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    private function processAttendance($model, $idField, $id, $today, $currentTime, $name)
    {
        $existingAttendance = $model::where($idField, $id)
            ->whereDate('tanggal', $today)
            ->first();

        if ($existingAttendance) {
            if ($existingAttendance->waktu_pulang) {
                return [
                    'success' => false,
                    'message' => $name . ' sudah melakukan absen hari ini',
                    'type' => 'already_done',
                    'name' => $name,
                    'time' => $currentTime
                ];
            }

            // Cek selisih waktu minimal 1 jam (60 menit)
            $checkInTime = Carbon::parse($existingAttendance->waktu_masuk);
            $currentTimeCarbon = Carbon::parse($currentTime);
            $minutesDifference = $checkInTime->diffInMinutes($currentTimeCarbon);

            if ($minutesDifference < 60) {
                return [
                    'success' => false,
                    'message' => $name . ', absen pulang terlalu cepat. Harus menunggu minimal 1 jam setelah absen masuk.',
                    'type' => 'too_soon',
                    'name' => $name,
                    'time' => $currentTime
                ];
            }

            $existingAttendance->update([
                'waktu_pulang' => $currentTime,
                'status' => 'hadir',
                'metode_absen' => 'barcode'
            ]);

            return [
                'success' => true,
                'message' => 'Absensi pulang ' . $name . ' berhasil',
                'type' => 'check_out',
                'name' => $name,
                'time' => $currentTime
            ];
        }

        $model::create([
            $idField => $id,
            'tanggal' => $today,
            'waktu_masuk' => $currentTime,
            'status' => 'hadir',
            'metode_absen' => 'barcode'
        ]);

        return [
            'success' => true,
            'message' => 'Absensi masuk ' . $name . ' berhasil',
            'type' => 'check_in',
            'name' => $name,
            'time' => $currentTime
        ];
    }
}