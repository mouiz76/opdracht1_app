<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Levering Informatie') }}
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

                    <h3 class="text-lg font-semibold mb-4">Levering Informatie - {{ $product->Naam }}</h3>

                    @if($geenVoorraad)
                        {{-- Scenario 02: Geen voorraad --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                <tbody>
                                    <tr>
                                        <td class="px-6 py-8 text-center text-gray-700 border border-gray-300">
                                            <div class="flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                                                </svg>
                                                <span class="font-medium">
                                                    Er is van dit product op dit moment geen voorraad aanwezig, de verwachte eerstvolgende levering is:
                                                    @if($verwachteLevering)
                                                        {{ \Carbon\Carbon::parse($verwachteLevering)->format('d-m-Y') }}
                                                    @else
                                                        Onbekend
                                                    @endif
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
                        {{-- Scenario 01: Leveringsinformatie tonen --}}

                        {{-- Leverancier informatie boven de tabel --}}
                        @if($leverancierInfo)
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                                <div>
                                    <span class="text-xs font-medium uppercase text-gray-500 block">Naam leverancier</span>
                                    <span class="font-semibold text-gray-800">{{ $leverancierInfo->Naam }}</span>
                                </div>
                                <div>
                                    <span class="text-xs font-medium uppercase text-gray-500 block">Contactpersoon leverancier</span>
                                    <span class="font-semibold text-gray-800">{{ $leverancierInfo->ContactPersoon }}</span>
                                </div>
                                <div>
                                    <span class="text-xs font-medium uppercase text-gray-500 block">Leveranciernummer</span>
                                    <span class="font-semibold text-gray-800">{{ $leverancierInfo->LeverancierNummer }}</span>
                                </div>
                                <div>
                                    <span class="text-xs font-medium uppercase text-gray-500 block">Mobiel</span>
                                    <span class="font-semibold text-gray-800">{{ $leverancierInfo->Mobiel }}</span>
                                </div>
                            </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Naam Product
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Datum laatste levering
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Aantal
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Eerstvolgende levering
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($leveringen as $levering)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                {{ $product->Naam }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                {{ \Carbon\Carbon::parse($levering->DatumLevering)->format('d-m-Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                {{ $levering->Aantal }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                @if($levering->DatumEerstVolgendeLevering)
                                                    {{ \Carbon\Carbon::parse($levering->DatumEerstVolgendeLevering)->format('d-m-Y') }}
                                                @else
                                                    <span class="text-gray-400 italic">Niet bekend</span>
                                                @endif
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
