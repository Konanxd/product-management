<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - {{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        const token = localStorage.getItem('token');
        if (token) {
            fetch('/api/auth/me', {
                    headers: {
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error("Invalid token");
                    return res.json();
                })
                .then(user => {
                    console.log(user);
                    window.location.href = '/dashboard';
                })
                .catch(err => {
                    console.warn("Token invalid:", err);
                    localStorage.removeItem('token');
                });
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
                <div class="flex items-center gap-3 mb-8">
                    <div class="bg-white rounded-full p-2">
                        logo
                    </div>
                    <span class="text-xl font-bold tracking-wider">InventoHub</span>
                </div>

                <h1 class="text-4xl md:text-5xl font-bold mb-4 leading-tight">Buat Akun Adna</h1>
                <p class="text-blue-100">
                    Bergabung bersama kami untuk? untuk apa?
                </p>
            </div>
        </div>

        <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Buat Akun Baru</h2>

            <form id='registerForm'>
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" placeholder="Nama" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-600 mb-1">Alamat Email</label>
                    <input type="email" id="email" name="email" placeholder="nama@mail.com" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-600 mb-1">Password</label>
                    <input type="password" id="password" name="password" placeholder="••••••••" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-600 mb-1">Konfirmasi Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                </div>

                <div class="mb-4">
                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-300">
                        Daftar
                    </button>
                </div>
                <div class="text-center text-sm">
                    <p class="text-gray-600">
                        Sudah punya akun?
                        <a href="{{ url('/masuk') }}" class="font-medium text-blue-600 hover:text-blue-500">Masuk di sini</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const response = await fetch('/api/auth/register', {
                method: 'POST',
                headers: {
                    'Content-type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: document.getElementById('name').value,
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                    password_confirmation: document.getElementById('password_confirmation')
                        .value,
                })
            });

            const data = await response.json();

            if (response.ok && data.success && data.token) {
                localStorage.setItem('token', data.token);
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
