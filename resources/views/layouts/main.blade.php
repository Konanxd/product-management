<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Dashboard RoyalStore</title>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    @vite('resources/css/app.css')
    @yield('script')
</head>

<body class="bg-gray-50">
    <x-sidebar />

    <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>

    <div class="sm:ml-64">
        <x-header />

        <main class="p-4">
            @yield('content')
        </main>
    </div>
</body>
