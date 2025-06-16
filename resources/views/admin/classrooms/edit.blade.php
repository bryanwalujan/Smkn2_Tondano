<!DOCTYPE html>
<html lang="id">
<head>
    <title>Edit Kelas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-group { margin-bottom: 15px; }
        input, select { padding: 5px; width: 100%; }
        button { padding: 10px; background-color: #4CAF50; color: white; border: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Edit Kelas</h2>
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('classrooms.update', $classroom) }}">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Tingkat</label>
            <select name="level" required>
                <option value="10" {{ $classroom->level == '10' ? 'selected' : '' }}>10</option>
                <option value="11" {{ $classroom->level == '11' ? 'selected' : '' }}>11</option>
                <option value="12" {{ $classroom->level == '12' ? 'selected' : '' }}>12</option>
            </select>
        </div>
        <div class="form-group">
            <label>Jurusan</label>
            <input type="text" name="major" value="{{ old('major', $classroom->major) }}" required>
        </div>
        <div class="form-group">
            <label>Kode Kelas</label>
            <input type="text" name="class_code" value="{{ old('class_code', $classroom->class_code) }}" required>
        </div>
        <button type="submit">Simpan</button>
    </form>
    <a href="{{ route('classrooms.index') }}">Kembali</a>
</body>
</html>