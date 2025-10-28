<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Semua Notifikasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- Tombol Tandai Semua Dibaca (jika ada yg belum dibaca) --}}
                    @if(Auth::user()->unreadNotifications->isNotEmpty())
                        <div class="mb-4 text-right">
                            <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                @csrf
                                <x-secondary-button type="submit">
                                    Tandai Semua Dibaca
                                </x-secondary-button>
                            </form>
                        </div>
                    @endif

                    {{-- Daftar Notifikasi --}}
                    <div class="space-y-4">
                        @forelse ($notifications as $notification)
                            <a href="{{ route('notifications.read', $notification->id) }}"
                               class="block p-4 rounded-md transition duration-150 ease-in-out {{ $notification->read_at ? 'bg-gray-50 dark:bg-gray-700' : 'bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 shadow-sm hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 me-3 {{ $notification->read_at ? 'text-gray-400' : 'text-indigo-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                    <div class="flex-grow">
                                        <p class="text-sm font-medium {{ $notification->read_at ? 'text-gray-500' : 'text-gray-900 dark:text-gray-100' }}">
                                            {{ $notification->data['message'] ?? 'Notifikasi' }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <p class="text-center text-gray-500">Tidak ada notifikasi.</p>
                        @endforelse
                    </div>

                    {{-- Link Pagination --}}
                    <div class="mt-6">
                        {{ $notifications->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
