<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Levering product') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Terugknop naar geleverde producten van deze leverancier --}}
                    <div class="mb-4">
                        <a href="{{ route('leverancier.geleverde-producten', $gegevens->LeverancierId) }}"
                           class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-md hover:bg-gray-700 transition-colors duration-150">
                            &larr; Terug naar Geleverde producten
                        </a>
                    </div>

                    <h3 class="text-lg font-semibold mb-6">Levering product - {{ $gegevens->ProductNaam }}</h3>

                    {{-- Validatiefouten --}}
                    @if($errors->any())
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md">
                            <ul class="list-disc pl-5">
                                @foreach($errors->all() as $fout)
                                    <li>{{ $fout }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Productinformatie (overzicht boven formulier) --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6 bg-gray-50 p-4 rounded-md border border-gray-200">
                        <div>
                            <span class="text-xs font-medium uppercase text-gray-500 block">Naam product</span>
                            <span class="font-semibold text-gray-800">{{ $gegevens->ProductNaam }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-medium uppercase text-gray-500 block">Barcode</span>
                            <span class="font-semibold text-gray-800">{{ $gegevens->Barcode }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-medium uppercase text-gray-500 block">Verpakkingseenheid (kg)</span>
                            <span class="font-semibold text-gray-800">{{ $gegevens->VerpakkingsEenheidInKg }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-medium uppercase text-gray-500 block">Huidig aantal in magazijn</span>
                            <span class="font-semibold text-gray-800">
                                @if($gegevens->AantalAanwezig !== null)
                                    {{ $gegevens->AantalAanwezig }}
                                @else
                                    <span class="text-red-600">Geen voorraad</span>
                                @endif
                            </span>
                        </div>
                        <div>
                            <span class="text-xs font-medium uppercase text-gray-500 block">Naam leverancier</span>
                            <span class="font-semibold text-gray-800">{{ $gegevens->LeverancierNaam }}</span>
                        </div>
                        <div>
                            <span class="text-xs font-medium uppercase text-gray-500 block">Contactpersoon</span>
                            <span class="font-semibold text-gray-800">{{ $gegevens->ContactPersoon }}</span>
                        </div>
                    </div>

                    {{-- Formulier voor nieuwe levering --}}
                    <form method="POST"
                          action="{{ route('levering.opslaan', [$gegevens->LeverancierId, $gegevens->ProductId]) }}"
                          id="levering-formulier">
                        @csrf

                        <div class="space-y-4">
                            {{-- Veld: Aantal producteenheden --}}
                            <div>
                                <label for="aantal" class="block text-sm font-medium text-gray-700 mb-1">
                                    Aantal producteenheden <span class="text-red-600">*</span>
                                </label>
                                <input type="number"
                                       id="aantal"
                                       name="aantal"
                                       value="{{ old('aantal') }}"
                                       min="1"
                                       max="9999"
                                       class="mt-1 block w-full sm:w-64 rounded-md border border-gray-300 shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('aantal') border-red-500 @enderror"
                                       placeholder="Bijv. 25"
                                       required>
                                @error('aantal')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Veld: Datum eerstvolgende levering --}}
                            <div>
                                <label for="datum_eerstvolgende_levering" class="block text-sm font-medium text-gray-700 mb-1">
                                    Datum eerstvolgende levering <span class="text-red-600">*</span>
                                </label>
                                <input type="date"
                                       id="datum_eerstvolgende_levering"
                                       name="datum_eerstvolgende_levering"
                                       value="{{ old('datum_eerstvolgende_levering') }}"
                                       class="mt-1 block w-full sm:w-64 rounded-md border border-gray-300 shadow-sm px-3 py-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('datum_eerstvolgende_levering') border-red-500 @enderror"
                                       required>
                                @error('datum_eerstvolgende_levering')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Knop: Sla op --}}
                            <div class="pt-4">
                                <button type="submit"
                                        id="btn-slaop"
                                        class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700 transition-colors duration-150 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Sla op
                                </button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
