<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold">Wijzig Leveranciergegevens</h1>
    </x-slot>

    @php($routeParameters = ['leverancierId' => $leverancier->Id, 'page' => request()->integer('page', 1)])
    @php($detailsUrl = route('leverancier.beheer.show', $routeParameters))
    <div class="mx-auto max-w-2xl px-4 py-10">
        <div class="rounded-lg bg-white p-6 shadow-sm">
            @if ($resultaat = session('leverancierResultaat'))
                <p role="{{ $resultaat['gelukt'] ? 'status' : 'alert' }}"
                   class="mb-4 rounded-md p-4 {{ $resultaat['gelukt'] ? 'bg-green-100 text-green-900' : 'bg-red-100 text-red-900' }}">
                    {{ $resultaat['melding'] }}
                </p>
                <p x-data x-init="setTimeout(() => location.replace($el.querySelector('a').href), 3000)">
                    <a class="font-semibold text-indigo-700 hover:underline" href="{{ $detailsUrl }}">Terug naar Leverancier Details</a>
                </p>
            @else
                <form method="POST" action="{{ route('leverancier.beheer.update', $routeParameters) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')
                    @foreach ($velden as $veld => $label)
                        <div class="grid items-start gap-2 sm:grid-cols-2">
                            <x-input-label :for="$veld" :value="$label" class="sm:pt-2" />
                            <div>
                                <x-text-input :id="$veld" :name="$veld" :type="$veld === 'Mobiel' ? 'tel' : 'text'"
                                              :value="old($veld, $leverancier->$veld)" class="w-full" required />
                                <x-input-error :messages="$errors->get($veld)" class="mt-1" />
                            </div>
                        </div>
                    @endforeach
                    <div class="flex items-center justify-between gap-4 pt-3">
                        <x-primary-button>Sla op</x-primary-button>
                        <div class="flex gap-4 font-semibold text-indigo-700">
                            <a href="{{ $detailsUrl }}">Terug</a>
                            <a href="{{ route('dashboard') }}">Home</a>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
