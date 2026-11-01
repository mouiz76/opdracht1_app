<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Geleverde producten') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Terugknop naar leveranciersoverzicht --}}
                    <div class="mb-4">
                        <a href="{{ route('leverancier.overzicht') }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors duration-150">
                            &larr; Terug naar Overzicht leverancier
                        </a>
                    </div>

                    {{-- Succesbericht bij levering toevoegen --}}
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- Foutbericht --}}
                    @if(session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h3 class="text-lg font-semibold mb-4">Geleverde producten - {{ $leverancier->Naam }}</h3>

                    @if($geenProducten)
                        {{-- Scenario 2: Leverancier heeft geen producten geleverd --}}
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
                                                    Dit bedrijf heeft tot nu toe geen producten geleverd aan Jamin
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Na 3 seconden terugsturen naar Overzicht leverancier --}}
                        <meta http-equiv="refresh" content="3;url={{ route('leverancier.overzicht') }}">
                        <script>
                            setTimeout(function() {
                                window.location.href = "{{ route('leverancier.overzicht') }}";
                            }, 3000);
                        </script>

                    @else
                        {{-- Scenario 1: Toon de geleverde producten --}}

                        {{-- Leverancier informatie boven de tabel --}}
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                            <div>
                                <span class="text-xs font-medium uppercase text-gray-500 block">Naam leverancier</span>
                                <span class="font-semibold text-gray-800">{{ $leverancier->Naam }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-medium uppercase text-gray-500 block">Contactpersoon</span>
                                <span class="font-semibold text-gray-800">{{ $leverancier->ContactPersoon }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-medium uppercase text-gray-500 block">Leveranciernummer</span>
                                <span class="font-semibold text-gray-800">{{ $leverancier->LeverancierNummer }}</span>
                            </div>
                            <div>
                                <span class="text-xs font-medium uppercase text-gray-500 block">Mobiel</span>
                                <span class="font-semibold text-gray-800">{{ $leverancier->Mobiel }}</span>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                                <thead class="bg-gray-100">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Naam product
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Barcode
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Verpakking (kg)
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Aantal in Magazijn
                                        </th>
                                        <th class="px-4 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Datum laatste levering
                                        </th>
                                        <th class="px-4 py-3 text-center text-xs font-bold text-gray-700 uppercase tracking-wider border border-gray-300">
                                            Nieuwe levering
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($producten as $product)
                                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                {{ $product->ProductNaam }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                {{ $product->Barcode }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                {{ $product->VerpakkingsEenheidInKg }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center text-gray-900 border border-gray-300">
                                                @if($product->AantalAanwezig !== null)
                                                    {{ $product->AantalAanwezig }}
                                                @else
                                                    <span class="text-red-600 font-semibold">Geen voorraad</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 border border-gray-300">
                                                {{ \Carbon\Carbon::parse($product->LaatsteLeverbatum)->format('d-m-Y') }}
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap text-sm text-center border border-gray-300">
                                                {{-- Plus-icoon: klik om nieuwe levering toe te voegen --}}
                                                <a href="{{ route('levering.formulier', [$leverancier->Id, $product->ProductId]) }}"
                                                   class="text-green-600 hover:text-green-800 transition-colors duration-150"
                                                   title="Nieuwe levering voor {{ $product->ProductNaam }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </a>
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
