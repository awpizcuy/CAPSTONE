@php
    // Logika pemilihan kelas dipindahkan ke sini
    $classes = '';
    switch ($status) {
        case 'pending':
            $classes = 'bg-yellow-100 text-yellow-800';
            break;
        case 'accepted':
            $classes = 'bg-blue-100 text-blue-800';
            break;
        case 'on_process':
            $classes = 'bg-orange-100 text-orange-800';
            break;
        case 'completed':
            $classes = 'bg-green-100 text-green-800';
            break;
        case 'rated':
            $classes = 'bg-purple-100 text-purple-800'; // Kelas ungu ada di sini
            break;
        case 'rejected':
        case 'hold':
            $classes = 'bg-gray-100 text-gray-800';
            break;
        default:
            $classes = 'bg-gray-100 text-gray-800';
    }
@endphp

<span class="inline-flex items-center justify-center rounded-full px-2.5 py-0.5 {{ $classes }}"> {{-- Terapkan kelas di sini --}}
    <p class="whitespace-nowrap text-sm">{{ $text }}</p>
</span>
