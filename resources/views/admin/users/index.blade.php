@extends('layouts.admin')

@section('content')
<div class="container mx-auto p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-2xl font-semibold">Manajemen Pengguna</h2>
        <!-- Button to open modal -->
        <button onclick="document.getElementById('addUserModal').classList.remove('hidden')"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Tambah Pengguna</button>
    </div>

    <!-- Modal -->
    <div id="addUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white w-full max-w-md p-6 rounded shadow-lg relative">
            <h3 class="text-xl font-semibold mb-4">Tambah Pengguna</h3>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium">Nama</label>
                    <input type="text" name="name" required class="w-full border px-3 py-2 rounded mt-1">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Email</label>
                    <input type="email" name="email" required class="w-full border px-3 py-2 rounded mt-1">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Password</label>
                    <input type="password" name="password" required class="w-full border px-3 py-2 rounded mt-1">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium">Role</label>
                    <select name="role" class="w-full border px-3 py-2 rounded mt-1">
                        <option value="customer">Customer</option>
                        <option value="admin">Admin</option>
                        <option value="courier">Courier</option>
                    </select>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="document.getElementById('addUserModal').classList.add('hidden')"
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit -->
<div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 items-center justify-center">
    <div class="bg-white max-w-md w-full p-6 rounded shadow relative">
        <h3 class="text-xl font-semibold mb-4">Edit Pengguna</h3>
        <form id="editUserForm" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="user_id" id="editUserId">
            <div class="mb-4">
                <label class="block text-sm">Nama</label>
                <input type="text" name="name" id="editName" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm">Email</label>
                <input type="email" name="email" id="editEmail" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm">Role</label>
                <select name="role" id="editRole" class="w-full border px-3 py-2 rounded mt-1">
                    <option value="admin">Admin</option>
                    <option value="customer">Customer</option>
                    <option value="courier">Courier</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm">Status</label>
                <select name="is_active" id="editIsActive" class="w-full border px-3 py-2 rounded mt-1">
                    <option value="1">Aktif</option>
                    <option value="0">Nonaktif</option>
                </select>
            </div>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-300 rounded">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>


    <!-- User Table -->
    <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow mt-4">
        <thead>
            <tr>
                <th class="py-2 px-4 text-left">Nama</th>
                <th class="py-2 px-4 text-left">Email</th>
                <th class="py-2 px-4 text-left">Status</th>
                <th class="py-2 px-4 text-left">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td class="py-2 px-4">{{ $user->name }}</td>
                <td class="py-2 px-4">{{ $user->email }}</td>
                <td class="py-2 px-4">
                    <span class="{{ $user->is_active ? 'text-green-600' : 'text-red-600' }}">
                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                </td>

                <td class="py-2 px-4 space-x-2">
                    <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-500">Lihat</a>
                    <button onclick="openEditModal({{ $user->id }})"
        class="text-green-600 hover:underline text-sm">Edit</button>
                    <form action="{{ route('admin.users.updateStatus', $user->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" name="status" value="{{ $user->is_active ? 0 : 1 }}"
                            class="text-yellow-600 hover:underline text-sm">
                            {{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

<script>
    const users = @json($users);

    function openEditModal(userId) {
        const user = users.find(u => u.id === userId);
        if (!user) return;

        document.getElementById('editUserId').value = user.id;
        document.getElementById('editName').value = user.name;
        document.getElementById('editEmail').value = user.email;
        document.getElementById('editRole').value = user.role;
        document.getElementById('editIsActive').value = user.is_active ? 1 : 0;

        const form = document.getElementById('editUserForm');
        form.action = `/admin/users/${user.id}`; // Pastikan route ini cocok
        document.getElementById('editUserModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }
</script>
