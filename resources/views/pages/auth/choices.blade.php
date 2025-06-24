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
                });
        }

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
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
        <div class="flex flex-col md:flex-row w-full max-w-5xl bg-white dark:bg-gray-800 shadow-2xl rounded-2xl overflow-hidden m-4">

            <!-- Left Panel (Branding) -->
            <div class="w-full md:w-1/2 bg-gray-800 p-12 text-white flex flex-col justify-center items-center text-center">
                <div class="max-w-md">
                    <h1 class="text-4xl font-bold mb-4">One Last Step!</h1>
                    <p class="text-gray-300 mb-8">
                        To manage your products, you need to be part of an organization. You can either create a new one or join an existing team.
                    </p>
                    <svg class="w-48 h-48 mx-auto text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.124-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.124-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>

            <!-- Right Panel (Choices) -->
            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">Choose Your Path</h2>

                <div class="space-y-6">
                    <!-- Option 1: Create Organization -->
                    <a href="/create_organization" class="block p-8 bg-gray-50 dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-gray-600 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Create an Organization</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Start a new team and invite your collaborators.</p>
                            </div>
                        </div>
                    </a>

                    <!-- Option 2: Join Organization -->
                    <a href="/create_organization" class="block p-8 bg-gray-50 dark:bg-gray-700 hover:bg-green-50 dark:hover:bg-gray-600 rounded-xl shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-full">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Join an Organization</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Use an invite code or link from an existing team.</p>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Logout Link -->
                <div class="mt-8 text-center">
                    <form method="POST" action="{{-- route('logout') --}}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-600 dark:text-gray-400 hover:underline">
                            Or, log out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
