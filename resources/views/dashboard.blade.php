<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold">Moje provere</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4">

        {{-- 🔹 Poruka o uspehu --}}
        @if(session('success'))
            <div class="bg-green-200 p-3 mb-4 rounded">
                {{ session('success') }}
            </div>
        @endif

        {{-- 🔹 Forma za unos URL-a --}}
        <form action="{{ route('checks.store') }}" method="POST" class="mb-6">
            @csrf
            <label for="url" class="block font-semibold mb-1">Unesi URL:</label>
            <input 
                type="url" 
                name="url" 
                id="url" 
                placeholder="https://example.com" 
                required 
                value="{{ old('url') }}"
                class="border p-2 w-full rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
            >

            {{-- Validacione greške --}}
            @error('url')
                <div class="text-red-500 mt-1">{{ $message }}</div>
            @enderror

            <button type="submit" class="m-3 text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                Proveri URL
            </button>
        </form>

        {{-- 🔹 Istorija provera --}}
        <h2 class="mt-6 font-bold text-lg">Istorija provera</h2>
        <div class="overflow-x-auto mt-2">
            <table class="min-w-full border">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-2 border">URL</th>
                        <th class="p-2 border">Status</th>
                        <th class="p-2 border">Datum</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($checks as $check)
                    <tr class="hover:bg-gray-50">
                        <td class="p-2 border break-words">{{ $check->url }}</td>
                        <td class="p-2 border">
                            @php
                                $statusColors = [
                                    'queued' => 'bg-gray-200 text-gray-800',
                                    'processing' => 'bg-yellow-200 text-yellow-800',
                                    'done' => 'bg-green-200 text-green-800',
                                    'failed' => 'bg-red-200 text-red-800',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded {{ $statusColors[$check->status] ?? 'bg-gray-200 text-gray-800' }}">
                                {{ ucfirst($check->status) }}
                            </span>
                        </td>
                        <td class="p-2 border">{{ $check->created_at->format('d.m.Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginacija --}}
        <div class="mt-4">
            {{ $checks->links() }}
        </div>

    </div>
</x-app-layout>


