<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-lg">
                {{-- Card Header --}}
                <div class="px-6 py-4 bg-white border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between space-y-2 md:space-y-0">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Manajemen Pengguna') }}
                    </h2>
                    <a href="{{ route('admin.users.create') }}"
                       class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg transition-colors text-center">
                        + Tambah Pengguna
                    </a>
                </div>
                <div class="p-6 text-gray-900">
                    @include('layouts.partials.alert-messages')

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nama</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">
                                        Role</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($users as $user)
                                    <tr>
                                        {{-- FIX: Removed whitespace-nowrap to allow wrapping on mobile --}}
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <img class="h-10 w-10 rounded-full object-cover"
                                                         src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                                                         alt="{{ $user->name }}">
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}
                                                    </div>
                                                    {{-- FIX: Hide email on mobile --}}
                                                    <div class="text-sm text-gray-500 hidden sm:block">{{ $user->email }}</div>
                                                    
                                                    <div class="sm:hidden mt-1">
                                                        <span
                                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                        @if ($user->role == 'admin') bg-red-100 text-red-800 @endif
                                                        @if ($user->role == 'atasan') bg-blue-100 text-blue-800 @endif
                                                        @if ($user->role == 'kasir') bg-green-100 text-green-800 @endif
                                                        ">
                                                            {{ ucfirst($user->role) }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if ($user->role == 'admin') bg-red-100 text-red-800 @endif
                                                @if ($user->role == 'atasan') bg-blue-100 text-blue-800 @endif
                                                @if ($user->role == 'kasir') bg-green-100 text-green-800 @endif
                                                ">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                  class="inline-block ml-4"
                                                  onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada
                                            pengguna lain.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
