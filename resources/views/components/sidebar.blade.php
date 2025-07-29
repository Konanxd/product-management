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
                <a href="{{ url('/kategori') }}" class="flex items-center w-full h-9 px-3 py-6 mt-2 rounded-lg text-gray-200 hover:bg-blue-900 hover:text-white">

                    <span class="ml-2 text-base font-medium">Kategori</span>
                </a>
                <a href="{{ url('/produk') }}" class="flex items-center w-full h-9 px-3 py-6 mt-2 rounded-lg text-gray-200 hover:bg-blue-900 hover:text-white">

                    <span class="ml-2 text-base font-medium">Produk</span>
                </a>
                <a href="{{ url('/laporan') }}" class="flex items-center w-full h-9 px-3 py-6 mt-2 rounded-lg text-gray-200 hover:bg-blue-900 hover:text-white">

                    <span class="ml-2 text-base font-medium">Laporan</span>
                </a>
                <a href="{{ url('/anggota') }}" class="flex items-center w-full h-9 px-3 py-6 mt-2 rounded-lg text-gray-200 hover:bg-blue-900 hover:text-white">

                    <span class="ml-2 text-base font-medium">Anggota</span>
                </a>
            </div>
        </div>
        <div class="mt-auto w-full px-2">
            <button class="flex items-center justify-center w-full h-12 px-3 rounded-lg bg-red-600 text-white hover:bg-red-700">
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
