<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(auth()->user()->role == 'kepala_it')
                        <x-nav-link :href="route('kepala.technicians.index')" :active="request()->routeIs('kepala.technicians.*')">
                            {{ __('Kelola Teknisi') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">

                {{-- Dropdown Notifikasi --}}
                <x-dropdown align="right" width="60">
                    {{-- Pemicu: Ikon Lonceng + Badge --}}
                    <x-slot name="trigger">
                        <button class="relative inline-flex items-center p-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            {{-- Ikon Lonceng SVG --}}
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                            {{-- Badge Angka Notifikasi --}}
                            @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                                <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                    {{ $unreadNotifications->count() }}
                                </span>
                            @endif
                        </button>
                    </x-slot>

                    {{-- Konten Dropdown Notifikasi --}}
                    <x-slot name="content">
                        <div class="border-b border-gray-200 dark:border-gray-600 max-h-60 overflow-y-auto">
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                Notifikasi Belum Dibaca
                            </div>
                            @if(isset($unreadNotifications) && $unreadNotifications->isNotEmpty())
                                @foreach($unreadNotifications->take(5) as $notification)
                                    <a href="{{ route('notifications.read', $notification->id) }}"
                                       class="flex items-center w-full px-4 py-3 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                                        <svg class="w-4 h-4 me-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341A6.002 6.002 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                        <div class="flex-grow">
                                            <p class="truncate">{{ $notification->data['message'] ?? 'Notifikasi baru' }}</p>
                                            <span class="block text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </a>
                                @endforeach
                                 @if($unreadNotifications->count() > 5)
                                    <a href="{{ route('notifications.index') }}"
                                        class="block w-full px-4 py-2 text-center ...">
                                        Lihat Semua ({{ $unreadNotifications->count() }})
                                    </a>
                                @endif
                            @else
                                <p class="px-4 py-2 text-sm text-gray-500">Tidak ada notifikasi baru.</p>
                            @endif

                             {{-- Form Tombol Tandai Semua Dibaca --}}
                            @if(isset($unreadNotifications) && $unreadNotifications->isNotEmpty())
                                <div class="border-t border-gray-200 dark:border-gray-600">
                                    <form method="POST" action="{{ route('notifications.markAllRead') }}">
                                        @csrf
                                        <button type="submit" class="block w-full px-4 py-2 text-center text-xs leading-5 text-gray-500 dark:text-gray-400 hover:bg-primary dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out">
                                            Tandai Semua Dibaca
                                        </button>
                                    </form>
                                </div>
                            @endif
                            {{-- BATAS PERUBAHAN --}}

                        </div>
                    </x-slot>
                </x-dropdown>
                {{-- Akhir Dropdown Notifikasi --}}


                {{-- Dropdown Profil Pengguna --}}
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        {{-- Pemicu: Nama Pengguna + Panah --}}
                        <x-slot name="trigger">
                            <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        {{-- Konten Dropdown Profil --}}
                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
                {{-- Akhir Dropdown Profil Pengguna --}}

            </div>
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(auth()->user()->role == 'kepala_it')
                <x-responsive-nav-link :href="route('kepala.technicians.index')" :active="request()->routeIs('kepala.technicians.*')">
                    {{ __('Kelola Teknisi') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                 {{-- [BARU] Tambah Notifikasi di Responsive Menu --}}
                 <div class="border-t border-gray-200 dark:border-gray-600 pt-2">
                    <div class="block px-4 pt-2 text-xs text-gray-400">
                        Notifikasi
                        @if(isset($unreadNotifications) && $unreadNotifications->count() > 0)
                            ({{ $unreadNotifications->count() }})
                        @endif
                    </div>
                    @if(isset($unreadNotifications) && $unreadNotifications->isNotEmpty())
                        @foreach($unreadNotifications->take(3) as $notification) {{-- Tampilkan 3 saja di mobile --}}
                            <a href="{{ route('notifications.read', $notification->id) }}" class="block px-4 py-2 text-base font-medium text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-800 dark:focus:text-gray-200 transition duration-150 ease-in-out">
                                <span class="block truncate">{{ $notification->data['message'] ?? 'Notifikasi baru' }}</span>
                                <span class="block text-xs text-gray-500">{{ $notification->created_at->diffForHumans() }}</span>
                            </a>
                        @endforeach
                    @else
                        <p class="px-4 py-2 text-sm text-gray-500">Tidak ada notifikasi baru.</p>
                    @endif
                     {{-- Tombol Mark All Read Responsive --}}
                     @if(isset($unreadNotifications) && $unreadNotifications->isNotEmpty())
                        <form method="POST" action="{{ route('notifications.markAllRead') }}">
                            @csrf
                            <x-responsive-nav-link as="button" type="submit">
                                Tandai Semua Dibaca
                            </x-responsive-nav-link>
                        </form>
                     @endif
                </div>
                {{-- Akhir Notifikasi Responsive --}}

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
