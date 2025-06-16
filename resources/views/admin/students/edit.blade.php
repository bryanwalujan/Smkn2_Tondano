<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-group { margin-bottom: 15px; }
        input, select { padding: 5px; width: 100%; }
        button { padding: 10px; background-color: #4CAF50; color: white; border: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Edit Siswa</h2>
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('students.update', $student) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>NIS</label>
            <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" required>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', $student->name) }}" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $student->user->email) }}" required>
        </div>
        <div class="form-group">
            <label>Kelas</label>
            <select name="classroom_id" required>
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ $student->classroom_id == $classroom->id ? 'selected' : '' }}>
                        {{ $classroom->full_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit">Simpan</button>
    </form>
    <a href="{{ route('students.index') }}">Kembali</a>
</body>
</html>