<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Guru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-group { margin-bottom: 15px; }
        input, select { padding: 5px; width: 100%; }
        button { padding: 10px; background-color: #4CAF50; color: white; border: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Edit Guru</h2>
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('teachers.update', $teacher) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>NIP</label>
            <input type="text" name="nip" value="{{ old('nip', $teacher->nip) }}" required>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $teacher->user->email) }}" required>
        </div>
        <div class="form-group">
            <label>Mata Pelajaran (pisahkan dengan koma)</label>
            <input type="text" name="subjects" value="{{ old('subjects', implode(', ', $selectedSubjects)) }}" placeholder="Contoh: Matematika, Bahasa Inggris" required>
        </div>
        <div class="form-group">
            <label>Kelas</label>
            <select name="classrooms[]" multiple required>
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}" {{ in_array($classroom->id, $selectedClassrooms) ? 'selected' : '' }}>
                        {{ $classroom->full_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit">Simpan</button>
    </form>
    <a href="{{ route('teachers.index') }}">Kembali</a>
</body>
</html>