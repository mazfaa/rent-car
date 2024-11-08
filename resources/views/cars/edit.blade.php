<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Car') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center pb-8">
                        <h2 class="font-semibold text-lg text-gray-800 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="size-5 me-1">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                            </svg>

                            Edit Car
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

                    <form action="{{ route('cars.update', $car->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="brand" class="block text-sm font-medium text-gray-700 mb-1">Car Brand</label>
                            <select id="brand" name="brand"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Choose or Add Brand</option>
                                @foreach ($brands as $brand)
                                    <option value="{{ $brand->name }}"
                                        {{ old('brand') == $brand->id ? 'selected' : '' }}
                                        {{ $car->car_model->brand->id == $brand->id ? 'selected' : '' }}
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

                                @foreach ($models as $model)
                                    @if ($model->brand_id == $car->car_model->brand->id)
                                        <option value="{{ $model->name }}"
                                            {{ old('car_model', $car->car_model->id) == $model->id ? 'selected' : '' }}>
                                            {{ $model->name }}
                                        </option>
                                    @endif
                                @endforeach
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
                                value="{{ old('plate_number', $car->plate_number) }}">
                            @error('plate_number')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="daily_rate" class="block text-sm font-medium text-gray-700">Daily Rate</label>
                            <input type="number" name="daily_rate" id="daily_rate"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('daily_rate', $car->daily_rate) }}">
                            @error('daily_rate')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="Available"
                                    {{ old('status', $car->status) == 'Available' ? 'selected' : '' }}>Available
                                </option>
                                <option value="Rented" {{ old('status', $car->status) == 'Rented' ? 'selected' : '' }}>
                                    Rented
                                </option>
                            </select>
                            @error('status')
                                <div class="text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update Car</button>
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

                const brandId = $('#brand').find(':selected').data('id');
                const selectedModelId = "{{ $car->car_model->id ?? '' }}"; // Pre-select for edit
                if (brandId) {
                    loadModels(brandId, selectedModelId);
                }

                // Change event for brand select
                $('select#brand').change(function() {
                    const brandId = $(this).find(':selected').data('id');
                    if (brandId) {
                        loadModels(brandId);
                    } else {
                        $('#model').empty().append('<option value="">Choose a Car Model</option>');
                    }
                });

                function loadModels(brandId, selectedModelId = null) {
                    $.ajax({
                        url: `/get-models-by-brand/${brandId}`,
                        method: 'GET',
                        success: function(data) {
                            $('#model').empty().removeAttr('disabled');
                            $('#model').append('<option value="">Choose a Car Model</option>');

                            $.each(data, function(index, model) {
                                $('#model').append(
                                    `<option value="${model.name}" ${selectedModelId == model.id ? 'selected' : ''}>
                            ${model.name}
                        </option>`
                                );
                            });

                            $('#model').trigger('change'); // Refresh Select2
                        },
                        error: function() {
                            alert('Error loading models.');
                        }
                    });
                }
            });
        </script>
    </x-slot>
</x-app-layout>
