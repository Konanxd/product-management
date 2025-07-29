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
            const res = await fetch('/api/data/products', {
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

            let tableContent = '';

            // Recommended forEach loop
            data.products.forEach(product => {
                tableContent += `
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">${product.name}</td>
                        <td class="px-6 py-4"><img src="${product.image ?? 'https://placehold.co/60x60/e2e8f0/334155?text=Produk'}" alt="Gambar Produk" class="h-10 w-10 rounded object-cover"></td>
                        <td class="px-6 py-4">${product.category.name}</td>
                        <td class="px-6 py-4">${product.stock}</td>
                        <td class="px-6 py-4">Rp ${product.price.toLocaleString('id-ID')}</td>

                        <td class="px-6 py-4 text-right"
                            data-id="${product.id}"
                            data-name="${product.name}"
                            data-category-id="${product.category_id}"
                            data-stock="${product.stock}"
                            data-price="${product.price}">
                            <a href="#" class="edit-btn font-medium text-blue-600 hover:underline mr-3">Edit</a>
                            <a href="#" class="delete-btn font-medium text-red-600 hover:underline">Hapus</a>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('products').innerHTML = tableContent;

            const categorySelect = document.getElementById('add-product-category');

            data.categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });

            attachAddListeners();
            attachEditListeners();
            attachDeleteModal();
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

<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex flex-col md:flex-row justify-between md:items-center mb-4">
        <h3 class="text-xl font-semibold text-gray-700 mb-4 md:mb-0">Daftar Inventaris Produk</h3>
        <button class="add-btn px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">+ Tambah Produk</button>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-6 py-3">Nama Produk</th>
                    <th class="px-6 py-3">Gambar</th>
                    <th class="px-6 py-3">Kategori</th>
                    <th class="px-6 py-3">Stok</th>
                    <th class="px-6 py-3">Harga</th>
                    <th class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id='products'>
            </tbody>
        </table>
    </div>
</div>

<div id="add-product-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700">Tambah Produk Baru</h3>
            <button id="close-add-modal" class="text-gray-400 hover:text-gray-800">&times;</button>
        </div>
        <form id="addProductForm" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="add-product-name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input id="add-product-name" name="name" type="text" required class="mt-1 w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="add-product-image" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input id="add-product-image" name="image" type="file" accept="image/*" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div>
                    <label for="add-product-category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="add-product-category" name="category_id" required class="mt-1 w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Pilih Kategori...</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="add-product-stock" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input id="add-product-stock" name="stock" type="number" min="0" required class="mt-1 w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="add-product-price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input id="add-product-price" name="price" type="number" min="0" required class="mt-1 w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<div id="edit-product-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700">Ubah Produk Baru</h3>
            <button id="close-edit-modal" class="text-gray-400 hover:text-gray-800">&times;</button>
        </div>
        <p class="text-red-500 text-sm" id="errorMsg"></p>
        <form id="editProductForm" enctype="multipart/form-data">
            @csrf
            <input type="text" id="edit-product-id" class="hidden">
            <div class="space-y-4">
                <div>
                    <label for="edit-product-name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input id="edit-product-name" name="name" type="text" required class="mt-1 w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="edit-product-image" class="block text-sm font-medium text-gray-700">Gambar</label>
                    <input id="edit-product-image" name="image" type="file" accept="image/*" class="mt-1 w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>

                <div>
                    <label for="edit-product-category" class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select id="edit-product-category" name="category_id" required class="mt-1 w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" disabled selected>Pilih Kategori...</option>
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="edit-product-stock" class="block text-sm font-medium text-gray-700">Stok</label>
                        <input id="edit-product-stock" name="stock" type="number" min="0" required class="mt-1 w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="edit-product-price" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                        <input id="edit-product-price" name="price" type="number" min="0" required class="mt-1 w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition duration-300">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>


<div id="delete-product-modal"
    class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm flex items-center justify-center z-50 transition-opacity duration-300 ease-in-out opacity-0 pointer-events-none">
    <div
        class="relative bg-white w-full max-w-md mx-auto rounded-lg shadow-lg p-6 transform scale-95 transition-transform duration-300 ease-in-out">
        <input type="text" class="hidden" id="delete-product-id">
        <button onclick="toggleModalHapus(null)"
            class="absolute top-2 right-2 text-black hover:text-red-500 text-3xl z-50">
            &times;
        </button>
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-4">Konfirmasi Hapus</h2>
        <p class="text-gray-600 text-center mb-6">Apakah Anda yakin ingin menghapus data ini?</p>

        <div class="flex justify-center gap-4">
            <button id="close-product-modal"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md">
                Tidak
            </button>

            <form id="deleteForm" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md">
                    Ya
                </button>
            </form>
        </div>

    </div>
</div>

</div>

<script>
    function attachAddListeners() {
        const addModal = document.getElementById('add-product-modal');
        const closeBtn = document.getElementById('close-add-modal');

        const addButtons = document.querySelectorAll('.add-btn');

        addButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                addModal.classList.remove('hidden');
            });
        });

        closeBtn.addEventListener('click', () => {
            addModal.classList.add('hidden');
        });
    }

    function attachEditListeners() {
        const editModal = document.getElementById('edit-product-modal');
        const closeBtn = document.getElementById('close-edit-modal');

        const productNameInput = document.getElementById('edit-product-name');
        const productIdInput = document.getElementById('edit-product-id');
        const categorySelect = document.getElementById('edit-product-category');
        const stockInput = document.getElementById('edit-product-stock');
        const priceInput = document.getElementById('edit-product-price');

        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const dataCell = this.parentElement;

                const productId = dataCell.getAttribute('data-id');
                const productName = dataCell.getAttribute('data-name');
                const categoryId = dataCell.getAttribute('data-category-id');
                const stock = dataCell.getAttribute('data-stock');
                const price = dataCell.getAttribute('data-price');

                productNameInput.value = productName;
                productIdInput.value = productId;
                categorySelect.value = categoryId;
                stockInput.value = stock;
                priceInput.value = price;

                const editCategorySelect = document.getElementById('edit-product-category');
                editCategorySelect.innerHTML = '';
                data.categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.name;
                    editCategorySelect.appendChild(option);
                });
                editCategorySelect.value = categoryId;

                editModal.classList.remove('hidden');
            });
        });

        closeBtn.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });
    }

    function attachDeleteModal() {
        const deleteModal = document.getElementById('delete-product-modal');
        const closeBtn = document.getElementById('close-product-modal');
        const productIdInput = document.getElementById('delete-product-id');

        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const dataCell = this.parentElement;

                const productId = dataCell.getAttribute('data-id');

                productIdInput.value = productId;

                deleteModal.classList.remove('hidden');
            });
        });

        closeBtn.addEventListener('click', () => {
            deleteModal.classList.add('hidden');
        });
    }

    document.getElementById('addProductForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const name = document.getElementById('add-product-name').value;
        const categoryId = document.getElementById('add-product-category').value;
        const stock = document.getElementById('add-product-stock').value;
        const price = document.getElementById('add-product-price').value;
        const imageFile = document.getElementById('add-product-image').files[0];

        const response = await fetch('/api/data/products', {
            method: 'POST',
            headers: {
                'Content-type': 'application/json',
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                categoryId: categoryId,
                stock: stock,
                price: price,
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            window.location.href = "/produk";
        } else {
            let errors = data.message || "Tambah produk gagal";
            if (typeof data === 'object' && !data.success) {
                errors = Object.values(data).flat().join('\n');
            }
            document.getElementById('errorMsg').innerText = errors;
        }
    });

    document.getElementById('editProductForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const id = document.getElementById('edit-product-id').value;
        const name = document.getElementById('edit-product-name').value;
        const categoryId = document.getElementById('edit-product-category').value;
        const stock = document.getElementById('edit-product-stock').value;
        const price = document.getElementById('edit-product-price').value;
        // const imageFile = document.getElementById('edit-product-image').files[0];

        const response = await fetch('/api/data/products', {
            method: 'PUT',
            headers: {
                'Content-type': 'application/json',
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id: id,
                name: name,
                categoryId: categoryId,
                stock: parseInt(stock),
                price: parseInt(price),
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            window.location.href = "/produk";
        } else {
            let errors = data.message || "Edit produk gagal";
            if (typeof data === 'object' && !data.success) {
                errors = Object.values(data).flat().join('\n');
            }
            document.getElementById('errorMsg').innerText = errors;
        }
    })

    document.getElementById('deleteForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const id = document.getElementById('delete-product-id').value;

        const response = await fetch('/api/data/products', {
            method: 'DELETE',
            headers: {
                'Content-type': 'application/json',
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id: id,
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            window.location.href = "/produk";
        } else {
            let errors = data.message || "Hapus produk gagal";
            if (typeof data === 'object' && !data.success) {
                errors = Object.values(data).flat().join('\n');
            }
            document.getElementById('errorMsg').innerText = errors;
        }
    })
</script>

@endsection
