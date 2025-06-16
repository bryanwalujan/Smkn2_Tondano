<!DOCTYPE html>
<html lang="id">
<head>
    <title>Kelola Kelas</title>
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
    <h2>Kelola Kelas</h2>
    <a href="{{ route('classrooms.create') }}">Tambah Kelas</a>
    <table>
        <thead>
            <tr>
                <th>Kelas</th>
                <th>Tingkat</th>
                <th>Jurusan</th>
                <th>Kode Kelas</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($classrooms as $classroom)
                <tr>
                    <td><a href="{{ route('classrooms.show', $classroom) }}">{{ $classroom->full_name }}</a></td>
                    <td>{{ $classroom->level }}</td>
                    <td>{{ $classroom->major }}</td>
                    <td>{{ $classroom->class_code }}</td>
                    <td>
                        <a href="{{ route('classrooms.edit', $classroom) }}">Edit</a>
                        <form action="{{ route('classrooms.destroy', $classroom) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="delete-button" onclick="return confirm('Yakin ingin menghapus kelas ini?')">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.dashboard') }}">Kembali ke Dashboard</a>
</body>
</html>