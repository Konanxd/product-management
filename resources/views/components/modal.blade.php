<!-- Tailwind Modal -->
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
    <div class="flex items-center justify-center">
        <div class="bg-white rounded-lg w-full max-w-md shadow-lg">
            <div class="flex justify-between items-center p-4 border-b">
                <h5 class="text-lg font-semibold">Tambah Kategori</h5>
                <button type="button" class="text-gray-500 hover:text-gray-700" onclick="closeModal()">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <form id="addCategoryForm" action="/categories" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="categoryName" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
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

<script>
    function openModal() {
        document.getElementById('modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('modal').classList.add('hidden');
    }
</script>
