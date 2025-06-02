@extends('layouts.main')

@section('content')
    <div class="flex flex-col justify-center w-full px-4">
        <h1 class="text-4xl font-semibold">Category</h1>

        <div class="w-full flex justify-center">
            <div class="w-full my-4 mx-20 p-4">
                <h2 class="text-center text-3xl font-semibold mb-4">Manajemen Kategori</h2>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4"
                        id="successMessage">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tambah Kategori Button -->
                <div class="flex justify-between items-center mb-4">
                    <button id="addButton" onclick="openModal(this.id)"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition" data-bs-toggle="modal"
                        data-bs-target="#categoryModal">
                        <i class="fas fa-plus mr-2"></i> Tambah Kategori
                    </button>
                </div>

                <!-- Table -->
                <div class="overflow-y-auto max-h-[400px] border rounded shadow-sm">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-2 text-left">ID</th>
                                <th class="px-4 py-2 text-left">Nama Kategori</th>
                                <th class="px-4 py-2 text-left">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="categoryTableBody" class="divide-y divide-gray-200">
                            @foreach ($categories as $category)
                                <tr id="categoryRow{{ $category->id }}" class="hover:bg-gray-100">
                                    <td class="px-4 py-2">{{ $category->id }}</td>
                                    <td class="px-4 py-2">{{ $category->name }}</td>
                                    <td class="px-4 py-2 space-x-2">
                                        <!-- Edit Button -->
                                        <button id="editButton" onclick="openModal(this.id)"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition editCategoryBtn"
                                            data-id="{{ $category->id }}" data-name="{{ $category->name }}"
                                            data-bs-toggle="modal" data-bs-target="#editCategoryModal">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 transition">
                                                <i class="fas fa-trash mr-1"></i> Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Kategori -->
        <div id="modalAdd" class="fixed inset-0 bg-black/50 z-50 hidden">
            <div class="flex mt-4 items-center justify-center">
                <div class="bg-white opacity-100 rounded-lg w-full max-w-md shadow-lg">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h5 class="text-lg font-semibold">Tambah Kategori</h5>
                        <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeModal(this.id)"
                            id="addButton">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <form id="addCategoryForm" action="/categories" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                    Kategori</label>
                                <input type="text" name="name" id="categoryName"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-500"
                                    required>
                            </div>
                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded flex justify-center items-center gap-2">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit Kategori -->
        <div id="modalEdit" class="fixed inset-0 bg-black/50 z-50 hidden">
            <div class="flex mt-4 items-center justify-center">
                <div class="bg-white opacity-100 rounded-lg w-full max-w-md shadow-lg">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h5 class="text-lg font-semibold">Edit Kategori</h5>
                        <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeModal(this.id)"
                            id="editButton">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <form id="editCategoryForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label for="categoryEdit" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                    Kategori</label>
                                <input type="text" name="name" id="editCategoryName"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-500"
                                    required>
                            </div>
                            <button type="submit"
                                class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded flex justify-center items-center gap-2">
                                <i class="fas fa-save"></i> Simpan Perubahan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        function openModal(id) {
            if (id == "addButton") {
                document.getElementById('modalAdd').classList.remove('hidden');
            } else if (id == "editButton") {
                document.getElementById('modalEdit').classList.remove('hidden');
            }
        }

        function closeModal(id) {
            if (id == "addButton") {
                document.getElementById('modalAdd').classList.add('hidden');
            } else if (id == "editButton") {
                document.getElementById('modalEdit').classList.add('hidden');
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".editCategoryBtn").forEach(button => {
                button.addEventListener("click", function() {
                    let categoryId = this.getAttribute("data-id");
                    let categoryName = this.getAttribute("data-name");

                    document.getElementById("editCategoryName").value = categoryName;
                    document.getElementById("editCategoryForm").setAttribute("action",
                        "/categories/" + categoryId);
                });
            });
        });
    </script>
@endsection
