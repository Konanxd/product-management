@extends('layouts.main')

@section('content')
    <div class="flex flex-col justify-center w-full px-4">
        <h1 class="text-4xl font-semibold">Products</h1>

        <div class="w-full flex justify-center">
            <div class="w-full my-4 mx-20 p-4">
                <h2 class="text-center text-3xl font-semibold mb-4">Product's List</h2>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4"
                        id="successMessage">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="flex justify-between mb-3">
                    <input id="searchInput"
                        class="w-1/4 p-2 border-2 border-slate-400 rounded-md focus:outline-none focus:shadow-sky-300 focus:shadow-2xl transition duration-300"
                        placeholder="Cari produk..." />
                    <button id="addButton" onclick="openModal(this.id)"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition" data-bs-toggle="modal"
                        data-bs-target="#productModal">Add</button>
                </div>

                <!-- Table -->
                <div class="overflow-y-auto max-h-[400px] border rounded shadow-sm">
                    <table class="min-w-full table-auto">
                        <thead class="bg-gray-800 text-white">
                            <tr>
                                <th class="px-4 py-2 text-left">No</th>
                                <th class="px-4 py-2 text-left">Image</th>
                                <th class="px-4 py-2 text-left">Name</th>
                                <th class="px-4 py-2 text-left">Description</th>
                                <th class="px-4 py-2 text-left">Stock</th>
                                <th class="px-4 py-2 text-left">Price</th>
                                <th class="px-4 py-2 text-left">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productTableBody" class="divide-y divide-gray-200">
                            @foreach ($products as $product)
                                <tr id="productRow{{ $product->id }}" class="hover:bg-gray-100">
                                    <td class="px-4 py-2">{{ $product->name }}</td>
                                    <td class="px-4 py-2">{{ $product->description }}</td>
                                    <td class="px-4 py-2">{{ $product->stock }}</td>
                                    <td class="px-4 py-2">Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                    <td class="px-4 py-2 space-x-2">
                                        <!-- Edit Button -->
                                        <button id="editButton" onclick="openModal(this.id)"
                                            class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition editProductBtn"
                                            data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                            data-bs-toggle="modal" data-bs-target="#editproductModal">
                                            <i class="fas fa-edit mr-1"></i> Edit
                                        </button>

                                        <!-- Delete Form -->
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST"
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

        <!-- Modal Tambah Produk -->
        <div id="modalAdd" class="fixed inset-0 bg-black/50 z-50 hidden">
            <div class="flex mt-4 items-center justify-center">
                <div class="bg-white opacity-100 rounded-lg w-full max-w-md shadow-lg">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h5 class="text-lg font-semibold">Tambah Produk</h5>
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
                        <form id="addProductForm" action="/products" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="productName" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                    Produk</label>
                                <input type="text" name="name" id="productName"
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

        <!-- Modal Edit Produk -->
        <div id="modalEdit" class="fixed inset-0 bg-black/50 z-50 hidden">
            <div class="flex mt-4 items-center justify-center">
                <div class="bg-white opacity-100 rounded-lg w-full max-w-md shadow-lg">
                    <div class="flex justify-between items-center p-4 border-b">
                        <h5 class="text-lg font-semibold">Edit Produk</h5>
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
                        <form id="editProductForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-4">
                                <label for="productEdit" class="block text-sm font-medium text-gray-700 mb-1">Nama
                                    Produk</label>
                                <input type="text" name="name" id="editProductName"
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
            document.querySelectorAll(".editProductBtn").forEach(button => {
                button.addEventListener("click", function() {
                    let productId = this.getAttribute("data-id");
                    let productName = this.getAttribute("data-name");

                    document.getElementById("editProductName").value = productName;
                    document.getElementById("editProductForm").setAttribute("action",
                        "/products/" + productId);
                });
            });
        });
    </script>
@endsection
