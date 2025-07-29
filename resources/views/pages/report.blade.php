@extends('layouts.main')

@section('script')
<script>
    const token = localStorage.getItem('token');
    if (!token) {
        window.location.href = '/masuk';
    }

    let data = null;
    let orgId = '';

    (async () => {
        try {
            const res = await fetch('/api/data/report', {
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

            data = await res.json();
            console.log(data);

            if (!data.user || data.organizations.length == null) {
                window.location.href = '/choices';
                return;
            }

            orgId = `${data.organizations[0].id}`

            document.getElementById('username').innerText = data.user.name;

            const totalInventoryWorth = data.totalInventoryWorth;
            const totalItems = data.totalItems;
            const mostStockItem = data.mostStockItem.name;
            const mostStockCategory = data.mostStockCategory.name;

            let productsContent = '';
            let criticalContent = '';
            let i = 1

            data.products.forEach(product => {
                productsContent += `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">${i}</td>
                        <td class="px-6 py-4">${product.name}</td>
                        <td class="px-6 py-4">${product.category.name}</td>
                        <td class="px-6 py-4">${product.stock}</td>
                        <td class="px-6 py-4">Rp ${product.stock * product.price}</td>
                    </tr>
                `;
                i += 1;
            });

            data.products.forEach(product => {
                let mark = '';
                let message = '';
                if (product.stock > 30) {
                    return;
                } else if (product.stock > 10 && product.stock < 30) {
                    mark = 'text-red-700 bg-red-100';
                    message = 'Segera Isi Ulang'
                } else {
                    mark = 'text-yellow-700 bg-yellow-100'
                    message = 'Stok Menipis'
                }

                criticalContent += `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">${product.name}</td>
                        <td class="px-6 py-4">${product.stock}</td>
                        <td class="px-6 py-4">${product.category.name}</td>
                        <td class="px-6 py-4"><span class="px-2 py-1 font-semibold leading-tight ${mark} rounded-full">${message}</span></td>
                    </tr>
                `;
            });

            document.getElementById('products').innerHTML = productsContent;
            document.getElementById('criticalStock').innerHTML = criticalContent;
            document.getElementById('totalInventoryWorth').innerText = `Rp ${totalInventoryWorth}`
            document.getElementById('totalItems').innerText = totalItems
            document.getElementById('mostStockItem').innerText = mostStockItem
            document.getElementById('mostStockCategory').innerText = mostStockCategory

            // const categorySelect = document.getElementById('add-product-category');

            // data.categories.forEach(category => {
            //     const option = document.createElement('option');
            //     option.value = category.id;
            //     option.textContent = category.name;
            //     categorySelect.appendChild(option);
            // });

            // attachAddListeners();
            // attachEditListeners();
            // attachDeleteModal();
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
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg p-6 shadow-lg">
        <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-full">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path d="M12 6V18" stroke="#1eb300" stroke-width="1.5" stroke-linecap="round"></path>
                        <path d="M15 9.5C15 8.11929 13.6569 7 12 7C10.3431 7 9 8.11929 9 9.5C9 10.8807 10.3431 12 12 12C13.6569 12 15 13.1193 15 14.5C15 15.8807 13.6569 17 12 17C10.3431 17 9 15.8807 9 14.5" stroke="#1eb300" stroke-width="1.5" stroke-linecap="round"></path>
                        <path d="M7 3.33782C8.47087 2.48697 10.1786 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 10.1786 2.48697 8.47087 3.33782 7" stroke="#1eb300" stroke-width="1.5" stroke-linecap="round"></path>
                    </g>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Total Nilai Inventaris</p>
                <p id="totalInventoryWorth" class="text-2xl font-bold text-gray-800"></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-lg">
        <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-full">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Jumlah Item</p>
                <p id="totalItems" class="text-2xl font-bold text-gray-800"></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-lg">
        <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Kategori Stok Terbanyak</p>
                <p id="mostStockCategory" class="text-lg font-bold text-gray-800"></p>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-lg p-6 shadow-lg">
        <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-full">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-500">Produk Stok Terbanyak</p>
                <p id="mostStockItem" class="text-lg font-bold text-gray-800"></p>
            </div>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Distribusi Stok per Produk</h3>
        <canvas id="stokPerProdukChart"></canvas>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Grafik Stok (Berdasarkan Kategori)</h3>
        <canvas id="trenStokChart"></canvas>
    </div>
</div>

<div class="mt-8 grid grid-cols-1 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Detail Laporan Stok Produk</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Peringkat</th>
                        <th scope="col" class="px-6 py-3">Nama Produk</th>
                        <th scope="col" class="px-6 py-3">Kategori</th>
                        <th scope="col" class="px-6 py-3">Jumlah Stok</th>
                        <th scope="col" class="px-6 py-3">Nilai Stok</th>
                    </tr>
                </thead>
                <tbody id="products">
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Status Stok Kritis</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Produk</th>
                        <th scope="col" class="px-6 py-3">Stok Saat Ini</th>
                        <th scope="col" class="px-6 py-3">Kategori</th>
                        <th scope="col" class="px-6 py-3">Status</th>
                    </tr>
                </thead>
                <tbody id="criticalStock">
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stokProdukData = {
            labels: ['Sekop', 'Pupuk A', 'Pupuk', 'Obat Pembunuh Nyamuk', 'Karburator Mio'],
            datasets: [{
                label: 'Jumlah Stok',
                data: [200, 150, 120, 95, 35],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        };

        const kategori = {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli'],
            datasets: [{
                label: 'Spare Part',
                data: [200, 180, 150, 160, 120, 100, 120],
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
                tension: 0.1
            }, {
                label: 'Obat',
                data: [300, 250, 280, 220, 180, 200, 150],
                fill: false,
                borderColor: 'rgb(255, 99, 132)',
                tension: 0.1
            }]
        };

        const ctxStokProduk = document.getElementById('stokPerProdukChart');
        if (ctxStokProduk) {
            new Chart(ctxStokProduk, {
                type: 'bar',
                data: stokProdukData,
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    responsive: true,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }


        const ctxTrenStok = document.getElementById('trenStokChart');
        if (ctxTrenStok) {
            new Chart(ctxTrenStok, {
                type: 'line',
                data: kategori,
                options: {
                    responsive: true
                }
            });
        }
    });
</script>
@endpush

@endsection
