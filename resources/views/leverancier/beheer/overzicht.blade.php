<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold">Overzicht Leveranciers</h1>
    </x-slot>

    <div class="mx-auto max-w-6xl px-4 py-10">
        <div class="overflow-x-auto rounded-lg bg-white p-6 shadow-sm">
            <table class="w-full text-left text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        @foreach (['Naam', 'Contactpersoon', 'Leveranciernummer', 'Mobiel', 'Leverancier Details'] as $kop)
                            <th scope="col" class="px-4 py-3">{{ $kop }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse ($leveranciers as $leverancier)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ $leverancier->Naam }}</td>
                            <td class="px-4 py-3">{{ $leverancier->ContactPersoon }}</td>
                            <td class="px-4 py-3">{{ $leverancier->LeverancierNummer }}</td>
                            <td class="px-4 py-3">{{ $leverancier->Mobiel }}</td>
                            <td class="px-4 py-3">
                                <a class="inline-flex p-2 text-xl text-indigo-700 hover:underline"
                                   href="{{ route('leverancier.beheer.show', ['leverancierId' => $leverancier->Id, 'page' => $leveranciers->currentPage()]) }}"
                                   aria-label="Leverancier Details van {{ $leverancier->Naam }}"><span aria-hidden="true">✎</span></a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-4 py-4">Geen leveranciers op deze pagina.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-5 flex flex-wrap items-center justify-between gap-4">
            <nav class="flex items-center gap-4" aria-label="Leverancierspagina's">
                @if ($leveranciers->previousPageUrl())
                    <a class="font-semibold text-indigo-700 hover:underline" href="{{ $leveranciers->previousPageUrl() }}">Vorige</a>
                @endif
                <span>Pagina {{ $leveranciers->currentPage() }} van {{ $leveranciers->lastPage() }}</span>
                @if ($leveranciers->nextPageUrl())
                    <a class="font-semibold text-indigo-700 hover:underline" href="{{ $leveranciers->nextPageUrl() }}">Volgende</a>
                @endif
            </nav>
            <a class="font-semibold text-indigo-700 hover:underline" href="{{ route('dashboard') }}">Home</a>
        </div>
    </div>
</x-app-layout>
