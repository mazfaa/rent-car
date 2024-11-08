<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Rent') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <div class="flex justify-between items-center pb-8">
                            <h2 class="font-semibold text-lg text-gray-800 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 me-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>

                                Edit Rent Car
                            </h2>

                            <x-primary-link href="{{ route('rentals.index') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-4 me-1">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
                                </svg>


                                Back
                            </x-primary-link>
                        </div>
                        <form action="{{ route('rentals.update', $rental->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="user_id" value="{{ $rental->user_id }}">
                            <div class="mb-4">
                                <label for="car_id" class="block text-sm font-medium text-gray-700">Select a
                                    Car</label>
                                <select name="car_id" id="car_id"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @foreach ($cars as $car)
                                        <option value="{{ $car->id }}"
                                            {{ old('car_id') == $car->id ? 'selected' : '' }}
                                            {{ $rental->car_id == $car->id ? 'selected' : '' }}
                                            data-rate={{ $car->daily_rate }}>
                                            {{ $car->car_model->brand->name . ' ' . $car->car_model->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-4">
                                <label for="start_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Mulai</label>
                                <input type="date" name="start_date" id="start_date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    value="{{ $rental->start_date }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="end_date" class="block text-sm font-medium text-gray-700">Tanggal
                                    Selesai</label>
                                <input type="date" name="end_date" id="end_date"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    value="{{ $rental->end_date }}" required>
                            </div>
                            <div class="mb-4">
                                <label for="total_price">Total Price (Based on car rent's daily rate x rent
                                    days)</label>
                                <input type="text" name="" id="total_price"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    value="{{ old('total_price') }}" disabled>
                                @error('total_price')
                                    <div class="text-red-500 text-sm">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            $(document).ready(function() {
                function calculateTotalPrice() {
                    const startDate = new Date($('#start_date').val());
                    const endDate = new Date($('#end_date').val());
                    const dailyRate = parseFloat($('#car_id option:selected').data('rate')) || 0;

                    if (startDate && endDate && dailyRate) {
                        const timeDiff = endDate - startDate;
                        const days = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));

                        if (days > 0) {
                            $('#total_price').val((days * dailyRate).toLocaleString('en-US', {
                                style: 'currency',
                                currency: 'USD'
                            }));
                        } else {
                            $('#total_price').val('Invalid date range');
                        }
                    } else {
                        $('#total_price').val(parseFloat($('#car_id option:selected').data('rate')) || 0);
                    }
                }

                $('#car_id, #start_date, #end_date').change(function() {
                    calculateTotalPrice();
                });

                // Initial calculation
                calculateTotalPrice();
            })
        </script>
    </x-slot>
</x-app-layout>
