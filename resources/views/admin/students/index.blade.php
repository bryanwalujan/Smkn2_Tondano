<!DOCTYPE html>
<html>
<head>
    <title>Students List</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="{{ asset('js/html2canvas.min.js') }}"></script>
    <style>
        /* Ganti semua warna dengan format hex */
        body {
            background-color: #f3f4f6 !important;
        }
        .bg-white {
            background-color: #ffffff !important;
        }
        .bg-gray-50 {
            background-color: #f9fafb !important;
        }
        .text-gray-800 {
            color: #1f2937 !important;
        }
        .text-blue-600 {
            color: #2563eb !important;
        }
        .hover\:text-blue-900:hover {
            color: #1e40af !important;
        }
        .text-red-600 {
            color: #dc2626 !important;
        }
        .hover\:text-red-900:hover {
            color: #7f1d1d !important;
        }
        .bg-blue-500 {
            background-color: #3b82f6 !important;
        }
        .hover\:bg-blue-600:hover {
            background-color: #2563eb !important;
        }
        .bg-green-100 {
            background-color: #d1fae5 !important;
        }
        .border-green-500 {
            border-color: #10b981 !important;
        }
        .text-green-700 {
            color: #047857 !important;
        }

        /* Style custom lainnya tetap sama */
        .qr-container {
            position: relative;
            display: inline-block;
        }
        .qr-download-btn {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            text-align: center;
            padding: 2px 0;
            font-size: 12px;
            cursor: pointer;
            opacity: 0;
            transition: opacity 0.3s;
            border-bottom-left-radius: 0.25rem;
            border-bottom-right-radius: 0.25rem;
        }
        .qr-container:hover .qr-download-btn {
            opacity: 1;
        }
        .qr-preview {
            width: 100px;
            height: 100px;
            image-rendering: crisp-edges;
        }
        .qr-download-template {
            position: absolute;
            left: -9999px;
            width: 600px;
            padding: 20px;
            background: #ffffff;
            text-align: center;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
        }
        .qr-download-image {
            width: 500px;
            height: 500px;
            margin: 0 auto;
            display: block;
            image-rendering: crisp-edges;
        }
        .qr-download-name {
            font-size: 28px;
            font-weight: bold;
            margin-top: 20px;
            color: #2d3748;
            padding: 0 20px;
        }
        .qr-download-footer {
            font-size: 16px;
            color: #718096;
            margin-top: 15px;
        }
    </style>
</head>
<body class="bg-gray-100">
    @include('layouts.navbar-admin')
    <div class="container mx-auto p-4">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Daftar Siswa</h1>
            <a href="{{ route('students.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded shadow transition duration-200">Tambah Siswa</a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-6">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIS</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">QR Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach ($students as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $student->nis }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $student->name }}</td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $student->classroom ? $student->classroom->level . ' ' . $student->classroom->major . ' ' . $student->classroom->class_code : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if (file_exists(public_path('qrcodes/student_'.$student->barcode.'.svg')))
                                        <div class="qr-container" id="qr-container-{{ $student->id }}">
                                            <img src="{{ asset('qrcodes/student_'.$student->barcode.'.svg') }}" alt="QR Code" class="qr-preview rounded border border-gray-200">
                                            <div class="qr-download-btn" onclick="downloadQRCode({{ $student->id }}, '{{ $student->name }}')">Download HQ</div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-400">QR Code tidak ditemukan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('students.edit', $student->id) }}" class="text-blue-600 hover:text-blue-900">Edit</a>
                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Template untuk download QR Code -->
    <div id="qr-download-template" class="qr-download-template">
        <img id="qr-download-image" class="qr-download-image" src="">
        <div id="qr-download-name" class="qr-download-name"></div>
        <div class="qr-download-footer">Scan QR Code untuk verifikasi</div>
    </div>

    <script>
        async function downloadQRCode(studentId, studentName) {
            // Dapatkan elemen template
            const template = document.getElementById('qr-download-template');
            const qrImage = document.getElementById('qr-download-image');
            const qrName = document.getElementById('qr-download-name');
            
            // Set konten
            qrImage.src = document.querySelector(`#qr-container-${studentId} img`).src;
            qrName.textContent = studentName;
            
            // Tampilkan template sementara
            template.style.left = '0';
            template.style.top = '0';
            template.style.position = 'fixed';
            template.style.zIndex = '10000';
            
            // Konfigurasi html2canvas untuk kualitas tinggi
            const options = {
                scale: 3,
                logging: true,
                useCORS: true,
                allowTaint: true,
                backgroundColor: '#ffffff',
                windowWidth: 640,
                windowHeight: 640,
                onclone: (clonedDoc) => {
                    // Hapus semua class Tailwind yang mungkin menggunakan warna modern
                    const elements = clonedDoc.querySelectorAll('[class]');
                    elements.forEach(el => {
                        el.classList.forEach(className => {
                            if (className.startsWith('bg-') || 
                                className.startsWith('text-') || 
                                className.startsWith('border-') || 
                                className.startsWith('hover:')) {
                                el.classList.remove(className);
                            }
                        });
                    });
                    
                    // Terapkan style inline sederhana
                    const template = clonedDoc.getElementById('qr-download-template');
                    template.style.backgroundColor = '#ffffff';
                    template.style.color = '#2d3748';
                    
                    const nameEl = clonedDoc.getElementById('qr-download-name');
                    nameEl.style.color = '#2d3748';
                    nameEl.style.fontSize = '28px';
                    nameEl.style.fontWeight = 'bold';
                    
                    const footerEl = clonedDoc.querySelector('.qr-download-footer');
                    footerEl.style.color = '#718096';
                }
            };
            
            try {
                // Tunggu untuk memastikan gambar terload
                await new Promise(resolve => {
                    if (qrImage.complete) {
                        resolve();
                    } else {
                        qrImage.onload = resolve;
                        qrImage.onerror = resolve;
                        setTimeout(resolve, 500);
                    }
                });
                
                // Konversi ke canvas
                const canvas = await html2canvas(template, options);
                
                // Kembalikan posisi template
                template.style.left = '-9999px';
                template.style.position = 'absolute';
                template.style.zIndex = '';
                
                // Download gambar
                const link = document.createElement('a');
                link.download = `QR_${studentName.replace(/[^a-zA-Z0-9]/g, '_')}.png`;
                link.href = canvas.toDataURL('image/png');
                link.click();
                
            } catch (error) {
                console.error('Error generating QR Code:', error);
                alert('Gagal menghasilkan QR Code. Silakan coba lagi.');
                template.style.left = '-9999px';
                template.style.position = 'absolute';
                template.style.zIndex = '';
            }
        }
    </script>
</body>
</html>