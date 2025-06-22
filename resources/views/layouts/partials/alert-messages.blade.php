{{-- File: resources/views/layouts/partials/alert-messages.blade.php --}}

{{-- Cek apakah ada pesan 'success' di session --}}
@if (session('success'))
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p class="font-bold">Sukses!</p>
        <p>{{ session('success') }}</p>
    </div>
@endif

{{-- Cek apakah ada pesan 'error' di session --}}
@if (session('error'))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">Error!</p>
        <p>{{ session('error') }}</p>
    </div>
@endif

{{-- Cek apakah ada error validasi secara umum --}}
@if ($errors->any())
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
        <p class="font-bold">Terjadi Kesalahan Validasi</p>
        <ul>
            @foreach ($errors->all() as $error)
                <li>- {{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
