<!DOCTYPE html>
<html lang="id">
<head>
    <title>Dashboard Admin</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        a { margin-right: 10px; color: blue; }
        button { padding: 10px; background-color: #f44336; color: white; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <h2>Dashboard Admin</h2>
    <a href="{{ route('students.index') }}">Kelola Siswa</a>
    <a href="{{ route('teachers.index') }}">Kelola Guru</a>
    <a href="{{ route('classrooms.index') }}">Kelola Kelas</a>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>