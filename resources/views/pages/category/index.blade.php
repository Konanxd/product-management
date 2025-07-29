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
            const res = await fetch('/api/data/categories', {
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

            if (!data.user || data.organizations.length == null) {
                window.location.href = '/choices';
                return;
            }

            orgId = `${data.organizations[0].id}`

            document.getElementById('username').innerText = data.user.name;

            let tableContent = '';

            data.categories.forEach(category => {
                tableContent += `
                    <tr class="border-b hover:bg-gray-50">
                        <td id="categoryName" class="px-6 py-4 font-medium text-gray-900">${category.name}</td>
                        <td id='totalProduct' class="px-6 py-4">${category.total_stock ?? 0}</td>
                        <td class="create-org-btn px-6 py-4 text-right">
                        <a href="#"
                        class="edit-btn font-medium text-blue-600 hover:underline"
                        data-id="${category.id}"}
                        data-name="${category.name}"}
                        >Edit</a></td>
                    </tr>
                `;
            });

            document.getElementById('categoryProducts').innerHTML = tableContent;

            attachEditListeners();
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
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
    <div class="md:col-span-2 bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Daftar Kategori Global</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">Nama Kategori</th>
                        <th scope="col" class="px-6 py-3">Jumlah Produk</th>
                        <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody id='categoryProducts'>
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h3 class="text-xl font-semibold text-gray-700 mb-4">Tambah Kategori Baru</h3>
        <form id="createCategoryForm">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input id="name" type="text" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">Simpan Kategori</button>
            </div>
        </form>
    </div>
</div>

<div id="edit-category-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 modal-backdrop">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700">Edit Kategori</h3>
            <button id="close-edit-modal" class="text-gray-400 hover:text-gray-800">&times;</button>
        </div>
        <form id="editCategoryForm">
            @csrf
            <input type="text" id="edit-category-id" class="hidden">
            <div>
                <label for="edit-category-name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input id="edit-category-name" type="text" class="w-full bg-gray-50 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mt-6">
                <button type="submit" class="w-full bg-green-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-green-700 transition duration-300">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    function attachEditListeners() {
        const editModal = document.getElementById('edit-category-modal');
        const closeBtn = document.getElementById('close-edit-modal');
        const editForm = document.getElementById('editCategoryForm');
        const categoryNameInput = document.getElementById('edit-category-name');
        const categoryIdInput = document.getElementById('edit-category-id');

        const editButtons = document.querySelectorAll('.edit-btn');

        editButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const categoryId = this.getAttribute('data-id');
                const categoryName = this.getAttribute('data-name');

                categoryNameInput.value = categoryName;
                categoryIdInput.value = categoryId;

                editModal.classList.remove('hidden');
            });
        });

        closeBtn.addEventListener('click', () => {
            editModal.classList.add('hidden');
        });
    }

    document.getElementById('editCategoryForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const response = await fetch('/api/data/categories', {
            method: 'PUT',
            headers: {
                'Content-type': 'application/json',
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                id: document.getElementById('edit-category-id').value,
                name: document.getElementById('edit-category-name').value,
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            window.location.href = "/kategori";
        } else {
            let errors = data.message || "Ubah kategori gagal";
            if (typeof data === 'object' && !data.success) {
                errors = Object.values(data).flat().join('\n');
            }
            document.getElementById('errorMsg').innerText = errors;
        }
    });

    document.getElementById('createCategoryForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const response = await fetch('/api/data/categories', {
            method: 'POST',
            headers: {
                'Content-type': 'application/json',
                'Authorization': 'Bearer ' + token,
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: document.getElementById('name').value,
                orgId: orgId
            })
        });

        const data = await response.json();

        if (response.ok && data.success) {
            window.location.href = "/kategori";
        } else {
            let errors = data.message || "Tambah kategori gagal";
            if (typeof data === 'object' && !data.success) {
                errors = Object.values(data).flat().join('\n');
            }
            document.getElementById('errorMsg').innerText = errors;
        }
    })
</script>

@endsection
