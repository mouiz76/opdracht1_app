<x-app-layout>
    <x-slot name="header">
        <h1 class="text-xl font-semibold">Leverancier Details</h1>
    </x-slot>

    @php($page = request()->integer('page', 1))
    <div class="mx-auto max-w-2xl px-4 py-10">
        <div class="rounded-lg bg-white p-6 shadow-sm">
            <table class="w-full text-left text-sm">
                <tbody>
                    @foreach ($velden as $veld => $label)
                        <tr class="border-b">
                            <th scope="row" class="px-4 py-3">{{ $label }}</th>
                            <td class="px-4 py-3">{{ $leverancier->$veld ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="mt-6 flex items-center justify-between gap-4">
                <a class="rounded-md bg-indigo-700 px-4 py-2 font-semibold text-white" href="{{ route('leverancier.beheer.edit', ['leverancierId' => $leverancier->Id, 'page' => $page]) }}">Wijzig</a>
                <div class="flex gap-4 font-semibold text-indigo-700">
                    <a href="{{ route('leverancier.beheer.index', ['page' => $page]) }}">Terug</a>
                    <a href="{{ route('dashboard') }}">Home</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
