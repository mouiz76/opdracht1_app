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

                    <h3 class="text-lg font-semibold mb-4">Levering product</h3>

                    {{-- Melding: product wordt niet meer geproduceerd --}}
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 border border-gray-300">
                            <tbody>
                                <tr>
                                    <td class="px-6 py-8 text-center text-gray-700 border border-gray-300">
                                        <div class="flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-500 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                            <span class="font-medium text-red-700">
                                                {{-- Exacte melding uit de opdracht --}}
                                                Het product {{ $productNaam }} van de leverancier {{ $leverancierNaam }} wordt niet meer geproduceerd
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    {{-- Na 4 seconden terugsturen naar Geleverde producten van deze leverancier --}}
                    <meta http-equiv="refresh" content="4;url={{ route('leverancier.geleverde-producten', $leverancierId) }}">
                    <script>
                        setTimeout(function() {
                            window.location.href = "{{ route('leverancier.geleverde-producten', $leverancierId) }}";
                        }, 4000);
                    </script>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
