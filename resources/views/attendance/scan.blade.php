<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Scan QR Code Absensi</title>
    <script src="{{ asset('js/html5-qrcode.min.js') }}"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-xl shadow-lg p-6 w-full max-w-md">
        <h1 class="text-2xl font-semibold text-blue-600 mb-4 text-center">Scan QR Code Absensi</h1>
        
        <!-- Camera Selector (hidden by default) -->
        <select id="camera-select" class="hidden w-full p-2 mb-4 border border-gray-300 rounded-lg">
            <option value="">Pilih Kamera</option>
        </select>
        
        <!-- QR Scanner Container -->
        <div id="reader" class="w-full border-2 border-dashed border-gray-300 rounded-lg overflow-hidden bg-gray-50 min-h-[300px] flex items-center justify-center relative">
            <div id="loading-indicator" class="absolute inset-0 flex flex-col items-center justify-center bg-white bg-opacity-90 z-10">
                <div class="w-10 h-10 border-4 border-blue-200 border-t-blue-600 rounded-full animate-spin mb-3"></div>
                <p class="text-gray-700 font-medium">Memulai kamera...</p>
            </div>
        </div>
        
        <!-- Scan Result -->
        <div id="result" class="hidden mt-4 p-4 rounded-lg text-center"></div>
        
        <!-- Status Message -->
        <p id="status-message" class="text-gray-500 text-sm mt-4 text-center">Arahkan kamera ke QR code untuk memindai</p>
    </div>

    <!-- Audio Elements -->
    <audio id="success-sound" src="{{ asset('sounds/success.mp3') }}" preload="auto"></audio>
    <audio id="error-sound" src="{{ asset('sounds/error.mp3') }}" preload="auto"></audio>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const resultDiv = document.getElementById('result');
            const loadingIndicator = document.getElementById('loading-indicator');
            const cameraSelect = document.getElementById('camera-select');
            const statusMessage = document.getElementById('status-message');
            const successSound = document.getElementById('success-sound');
            const errorSound = document.getElementById('error-sound');
            
            let html5QrCode;
            let cameras = [];
            let currentCameraId = null;
            let isProcessing = false;

            // Initialize the scanner
            function initScanner() {
                html5QrCode = new Html5Qrcode('reader');

                const config = {
                    fps: 10,
                    qrbox: { width: 250, height: 250 },
                    formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
                    experimentalFeatures: {
                        useBarCodeDetectorIfSupported: true
                    }
                };

                // Get available cameras
                Html5Qrcode.getCameras().then(availableCameras => {
                    if (availableCameras && availableCameras.length) {
                        cameras = availableCameras;
                        
                        if (cameras.length > 1) {
                            cameraSelect.innerHTML = '<option value="">Pilih Kamera</option>';
                            cameras.forEach(camera => {
                                const option = document.createElement('option');
                                option.value = camera.id;
                                option.text = camera.label || `Kamera ${cameraSelect.length}`;
                                cameraSelect.appendChild(option);
                            });
                            cameraSelect.classList.remove('hidden');
                            currentCameraId = cameras[0].id;
                        } else {
                            currentCameraId = cameras[0].id;
                        }
                        
                        startCamera(currentCameraId, config);
                    } else {
                        showError('Tidak ada kamera yang ditemukan.');
                    }
                }).catch(err => {
                    showError('Gagal mengakses kamera: ' + err.message);
                });
            }

            // Start camera with given ID
            function startCamera(cameraId, config) {
                html5QrCode.start(
                    cameraId,
                    config,
                    onScanSuccess,
                    onScanError
                ).then(() => {
                    loadingIndicator.classList.add('hidden');
                    statusMessage.textContent = 'Arahkan kamera ke QR code untuk memindai';
                }).catch(err => {
                    showError('Gagal memulai kamera: ' + err.message);
                });
            }

            // Handle camera selection change
            cameraSelect.addEventListener('change', (e) => {
                if (e.target.value && html5QrCode) {
                    currentCameraId = e.target.value;
                    loadingIndicator.classList.remove('hidden');
                    html5QrCode.stop().then(() => {
                        const config = {
                            fps: 10,
                            qrbox: { width: 250, height: 250 },
                            formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE]
                        };
                        startCamera(currentCameraId, config);
                    }).catch(err => {
                        console.error('Gagal menghentikan kamera:', err);
                        loadingIndicator.classList.add('hidden');
                    });
                }
            });

            // Handle scan success
            function onScanSuccess(decodedText) {
                if (isProcessing) return;
                
                console.log('QR code berhasil dipindai:', decodedText);
                isProcessing = true;
                statusMessage.textContent = 'Memproses QR code...';
                
                // Show scanning feedback briefly
                resultDiv.textContent = 'Memproses QR code...';
                resultDiv.className = 'mt-4 p-4 rounded-lg text-center bg-blue-100 text-blue-800';
                resultDiv.classList.remove('hidden');
                
                // Process the scan
                processScan(decodedText);
            }

            // Handle scan error
            function onScanError(errorMessage) {
                if (!errorMessage.includes('NotFoundException')) {
                    console.log('Error pemindaian:', errorMessage);
                }
            }

            // Process the scanned data
            function processScan(barcode) {
                fetch('/attendance/scan', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ barcode: barcode })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        let resultText = data.message;
                        if (data.name) resultText += `\nNama: ${data.name}`;
                        if (data.time) resultText += `\nWaktu: ${data.time}`;
                        
                        showResult(resultText, 'success');
                        successSound.play().catch(err => console.warn('Gagal memutar suara sukses:', err));
                    } else {
                        showResult(data.message || 'Terjadi kesalahan', 'error');
                        errorSound.play().catch(err => console.warn('Gagal memutar suara error:', err));
                    }
                })
                .catch(error => {
                    showResult(error.message || 'Terjadi kesalahan sistem', 'error');
                    errorSound.play().catch(err => console.warn('Gagal memutar suara error:', err));
                })
                .finally(() => {
                    // Reset processing flag after a short delay
                    setTimeout(() => {
                        isProcessing = false;
                        statusMessage.textContent = 'Arahkan kamera ke QR code untuk memindai';
                    }, 2000);
                });
            }

            // Show result message
            function showResult(message, type) {
                resultDiv.textContent = message;
                resultDiv.className = `mt-4 p-4 rounded-lg text-center ${
                    type === 'success' ? 'bg-green-100 text-green-800' : 
                    type === 'error' ? 'bg-red-100 text-red-800' : 
                    'bg-blue-100 text-blue-800'
                }`;
                resultDiv.classList.remove('hidden');
            }

            // Show error message
            function showError(message) {
                resultDiv.textContent = message;
                resultDiv.className = 'mt-4 p-4 rounded-lg text-center bg-red-100 text-red-800';
                resultDiv.classList.remove('hidden');
                loadingIndicator.classList.add('hidden');
                statusMessage.textContent = 'Gagal memulai kamera';
                errorSound.play().catch(err => console.warn('Gagal memutar suara error:', err));
            }

            // Start scanner initially
            initScanner();
        });
    </script>
</body>
</html>