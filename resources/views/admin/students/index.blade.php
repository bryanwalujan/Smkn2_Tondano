<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola Siswa</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: blue; margin-bottom: 10px; display: inline-block; }
    </style>
</head>
<body>
    <h2>Kelola Siswa</h2>
    <a href="{{ route('students.create') }}">Tambah Siswa</a>
    <table>
        <thead>
            <tr>
                <th>NIS</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
                <tr>
                    <td>{{ $student->nis }}</td>
                    <td>{{ $student->name }}</td>
                    <td>{{ $student->classroom->name }}</td>
                    <td>{{ $student->user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>