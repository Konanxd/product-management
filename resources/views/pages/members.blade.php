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
            const res = await fetch('/api/data/members', {
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

            data.members.forEach(member => {
                const date = new Date(member.pivot.created_at);
                const readable = date.toLocaleString('id-ID', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false,
                    timeZoneName: 'short'
                });

                tableContent +=
                    `
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-900">${member.name}</td>
                    <td class="px-6 py-4">${member.email}</td>
                    <td class="px-6 py-4">${member.pivot.role}</td>
                    <td class="px-6 py-4">${readable}</td>
                    <td class="px-6 py-4 text-right"><a href="#" class="font-medium text-blue-600 hover:underline">Edit</a></td>
                </tr>
                `
            });

            document.getElementById('members').innerHTML = tableContent;

            // attachAddListeners();
            // attachEditListeners();
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
        <div class="flex-1">
            <input type="text" placeholder="Cari anggota..." class="w-full md:w-80 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex items-center mt-4 md:mt-0">
            <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">+ Tambah Anggota</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3">Nama Pengguna</th>
                    <th scope="col" class="px-6 py-3">Email</th>
                    <th scope="col" class="px-6 py-3">Peran</th>
                    <th scope="col" class="px-6 py-3">Tanggal Bergabung</th>
                    <th scope="col" class="px-6 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="members">
            </tbody>
        </table>
    </div>
    <div class="flex justify-between items-center mt-4">
        <span class="text-sm text-gray-700">Menampilkan 1 sampai 4 dari 5,783 hasil</span>
        <div class="inline-flex mt-2 xs:mt-0">
            <button class="px-4 py-2 text-sm font-medium text-white bg-gray-800 rounded-l hover:bg-gray-900">Prev</button>
            <button class="px-4 py-2 text-sm font-medium text-white bg-gray-800 border-0 border-l border-gray-700 rounded-r hover:bg-gray-900">Next</button>
        </div>
    </div>
</div>


@endsection
