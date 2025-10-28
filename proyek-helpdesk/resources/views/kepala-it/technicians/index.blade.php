<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Kelola Data Teknisi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-300 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 text-red-700 border border-red-300 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Pastikan class="..." tidak terpotong seperti di contoh Anda --}}
                    <a href="{{ route('kepala.technicians.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Tambah Teknisi Baru
                    </a>

                    </a> {{-- Akhir Tombol Tambah --}}

                <div class="mt-4 mb-4">
                    <form method="GET" action="{{ route('kepala.technicians.index') }}">
                        <div class="flex">
                            <x-text-input type="text" name="search" placeholder="Cari nama atau email..." class="flex-grow rounded-r-none" :value="$searchTerm ?? ''" />
                            <x-primary-button type="submit" class="rounded-l-none">
                                Cari
                            </x-primary-button>
                        </div>
                    </form>
                </div>
                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full ...">
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
                            <thead class="text-left">
                                <tr>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Nama</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Email</th>
                                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">Total Tugas Selesai</th>
                                    <th class="px-4 py-2"></th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-gray-200">
                                @forelse ($technicians as $teknisi)
                                <tr>
                                    <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $teknisi->name }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">{{ $teknisi->email }}</td>
                                    <td class="whitespace-nowrap px-4 py-2 text-gray-700">
                                        {{ $teknisi->tasks_count }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-2 space-x-2">
                                        <a href="{{ route('kepala.technicians.edit', $teknisi) }}" class="inline-block rounded bg-indigo-600 px-4 py-2 text-xs font-medium text-white hover:bg-indigo-700">
                                            Edit
                                        </a>

                                        <form method="POST" action="{{ route('kepala.technicians.destroy', $teknisi) }}" class="inline-block" onsubmit="return confirm('Anda yakin ingin menghapus teknisi ini? Ini tidak bisa dibatalkan.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-block rounded bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                        Belum ada data teknisi.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div> {{-- Akhir dari div overflow-x-auto --}}

                    <div class="mt-4">
                        {{ $technicians->links() }}
                    </div>
                    </div> {{-- Akhir dari div.p-6 --}}
            </div> {{-- Akhir dari div.bg-white --}}
        </div>
    </div>
</x-app-layout>
