<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Jadwal</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-group { margin-bottom: 15px; }
        input, select { padding: 5px; width: 100%; }
        button { padding: 10px; background-color: #4CAF50; color: white; border: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Tambah Jadwal untuk {{ $classroom->full_name }}</h2>
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('schedules.store', $classroom) }}">
        @csrf
        <div class="form-group">
            <label>Mata Pelajaran</label>
            <select name="subject_name" required>
                @foreach ($subjects as $subject)
                    <option value="{{ $subject }}">{{ $subject }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Guru</label>
            <select name="teacher_id" required>
                @foreach ($teachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label>Hari</label>
            <select name="day" required>
                <option value="Senin">Senin</option>
                <option value="Selasa">Selasa</option>
                <option value="Rabu">Rabu</option>
                <option value="Kamis">Kamis</option>
                <option value="Jumat">Jumat</option>
            </select>
        </div>
        <div class="form-group">
            <label>Waktu Mulai</label>
            <input type="time" name="start_time" required>
        </div>
        <div class="form-group">
            <label>Waktu Selesai</label>
            <input type="time" name="end_time" required>
        </div>
        <button type="submit">Simpan</button>
    </form>
    <a href="{{ route('classrooms.show', $classroom) }}">Kembali</a>
</body>
</html>