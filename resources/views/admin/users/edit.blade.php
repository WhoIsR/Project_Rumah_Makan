<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Edit Pengguna: ') . $user->name }}</h2></x-slot>
    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                     @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <strong class="font-bold">Oops!</strong> <span class="block sm:inline">Ada beberapa kesalahan input.</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="flex flex-col items-center mb-6">
                            <label class="block font-medium text-sm text-gray-700 mb-2">Foto Profil</label>
                            <img id="image-preview" src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}" alt="preview" class="w-24 h-24 rounded-full object-cover mb-4 shadow-md">
                            <input id="profile_photo" type="file" name="profile_photo" accept="image/*" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"/>
                            <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block font-medium text-sm text-gray-700">Nama Lengkap</label>
                                <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>
                            <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div>
                                <label for="role" class="block font-medium text-sm text-gray-700">Role / Jabatan</label>
                                <select name="role" id="role" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir</option>
                                    <option value="atasan" {{ old('role', $user->role) == 'atasan' ? 'selected' : '' }}>Atasan</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 border-t pt-6">
                            <p class="text-sm text-gray-600">Kosongkan password jika tidak ingin mengubahnya.</p>
                            <div class="mt-4">
                                <label for="password" class="block font-medium text-sm text-gray-700">Password Baru</label>
                                <input id="password" type="password" name="password" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="mt-4">
                                <label for="password_confirmation" class="block font-medium text-sm text-gray-700">Konfirmasi Password Baru</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-8 border-t pt-6">
                            <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">{{ __('Batal') }}</a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Script ini sama, untuk live preview gambar
        document.getElementById('profile_photo').addEventListener('change', function(event) {
            const [file] = event.target.files;
            if (file) {
                document.getElementById('image-preview').src = URL.createObjectURL(file);
            }
        });
    </script>
</x-app-layout>
