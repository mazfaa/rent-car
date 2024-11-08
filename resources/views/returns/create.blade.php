<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Return Car') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center pb-8">
                        <h2 class="font-semibold text-lg text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-4 me-1">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>

                            Return Car
                        </h2>

                        <x-primary-link href="{{ route('returns.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-4 me-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>
                            Back
                        </x-primary-link>
                    </div>
                    <form action="{{ route('returns.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="total_price">Enter Car Plate Number</label>
                            <input type="text" name="plate_number" id="plate_number"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('plate_number') }}" autocomplete="off" autofocus>
                            @error('plate_number')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Return</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">

    </x-slot>
</x-app-layout>
