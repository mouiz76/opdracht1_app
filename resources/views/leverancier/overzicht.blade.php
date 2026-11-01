<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Overzicht leverancier') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Terugknop naar dashboard --}}
                    <div class="mb-4">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors duration-150">
                            &larr; Terug naar Dashboard
                        </a>
                    </div>

                    <h3 class="text-lg font-semibold mb-4">Overzicht leverancier</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                        Naam leverancier
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                        Aantal producten
                                    </th>
                                    <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                        Toon producten
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($leveranciers as $leverancier)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                            {{ $leverancier->Naam }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-900 border border-gray-300">
                                            {{ $leverancier->AantalProducten }}
                                        </td>
                                        <td class="px-4 py-3 whitespace-nowrap text-sm text-center border border-gray-300">
                                            {{-- Box-icoon: klik om geleverde producten te bekijken --}}
                                            <a href="{{ route('leverancier.geleverde-producten', $leverancier->Id) }}"
                                               class="text-indigo-600 hover:text-indigo-800 transition-colors duration-150"
                                               title="Toon producten van {{ $leverancier->Naam }}">
                                                {{-- Doos/box icoon --}}
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
