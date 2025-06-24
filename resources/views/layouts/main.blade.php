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
    <script>
        const token = localStorage.getItem('token');
        if (!token) {
            window.location.href = '/login';
        }

        fetch('/api/auth/me', {
                method: 'GET',
                headers: {
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error("Unauthorized");
                }
                return res.json();
            })
            .then(data => {
                if (!data.user || data.organizations.length == 0) {
                    window.location.href = '/choices';
                }
            })
            .catch(err => {
                console.error("Page error:", err);
                localStorage.removeItem('token');
                window.location.href = '/login';
            });

        function logout() {
            localStorage.removeItem('token');
            window.location.href = '/login';
        }
    </script>
    {{-- <script src="/js/app.js"></script> --}}
</head>

<body class="flex h-screen">
    <x-header />

    <div class="flex flex-row w-full">
        <div class="transition-all duration-300 transform h-screen w-60 bg-white overflow-hidden" id="menu">
            <x-sidebar />
        </div>

        <div class="flex flex-col w-full transition-all duration-300 overflow-auto pt-20" id="content">
            @yield('content')
            <x-footer />
        </div>
    </div>

    <script>
        const menuButton = document.getElementById('menu-button');
        const menu = document.getElementById('menu');

        menuButton.addEventListener('click', () => {
            if (menu.classList.contains('w-60')) {
                menu.classList.replace('w-60', 'w-0');
            } else {
                menu.classList.replace('w-0', 'w-60');
            }
        });
    </script>
</body>
