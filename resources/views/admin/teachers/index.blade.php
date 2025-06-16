<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola Guru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: blue; margin-bottom: 10px; display: inline-block; }
    </style>
</head>
<body>
    <h2>Kelola Guru</h2>
    <a href="{{ route('teachers.create') }}">Tambah Guru</a>
    <table>
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->nip }}</td>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->user->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>