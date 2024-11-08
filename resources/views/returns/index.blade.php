<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Completed Rent Cars') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between pb-8">
                        <h2 class="font-semibold text-lg text-gray-800">
                            Returned Cars
                        </h2>
                        <x-primary-link href="{{ route('returns.create') }}">
                            Return Car
                        </x-primary-link>
                    </div>
                    <table class="table table-bordered" id="returns-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Customer</th>
                                <th>Car</th>
                                <th>Car Owner</th>
                                <th>Return Date</th>
                                <th>Total Cost</th>
                                {{-- @role('admin')
                                    <th> </th>
                                @endrole --}}
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            $(document).ready(function() {
                $('#returns-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('returns.list') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'customer',
                            name: 'customer'
                        },
                        {
                            data: 'car',
                            name: 'car'
                        },
                        {
                            data: 'car_owner',
                            name: 'car_owner'
                        },
                        {
                            data: 'return_date',
                            name: 'return_date'
                        },
                        {
                            data: 'total_cost',
                            name: 'total_cost'
                        }
                    ]
                });
            })
        </script>
    </x-slot>
</x-app-layout>
