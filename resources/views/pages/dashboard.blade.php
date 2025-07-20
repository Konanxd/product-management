@extends('layouts.main')

@section('script')
<script>
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/masuk';
    }

    (async () => {
        try {
            const res = await fetch('/api/data/dashboard', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token,
                    'Accept': 'application/json'
                }
            });

            if (!res.ok) {
                throw new Error("Unauthorized");
            }

            const data = await res.json();

            if (!data.user || data.organizations.length == null) {
                window.location.href = '/choices';
                return;
            }

            console.log(data.totalProduct);

            document.getElementById('username').innerText = data.user.name;
            document.getElementById('totalProduct').innerText = data.totalProduct;
            document.getElementById('lowStockProducts').innerText = data.lowStockProducts;
            document.getElementById('emptyStockProducts').innerText = data.emptyStockProducts;

        } catch (err) {
            console.error("Page error:", err);
            // localStorage.removeItem('token');
            // window.location.href = '/masuk';
        }
    })();


    function logout() {
        localStorage.removeItem('token');
        window.location.href = '/masuk';
    }
</script>
@endsection

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
    <div class="bg-white rounded-lg p-6 shadow-lg">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full"><svg class="w-6 h-6 text-green-600" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4">
                    </path>
                </svg></div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Produk</p>
                <p id='totalProduct' class="text-3xl font-bold text-gray-800"></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-lg">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full"><svg class="w-6 h-6 text-yellow-600" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg></div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Stok Menipis</p>
                <p id='lowStockProducts' class="text-3xl font-bold text-gray-800"></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-lg">
        <div class="flex items-center">
            <div class="p-3 bg-red-100 rounded-full">
                <svg class="w-8 h-8" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M18.7071 4.69719C19.0976 4.30667 19.7308 4.30667 20.1213 4.69719L28.6066 13.1825C28.9971 13.573 28.9971 14.2062 28.6066 14.5967C28.216 14.9872 27.5829 14.9872 27.1924 14.5967L18.7071 6.1114C18.3166 5.72088 18.3166 5.08771 18.7071 4.69719Z"
                            fill="#e42525"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M28.7071 4.7068C29.0976 5.09733 29.0976 5.73049 28.7071 6.12102L20.2218 14.6063C19.8313 14.9968 19.1981 14.9968 18.8076 14.6063C18.4171 14.2158 18.4171 13.5826 18.8076 13.1921L27.2929 4.7068C27.6834 4.31628 28.3166 4.31628 28.7071 4.7068Z"
                            fill="#e42525"></path>
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M24.3162 15.0513C24.111 14.9829 23.8891 14.9829 23.6838 15.0513L8.86851 19.9889C8.64603 20.063 8.463 20.2102 8.34247 20.3985L4.39805 25.4613C4.1985 25.7175 4.13573 26.0545 4.2297 26.3653C4.32367 26.6761 4.56269 26.922 4.87072 27.0246L8.19325 28.1319L8.19595 36.7634C8.19636 38.0544 9.02257 39.2003 10.2473 39.6085L23.6291 44.0691C23.7475 44.1164 23.8738 44.1406 24.0009 44.1405C24.1293 44.141 24.2569 44.1168 24.3765 44.069L37.7577 39.6086C38.9827 39.2003 39.8089 38.054 39.809 36.7628L39.8096 28.1328L43.1346 27.0246C43.4427 26.922 43.6817 26.6761 43.7757 26.3653C43.8696 26.0545 43.8069 25.7175 43.6073 25.4613L39.6117 20.3327C39.4927 20.176 39.3274 20.0542 39.1315 19.9889L24.3162 15.0513ZM9.54341 22.1112L22.346 26.378L19.6478 29.8413L6.8452 25.5745L9.54341 22.1112ZM24.0025 24.8203L35.6526 20.9376L24 17.0541L12.35 20.9367L24.0025 24.8203ZM10.196 36.7628L10.1935 28.7986L19.686 31.9622C20.088 32.0962 20.5307 31.9623 20.7911 31.6281L23.0003 28.7924L23.0001 41.7513L10.8797 37.7112C10.4715 37.5751 10.1961 37.1931 10.196 36.7628ZM37.8095 28.7993L28.3193 31.9622C27.9174 32.0962 27.4747 31.9623 27.2143 31.6281L25.0013 28.7876L25.0049 41.7514L37.1252 37.7113C37.5336 37.5752 37.809 37.1931 37.809 36.7627L37.8095 28.7993ZM28.3576 29.8413L25.6583 26.3767L38.4609 22.1099L41.1602 25.5745L28.3576 29.8413Z"
                            fill="#e42525"></path>
                    </g>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Stok Habis</p>
                <p id='emptyStockProducts' class="text-3xl font-bold text-gray-800"></p>
            </div>
        </div>
    </div>

</div>
<div class="mt-8 bg-white rounded-lg shadow-lg">
    <div class="p-6">
        <h3 class="text-xl font-semibold text-gray-700">Produk Baru Ditambahkan</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-900 uppercase bg-gray-200 border-b-4">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Produk</th>
                    <th scope="col" class="px-6 py-3">Kategori</th>
                    <th scope="col" class="px-6 py-3">Stok Awal</th>
                    <th scope="col" class="px-6 py-3">Tanggal Ditambahkan</th>
                </tr>
            </thead>
            <tbody>
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">Pupuk Organik Cair Super</td>
                    <td class="px-6 py-4">Pupuk</td>
                    <td class="px-6 py-4">150</td>
                    <td class="px-6 py-4">12 Juli 2025</td>
                </tr>
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">Pupuk Organik Cair Super</td>
                    <td class="px-6 py-4">Pupuk</td>
                    <td class="px-6 py-4">150</td>
                    <td class="px-6 py-4">12 Juli 2025</td>
                </tr>
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">Pupuk Organik Cair Super</td>
                    <td class="px-6 py-4">Pupuk</td>
                    <td class="px-6 py-4">150</td>
                    <td class="px-6 py-4">12 Juli 2025</td>
                </tr>
                <tr class="bg-white border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">Pupuk Organik Cair Super</td>
                    <td class="px-6 py-4">Pupuk</td>
                    <td class="px-6 py-4">150</td>
                    <td class="px-6 py-4">12 Juli 2025</td>
                </tr>


            </tbody>
        </table>
    </div>
</div>
@endsection
