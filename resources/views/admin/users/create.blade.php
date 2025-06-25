<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8"> {{-- Lebarkan max-w menjadi 4xl --}}
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                {{-- Card Header --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200 justify-between flex items-center">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Tambah Pengguna Baru') }}
                    </h2>
                </div>
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops!</strong>
                            <span class="block sm:inline">Ada beberapa kesalahan input.</span>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                        @csrf

                        {{-- BAGIAN UTAMA: GRID 2 KOLOM --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6 pt-4">

                            {{-- KOLOM KIRI: FOTO PROFIL (Opsional untuk tambah baru) --}}
                            <div class="flex flex-col items-center justify-center">
                                <label for="profile_photo" class="block font-medium text-sm text-gray-700 mb-2">Foto Profil (Opsional)</label>
                                {{-- Gambar default untuk preview --}}
                                <img id="image-preview" src="https://ui-avatars.com/api/?name=?&color=7F9CF5&background=EBF4FF" alt="preview" class="w-28 h-28 rounded-full object-cover mb-4 shadow-md">
                                <input id="profile_photo" type="file" name="profile_photo" accept="image/*"
                                    class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"/>
                                <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                            </div>

                            {{-- KOLOM KANAN: DATA PENGGUNA LAINNYA (Nama, Email, Role, Password) --}}
                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                                    <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                </div>
                                <div>
                                    <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                        class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                                <div class="pb-4">
                                    <label for="role" class="block font-medium text-sm text-gray-700">Role / Jabatan</label>
                                    <select name="role" id="role"
                                        class="p-2 mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="kasir" @if(old('role') == 'kasir') selected @endif>Kasir</option>
                                        <option value="atasan" @if(old('role') == 'atasan') selected @endif>Atasan</option>
                                        <option value="admin" @if(old('role') == 'admin') selected @endif>Admin</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('role')" class="mt-2" />
                                </div>
                                {{-- Bagian Password (dalam kolom kanan) --}}
                                <div class="mt-6 border-t pt-6"> {{-- Tambahkan mt-6 border-t pt-6 untuk pemisah visual --}}
                                    <div>
                                        <label for="password" class="block font-medium text-sm text-gray-700">Password</label>
                                        <input id="password" type="password" name="password" required
                                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                    </div>
                                    <div class="mt-4">
                                        <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Konfirmasi Password</label>
                                        <input id="password_confirmation" type="password" name="password_confirmation" required
                                            class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                    </div>
                                </div>
                            </div>
                        </div> {{-- END BAGIAN UTAMA GRID --}}

                        {{-- Tombol Aksi (di luar grid, agar membentang penuh) --}}
                        <div class="flex items-center justify-end mt-8 border-t pt-6">
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Batal') }}</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script untuk live preview gambar profil
        document.getElementById('profile_photo').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                const preview = document.getElementById('image-preview');
                preview.src = URL.createObjectURL(file);
                preview.onload = () => URL.revokeObjectURL(preview.src); // Melepas memori
            } else {
                // Jika file dihapus, kembalikan ke avatar default (untuk form create)
                document.getElementById('image-preview').src = "https://ui-avatars.com/api/?name=?&color=7F9CF5&background=EBF4FF";
            }
        });
    </script>
</x-app-layout>