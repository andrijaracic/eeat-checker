<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Korisnički Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3>Dobrodošli, {{ $user->name }}</h3>
                <p>Uloga: {{ $user->role }}</p>
                <p>Status: {{ $user->is_active ? 'Aktivan' : 'Neaktivan' }}</p>
            </div>
        </div>
    </div>
</x-app-layout>

