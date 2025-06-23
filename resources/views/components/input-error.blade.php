@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['class' => 'text-sm text-red-600 dark:text-red-400 space-y-1']) }}>

        {{-- BAGIAN PINTARNYA DI SINI --}}
        {{-- Kita cek, jika $messages itu array, kita loop satu per satu --}}
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach

    </ul>
@endif
