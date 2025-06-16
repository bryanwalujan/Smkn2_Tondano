<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Kelas</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-group { margin-bottom: 15px; }
        input, select { padding: 5px; width: 100%; }
        button { padding: 10px; background-color: #4CAF50; color: white; border: none; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Tambah Kelas</h2>
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('classrooms.store') }}">
        @csrf
        <div class="form-group">
            <label>Tingkat</label>
            <select name="level" required>
                <option value="10">10</option>
                <option value="11">11</option>
                <option value="12">12</option>
            </select>
        </div>
        <div class="form-group">
            <label>Jurusan</label>
            <input type="text" name="major" required placeholder="Contoh: RPL, TKJ">
        </div>
        <div class="form-group">
            <label>Kode Kelas</label>
            <input type="text" name="class_code" required placeholder="Contoh: A, B, C">
        </div>
        <button type="submit">Simpan</button>
    </form>
    <a href="{{ route('classrooms.index') }}">Kembali</a>
</body>
</html>