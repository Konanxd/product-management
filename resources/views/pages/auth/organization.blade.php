<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Organization - {{ config('app.name', 'Laravel') }}</title>
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
                    <h1 class="text-4xl font-bold mb-4">Let's Build Something Great</h1>
                    <p class="text-gray-300 mb-8">
                        Create a new organization to manage your projects and invite your team members.
                    </p>
                    <svg class="w-48 h-48 mx-auto text-blue-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                </div>
            </div>

            <!-- Right Panel (Create Form) -->
            <div class="w-full md:w-1/2 p-8 md:p-12 flex flex-col justify-center">
                <h2 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center">Create Your Organization
                </h2>

                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4"
                    role="alert">
                    <strong class="font-bold">Oops!</strong>
                    <div id="errorMsg"></div>
                </div>

                <form id='orgForm'>
                    @csrf

                    <!-- Organization Name -->
                    <div class="mb-6">
                        <label for="organization_name"
                            class="block mb-2 text-sm font-medium text-gray-600 dark:text-gray-400">Organization
                            Name</label>
                        <input id="organization_name" type="text" name="name" placeholder="e.g. Acme Corporation"
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                            required autofocus />
                    </div>

                    <!-- Create Button -->
                    <div>
                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition duration-300 transform hover:scale-105">
                            Create Organization
                        </button>
                    </div>
                </form>

                <!-- Back Link -->
                <p class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{-- route('organization.choice') --}}"
                        class="font-medium text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300">
                        &larr; Back to choices
                    </a>
                </p>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('orgForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const response = await fetch('/api/auth/create_organization', {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Content-type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    name: document.getElementById('organization_name').value,
                })
            });

            const data = await response.json();

            if (response.ok && data.success && data.token) {
                localStorage.setItem('token', data.token);
                window.location.href = "/dashboard";
            } else {
                let message = data.message || "Terjadi kesalahan";
                if (data.message) {
                    message = Object.values(data).flat().join('\n');
                }
                document.getElementById('errorMsg').innerText = message;
            }
        })
    </script>
</body>

</html>
