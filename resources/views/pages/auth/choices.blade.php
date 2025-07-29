<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Join or Create an Organization - {{ config('app.name', 'Laravel') }}</title>
    @vite('resources/css/app.css')

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script>
        const token = localStorage.getItem('token');
        if (token) {
            fetch('/api/auth/me', {
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error("Invalid token");
                    return res.json();
                })
                .then(data => {
                    if (data.organizations.length != 0) {
                        window.location.href = '/dashboard';
                    }
                })
                .catch(err => {
                    console.warn("Token invalid:", err);
                    localStorage.removeItem('token');
                    window.location.href = '/masuk';
                });
        } else {
            window.location.href = '/masuk';
        }

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/masuk';
        }
    </script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="relative flex flex-col md:flex-row w-full max-w-4xl min-h-[600px] bg-white rounded-xl shadow-2xl overflow-hidden m-4">

        <div class="bg-blue-600 w-full md:w-1/2 text-white p-8 md:p-12 flex-col justify-center hidden md:flex overflow-hidden relative">
            <div class="absolute w-[300px] h-[300px] bg-white/10 rounded-full -top-[50px] -left-[100px]"></div>
            <div class="absolute w-[400px] h-[400px] bg-white/10 rounded-[45%] -bottom-[150px] -right-[100px] rotate-[30deg] "></div>
            <div class="relative z-10">
                <div class="flex justify-center mb-6">
                    <div class="bg-white/20 p-4 rounded-full">
                        <svg class="w-24 h-24" fill="#ffffff" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve" stroke="#ffffff">
                            <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                            <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                            <g id="SVGRepo_iconCarrier">
                                <g>
                                    <circle cx="26" cy="26" r="11.2"></circle>
                                    <path d="M26,2C12.8,2,2,12.8,2,26c0,13.2,10.8,24,24,24c13.2,0,24-10.8,24-24C50,12.8,39.2,2,26,2z M26,43.6 c-9.7,0-17.6-7.9-17.6-17.6S16.3,8.4,26,8.4S43.6,16.3,43.6,26S35.7,43.6,26,43.6z"></path>
                                </g>
                            </g>
                        </svg>
                    </div>
                </div>

                <h1 class="text-3xl font-bold text-white mb-3">Satu Langkah Terakhir!</h1>
                <p class="text-blue-100 max-w-sm">
                    Untuk mengelola inventaris, Anda harus menjadi bagian dari sebuah organisasi atau anda dapat membuat yang baru atau bergabung dengan tim yang sudah ada.
                </p>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-8">Pilih</h2>

            <div class="space-y-4">
                <button id="create-org-btn" class="w-full flex items-start p-5 bg-gray-50 rounded-lg hover:bg-gray-100 border border-gray-200 hover:border-blue-500 transition-all duration-300 cursor-pointer text-left">
                    <div class="flex-shrink-0 mr-4 mt-1">
                        <div class="bg-blue-100 text-blue-600 rounded-lg p-2">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800">Buat Organisasi</h3>
                        <p class="text-sm text-gray-500">Mulai tim baru dan undang anggota anda.</p>
                    </div>
                </button>

                <button id="join-org-btn" class="w-full flex items-start p-5 bg-gray-50 rounded-lg hover:bg-gray-100 border border-gray-200 hover:border-blue-500 transition-all duration-300 cursor-pointer text-left">
                    <div class="flex-shrink-0 mr-4 mt-1">
                        <div class="bg-green-100 text-green-600 rounded-lg p-2">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-800">Gabung Organisasi</h3>
                        <p class="text-sm text-gray-500">Gunakan kode undangan tim yang sudah ada.</p>
                    </div>
                </button>
            </div>

            <div class="mt-8 text-center">
                <a href="#" class="text-sm text-blue-600 hover:text-blue-500 font-medium transition-colors">Atau, keluar</a>
            </div>
        </div>

        <div id="create-org-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative text-gray-800">
                <button id="close-create-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Buat Organisasi Baru</h2>
                <p class="text-gray-500 mb-6">Jelaskan tujuan tim Anda untuk mendapatkan ide nama yang bagus.</p>
                <p id="errorMsg" class="text-red-500 text-sm mb-2"></p>

                <form id='createOrgForm'>
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label for="org-name" class="block text-sm font-medium text-gray-700 mb-1">Nama Organisasi Anda</label>
                            <input type="text" name="name" id="name" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Isi nama pilihan Anda di sini">
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Organisasi</label>
                            <textarea id="description" name="description" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Contoh: Perusahaan yang bergerrak di mana gitu..."></textarea>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type='submit' class="w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition duration-300">Selesaikan dan Buat</button>
                    </div>
                </form>
            </div>
        </div>


        <div id="join-org-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-8 relative text-gray-800">
                <button id="close-join-modal" class="absolute top-4 right-4 text-gray-400 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Gabung dengan Organisasi</h2>
                <div>
                    <label for="invite-code" class="block text-sm font-medium text-gray-700 mb-1">Kode Undangan</label>
                    <input type="text" id="invite-code" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan kode undangan Anda">
                </div>
                <div class="mt-6">
                    <button class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Gabung</button>
                </div>
            </div>
        </div>

    </div>
    <script>
        function setupModal(openBtnId, closeBtnId, modalId) {
            const openBtn = document.getElementById(openBtnId);
            const closeBtn = document.getElementById(closeBtnId);
            const modal = document.getElementById(modalId);

            openBtn.addEventListener('click', () => modal.classList.remove('hidden'));
            closeBtn.addEventListener('click', () => modal.classList.add('hidden'));
        }
        setupModal('create-org-btn', 'close-create-modal', 'create-org-modal');
        setupModal('join-org-btn', 'close-join-modal', 'join-org-modal');

        document.getElementById('createOrgForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const response = await fetch('/api/auth/create_organization', {
                method: 'POST',
                headers: {
                    'Content-type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: document.getElementById('name').value,
                    description: document.getElementById('description').value
                })
            });

            const data = await response.json();

            if (response.ok && data.success) {
                window.location.href = "/dashboard";
            } else {
                let errors = data.message || "Registrasi gagal";
                if (typeof data === 'object' && !data.success) {
                    errors = Object.values(data).flat().join('\n');
                }
                document.getElementById('errorMsg').innerText = errors;
            }
        })
    </script>
</body>

</html>
