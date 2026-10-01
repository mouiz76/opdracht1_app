<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Overzicht Allergenen') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Terug knop --}}
                    <div class="mb-4">
                        <a href="{{ route('magazijn.overzicht') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors duration-150">
                            &larr; Terug naar Overzicht
                        </a>
                    </div>

                    <h3 class="text-lg font-semibold mb-4">Overzicht Allergenen - {{ $product->Naam }}</h3>

                    @if($geenAllergenen)
                        {{-- Scenario 02: Geen allergenen --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                <tbody>
                                    <tr>
                                        <td class="px-6 py-8 text-center text-gray-700 border border-gray-300">
                                            <div class="flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <span class="font-medium">
                                                    In dit product zitten geen stoffen die een allergische reactie kunnen veroorzaken
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Na 4 seconden doorverwijzen naar Overzicht Magazijn Jamin --}}
                        <script>
                            setTimeout(function() {
                                window.location.href = "{{ route('magazijn.overzicht') }}";
                            }, 4000);
                        </script>

                    @else
                        {{-- Scenario 01: Allergeneninformatie tonen --}}

                        {{-- Product informatie boven de tabel --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                            <div>
                                <span class="text-xs font-medium uppercase text-gray-500 block">Naam Product</span>
                                <span class="font-semibold text-gray-800">{{ $product->Naam }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-medium uppercase text-gray-500 block">Barcode</span>
                                <span class="font-semibold text-gray-800">{{ $product->Barcode }}</span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Naam
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Omschrijving
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($allergenen as $productAllergeen)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                {{ $productAllergeen->allergeen->Naam }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                {{ $productAllergeen->allergeen->Omschrijving }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
