<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-group { margin-bottom: 15px; }
        input, select { padding: 5px; width: 100%; }
        button { padding: 10px; background-color: #4CAF50; color: white; border: none; }
    </style>
</head>
<body>
    <h2>Tambah Siswa</h2>
    <form method="POST" action="{{ route('students.store') }}">
        @csrf
        <div class="form-group">
            <label>NIS</label>
            <input type="text" name="nis" required>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <div class="form-group">
            <label>Kelas</label>
            <select name="classroom_id" required>
                @foreach ($classrooms as $classroom)
                    <option value="{{ $classroom->id }}">{{ $classroom->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>