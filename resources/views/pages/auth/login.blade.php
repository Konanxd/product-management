<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - {{ config('app.name', 'Laravel') }}</title>
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
                        'Authorization': 'Bearer ' + token,
                        'Accept': 'application/json'
                    }
                })
                .then(res => {
                    if (!res.ok) throw new Error("Invalid token");
                    return res.json();
                })
                .then(data => {
                    if (data.organizations.length == 0) {
                        window.location.href = '/choices';
                    }
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
        <div class="flex flex-col md:flex-row w-full max-w-4xl bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden m-4">

            <!-- Left Panel (Branding) -->
            <div class="w-full md:w-1/2 bg-gray-800 p-12 text-white flex flex-col justify-center items-center text-center">
                <div class="max-w-xs">
                    <h1 class="text-4xl font-bold mb-4">Welcome Back!</h1>
                    <p class="text-gray-300 mb-8">
                        Sign in to continue to your account. We're glad to see you again.
                    </p>
                    <svg class="w-48 h-48 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
            </div>

            <!-- Right Panel (Login Form) -->
            <div class="w-full md:w-1/2 p-8 md:p-12">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">Account Login</h2>

                {{-- Session Status --}}
                {{--
                    @if (session('status'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('status') }}</span>
            </div>
            @endif
            --}}

            <form id='loginForm'>
                @csrf

                <!-- Email Address -->
                <div class="mb-5">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Email Address</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        placeholder="you@example.com"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="username" />
                    {{--
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    --}}
                </div>

                <!-- Password -->
                <div class="mb-5">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        placeholder="Enter your password"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                        required
                        autocomplete="current-password" />
                    {{--
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    --}}
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">Remember me</label>
                    </div>

                    {{-- @if (Route::has('password.request')) --}}
                    <a href="{{-- route('password.request') --}}" class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                        Forgot password?
                    </a>
                    {{-- @endif --}}
                </div>

                <!-- Login Button -->
                <div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition duration-300 transform hover:scale-105">
                        Log In
                    </button>
                </div>
            </form>

            <!-- Sign Up Link -->
            <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                Don't have an account?
                <a href="{{-- route('register') --}}" class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                    Sign up
                </a>
            </p>
        </div>
    </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const response = await fetch('/api/auth/login', {
                method: 'POST',
                headers: {
                    'Content-type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: document.getElementById('email').value,
                    password: document.getElementById('password').value,
                })
            });

            const data = await response.json();

            if (response.ok && data.success && data.access_token) {
                if (data.organizations.length == 0) {
                    window.location.href = '/choices';
                } else {
                    window.location.href = "/dashboard";
                }
                localStorage.setItem('token', data.access_token);
            } else {
                let errors = data.message || "Login gagal";
                if (data.errors) {
                    message = Object.values(data).flat().join('\n');
                }
                document.getElementById('errorMsg').innerText = message;
            }
        })
    </script>
</body>

</html>
