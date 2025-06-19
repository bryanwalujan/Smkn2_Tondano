<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Kelas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 min-h-screen font-sans">
    @include('layouts.navbar-admin')
    
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Edit Data Kelas</h1>
                <p class="text-gray-600">Perbarui informasi kelas {{ $classroom->full_name }}</p>
            </div>
            <a href="{{ route('classrooms.index') }}" 
               class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition">
                <i class="fas fa-arrow-left"></i>
                <span>Kembali ke Daftar</span>
            </a>
        </div>

        <!-- Form Section -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-2xl mx-auto">
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 text-red-700">
                    <h3 class="font-bold mb-2">Terdapat kesalahan:</h3>
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('classrooms.update', $classroom) }}" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Tingkat Kelas</label>
                    <select name="level" required
                            class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="10" {{ $classroom->level == '10' ? 'selected' : '' }}>Kelas 10</option>
                        <option value="11" {{ $classroom->level == '11' ? 'selected' : '' }}>Kelas 11</option>
                        <option value="12" {{ $classroom->level == '12' ? 'selected' : '' }}>Kelas 12</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Jurusan</label>
                    <input type="text" name="major" required 
                           value="{{ old('major', $classroom->major) }}"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Kode Kelas</label>
                    <input type="text" name="class_code" required 
                           value="{{ old('class_code', $classroom->class_code) }}"
                           class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>