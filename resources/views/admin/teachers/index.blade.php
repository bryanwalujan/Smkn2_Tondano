<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola Guru</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 50px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        a { color: blue; margin-right: 10px; }
        .delete-form { display: inline; }
        .delete-button { padding: 5px; background-color: #f44336; color: white; border: none; cursor: pointer; }
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
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($teachers as $teacher)
                <tr>
                    <td>{{ $teacher->nip }}</td>
                    <td>{{ $teacher->name }}</td>
                    <td>{{ $teacher->user->email }}</td>
                    <td>
                        <a href="{{ route('teachers.edit', $teacher) }}">Edit</a>
                        <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button" onclick="return confirm('Yakin ingin menghapus guru ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
</body>
</html>