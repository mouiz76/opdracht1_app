<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">
                            {{ __("Welkom terug,") }} <span class="font-bold">{{ Auth::user()->name }}</span>!
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ __("Je bent succesvol ingelogd.") }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400 block mb-1">
                            {{ __('Rol in database') }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-indigo-100 text-indigo-800 border border-indigo-200">
                            {{ Auth::user()->role }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 border-t border-gray-100 pt-4 grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-gray-50 p-4 rounded-md">
                        <span class="text-gray-500 block text-xs font-medium uppercase">Gebruikersnaam</span>
                        <span class="font-semibold text-gray-800">{{ Auth::user()->name }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <span class="text-gray-500 block text-xs font-medium uppercase">E-mailadres</span>
                        <span class="font-semibold text-gray-800">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-md">
                        <span class="text-gray-500 block text-xs font-medium uppercase">Rol</span>
                        <span class="font-semibold text-indigo-600">{{ Auth::user()->role }}</span>
                    </div>
                </div>

                {{-- Link naar Overzicht Magazijn Jamin --}}
                <div class="mt-6 border-t border-gray-100 pt-4">
                    <a href="{{ route('magazijn.overzicht') }}"
                       class="inline-flex items-center px-6 py-3 bg-indigo-600 text-white text-sm font-semibold rounded-md hover:bg-indigo-700 transition-colors duration-150 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        Overzicht Magazijn Jamin
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
