<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Car') }}
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

                            Create New Car
                        </h2>

                        <x-primary-link href="{{ route('cars.index') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-4 me-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                            </svg>

                            Back
                        </x-primary-link>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-500 text-white p-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('cars.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Car Brand</label>
                            <select id="brand" name="brand"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Choose or Add Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->name }}"
                                        {{ old('brand') == $brand->id ? 'selected' : '' }}
                                        data-id="{{ $brand->id }}">{{ $brand->name }}</option>
                                @endforeach
                            </select>
                            @error('brand')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="model" class="block text-sm font-medium text-gray-700">Car Model</label>
                            <select id="model" name="car_model"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" disabled>
                                <option value="">Choose Car Model</option>
                            </select>
                            @error('car_model')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="plate_number" class="block text-sm font-medium text-gray-700">Plate
                                Number</label>
                            <input type="text" name="plate_number" id="plate_number"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('plate_number') }}">
                            @error('plate_number')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="daily_rate" class="block text-sm font-medium text-gray-700">Daily Rate</label>
                            <input type="number" name="daily_rate" id="daily_rate"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('daily_rate', 1) }}">
                            @error('daily_rate')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="Available" {{ old('status') == 'available' ? 'selected' : '' }}>
                                    Available</option>
                                <option value="Rented" {{ old('status') == 'rented' ? 'selected' : '' }}>
                                    Rented</option>
                            </select>
                            @error('status')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div> --}}

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Save Car</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            $(document).ready(function() {
                $('select#brand').select2({
                    tags: true,
                    placeholder: "Choose or Add Brand",
                    allowClear: true,
                    width: '100%'
                });

                $('#model').select2({
                    tags: true,
                    placeholder: "Choose or Add Model",
                    allowClear: true,
                    width: '100%'
                });

                $('select#brand').change(function() {
                    const brandId = $(this).find(':selected').data('id');

                    if (brandId) {
                        $.ajax({
                            url: `/get-models-by-brand/${brandId}`,
                            method: 'GET',
                            success: function(data) {
                                $('#model').empty();
                                $('#model').removeAttr('disabled')

                                $.each(data, function(index, model) {
                                    $('#model').append(
                                        `<option value=${model.name}>${model.name}</option>`
                                    );
                                });
                            },
                            error: function() {
                                alert('Terjadi kesalahan saat memuat data model.');
                            }
                        });
                    } else {
                        $('#model').empty();
                        $('#model').append('<option value="">Choose a Car Model</option>');
                        $('#model').removeAttr('disabled', '')
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>
