<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Teknisi: ') }} {{ $technician->name }}
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="p-6 md:p-8">
                <div class="mb-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Data Teknisi</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Perbarui informasi teknisi di bawah ini</p>
                </div>

                @if ($errors->any())
                    <x-flash-message type="error">Mohon periksa kembali form yang Anda isi.</x-flash-message>
                @endif

                <form method="POST" action="{{ route('kepala.technicians.update', $technician) }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Nama Teknisi')" />
                            <x-text-input id="name" class="mt-2 block w-full" type="text" name="name" :value="old('name', $technician->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input id="email" class="mt-2 block w-full" type="email" name="email" :value="old('email', $technician->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-4">Ubah Password (Opsional)</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">Kosongkan password jika Anda tidak ingin mengubahnya.</p>

                            <div class="space-y-6">
                                <div>
                                    <x-input-label for="password" :value="__('Password Baru (Opsional)')" />
                                    <x-text-input id="password" class="mt-2 block w-full" type="password" name="password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div>
                                    <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                                    <x-text-input id="password_confirmation" class="mt-2 block w-full" type="password" name="password_confirmation" />
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('kepala.technicians.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg font-medium text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                            Batal
                        </a>
                        <x-primary-button>
                            {{ __('Update Teknisi') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
