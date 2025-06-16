<!DOCTYPE html>
<html lang="id">
<head>
    <title>Tambah Guru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        .form-group { margin-bottom: 15px; }
        input { padding: 5px; width: 100%; }
        button { padding: 10px; background-color: #4CAF50; color: white; border: none; }
    </style>
</head>
<body>
    <h2>Tambah Guru</h2>
    <form method="POST" action="{{ route('teachers.store') }}">
        @csrf
        <div class="form-group">
            <label>NIP</label>
            <input type="text" name="nip" required>
        </div>
        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="name" required>
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>