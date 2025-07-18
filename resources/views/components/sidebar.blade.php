<!-- <div class="flex h-screen overflow-y-auto w-60 px-4 pt-20">
    <nav class="flex flex-col w-full space-y-9" id="sidenavAccordion">
        <div class="flex flex-col space-y-6">
            <span class="text-xs uppercase font-semibold">Core</span>
            <a class="flex flex-row gap-2 text-blue-500" href="/dashboard">
                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                Dashboard
            </a>
        </div>
        <div class="flex flex-col space-y-6">
            <span class="text-xs uppercase font-semibold">Interface</span>
            <a href="#" id="collapseTrigger" class="flex flex-row gap-2 text-blue-500">
                <div class="flex gap-2 items-center">
                    <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                    Pages
                </div>
                <div id="collapseArrow" class="transition-all duration-300 -rotate-90"><i class="fas fa-angle-down"></i>
                </div>
            </a>

            <div id="collapseLayouts"
                class="flex flex-col gap-5 max-h-0 transition-[max-height] duration-300 ease-in-out overflow-hidden text-blue-500 px-6">
                <a class="nav-link" href="/categories">Category</a>
                <a class="nav-link" href="/products">Product</a>
            </div>

        </div>
    </nav>
</div> -->

<aside id="defsidebar" class="fixed left-0 top-0 z-40 w-64 h-screen transition-transform -translate-x-full sm:translate-x-0 shadow-2xl">
    <div class="h-full px-3 py-4 overflow-y-auto bg-blue-800 flex flex-col items-center shadow-2xs">
        <h1 class="font-bold text-2xl text-center text-white mb-3">
            <span>InventoHub</span>
        </h1>
        <div class="w-full px-4 border-t border-gray-200">
            <div class="flex flex-col items-center w-full mt-3">
                <a href="{{ url('/dashboard') }}" class="flex items-center w-full h-9 px-3 py-6 mt-2 rounded-lg text-gray-200 hover:bg-blue-900 hover:text-white">

                    <span class="ml-2 text-base font-medium">dashboard</span>
                </a>
                <a href="{{ url('/manajemeninventaris') }}" class="flex items-center w-full h-9 px-3 py-6 mt-2 rounded-lg text-gray-200 hover:bg-blue-900 hover:text-white">

                    <span class="ml-2 text-base font-medium">Manajemen inventaris</span>
                </a>
                <a href="{{ url('/laporan') }}" class="flex items-center w-full h-9 px-3 py-6 mt-2 rounded-lg text-gray-200 hover:bg-blue-900 hover:text-white">

                    <span class="ml-2 text-base font-medium">Laporan</span>
                </a>
                <a href="{{ url('/profile') }}" class="flex items-center w-full h-9 px-3 py-6 mt-2 rounded-lg text-gray-200 hover:bg-blue-900 hover:text-white">

                    <span class="ml-2 text-base font-medium">Profile</span>
                </a>
            </div>
        </div>
        <div class="mt-auto w-full px-2">
            <button class="flex items-center justify-center w-full h-12 px-3 rounded-lg bg-red-600 text-white hover:bg-red-700"
                onclick="#">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M15 12L2 12M2 12L5.5 9M2 12L5.5 15" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M9.00195 7C9.01406 4.82497 9.11051 3.64706 9.87889 2.87868C10.7576 2 12.1718 2 15.0002 2L16.0002 2C18.8286 2 20.2429 2 21.1215 2.87868C22.0002 3.75736 22.0002 5.17157 22.0002 8L22.0002 16C22.0002 18.8284 22.0002 20.2426 21.1215 21.1213C20.3531 21.8897 19.1752 21.9862 17 21.9983M9.00195 17C9.01406 19.175 9.11051 20.3529 9.87889 21.1213C10.5202 21.7626 11.4467 21.9359 13 21.9827" stroke="#ffffff" stroke-width="1.5" stroke-linecap="round"></path>
                    </g>
                </svg>
                <span class="ml-2 text-lg font-medium">Logout</span>
            </button>
        </div>
    </div>
</aside>

<div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden"></div>

<div class="sm:ml-64">
    <header class="bg-fdfdfd shadow p-4 flex justify-between items-center">
        <div class="flex items-center">
            <button data-drawer-toggle="defsidebar" aria-controls="defsidebar" type="button" class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-200  mr-3">
                <span class="sr-only">Open sidebar</span>
                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 9.75A.75.75 0 012.75 9h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 9.75z"></path>
                </svg>
            </button>
            <h2 class="text-xl font-semibold text-gray-800">Dashboard</h2>
        </div>
        <div class="flex items-center">
            <span class="text-gray-600 mr-4">Selamat datang, Admin!</span>
        </div>
    </header>

    <main class="p-4">
        {{ $slot }}
    </main>
</div>
<script>
</script>
@stack('scripts')



<script>
    const trigger = document.getElementById('collapseTrigger');
    const content = document.getElementById('collapseLayouts');
    const arrow = document.getElementById('collapseArrow');

    let isOpen = false;

    trigger.addEventListener('click', (e) => {
        e.preventDefault();
        isOpen = !isOpen

        if (isOpen) {
            arrow.classList.remove('-rotate-90');
            content.style.maxHeight = content.scrollHeight + "px";
        } else {
            arrow.classList.add('-rotate-90');
            content.style.maxHeight = "0px";
        }
    });
</script>
