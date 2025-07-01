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

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200 antialiased">
    <div class="min-h-screen flex items-center justify-center">
        <div
            class="flex flex-col md:flex-row w-full max-w-4xl bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden m-4">

            <!-- Left Panel (Branding) -->
            <div
                class="w-full md:w-1/2 bg-gray-800 p-12 text-white flex flex-col justify-center items-center text-center">
                <div class="max-w-xs">
                    <h1 class="text-4xl font-bold mb-4">Join Our Community!</h1>
                    <p class="text-gray-300 mb-8">
                        Create an account to get started. It's free and only takes a minute.
                    </p>
                    <div id="errorMsg" class="text-red-400"></div>
                    <svg class="w-48 h-48 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Right Panel (Registration Form) -->
            <div class="w-full md:w-1/2 p-8 md:p-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">Create Account</h2>

                <form id='registerForm'>
                    @csrf

                    <!-- Name -->
                    <div class="mb-5">
                        <label for="name"
                            class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Full Name</label>
                        <input id="name" type="text" name="name" placeholder="John Doe"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                            value="{{ old('name') }}" required autofocus autocomplete="name" />
                        <p class="mt-2 text-sm text-red-600"></p>

                    </div>

                    <!-- Email Address -->
                    <div class="mb-5">
                        <label for="email"
                            class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Email
                            Address</label>
                        <input id="email" type="email" name="email" placeholder="you@example.com"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                            value="{{ old('email') }}" required autocomplete="username" />
                        <p class="mt-2 text-sm text-red-600"></p>

                    </div>

                    <!-- Password -->
                    <div class="mb-5">
                        <label for="password"
                            class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Password</label>
                        <input id="password" type="password" name="password" placeholder="Create a strong password"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                            required autocomplete="new-password" />
                        {{--
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        --}}
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-6">
                        <label for="password_confirmation"
                            class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Confirm
                            Password</label>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                            placeholder="Confirm your password"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                            required autocomplete="new-password" />
                    </div>

                    <!-- Register Button -->
                    <div>
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition duration-300 transform hover:scale-105">
                            Register
                        </button>
                    </div>
                </form>

                <!-- Login Link -->
                <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                    Already have an account?
                    <a href="{{-- route('login') --}}"
                        class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                        Log in
                    </a>
                </p>
            </div>
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

            if (response.ok && data.success && data.access_token) {
                localStorage.setItem('token', data.access_token);
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
