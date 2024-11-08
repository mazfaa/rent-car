<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="container">
                        <h2>Detail Rental</h2>
                        <p><strong>User:</strong> {{ $rental->user->name }}</p>
                        <p><strong>Mobil:</strong> {{ $rental->car->model }}</p>
                        <p><strong>Tanggal Mulai:</strong> {{ $rental->start_date }}</p>
                        <p><strong>Tanggal Selesai:</strong> {{ $rental->end_date }}</p>
                        <p><strong>Total Harga:</strong> Rp{{ number_format($rental->total_price, 2) }}</p>
                        <a href="{{ route('rentals.index') }}" class="btn btn-primary">Kembali</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">

    </x-slot>
</x-app-layout>
