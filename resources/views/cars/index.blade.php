<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Car Lists') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between pb-8">
                        <h2 class="font-semibold text-lg text-gray-800">
                            @role('admin')
                                My
                            @endrole Cars
                        </h2>
                        @role('admin')
                            <x-primary-link href="{{ route('cars.create') }}">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="size-5 me-1">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>

                                Create New Car
                            </x-primary-link>
                        @endrole
                    </div>
                    <table class="table table-bordered" id="cars-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Car Owner</th>
                                <th>Brand</th>
                                <th>Model</th>
                                <th>Plate Number</th>
                                <th>Daily Rate</th>
                                <th>Status</th>
                                @role('admin')
                                    <th> </th>
                                @endrole
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
                $('#cars-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: '{{ route('cars.list') }}',
                    columns: [{
                            data: 'DT_RowIndex',
                            name: 'DT_RowIndex'
                        },
                        {
                            data: 'car_owner',
                            name: 'car_owner'
                        },
                        {
                            data: 'brand',
                            name: 'brand'
                        },
                        {
                            data: 'car_model_id',
                            name: 'car_model_id'
                        },
                        {
                            data: 'plate_number',
                            name: 'plate_number'
                        },
                        {
                            data: 'daily_rate',
                            name: 'daily_rate'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        @role('admin')
                            {
                                data: 'action',
                                name: 'action',
                                orderable: false,
                                searchable: false,
                                render: function(data, type, row) {
                                    return `
                                  <div class="flex items-center gap-x-2 justify-center">
                                    <a href="/cars/${row.id}/edit" class="edit btn btn-success btn-sm">
                                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                      </svg>
                                    </a>

                                    <button onclick="deleteCar(${row.id})" class="delete btn btn-danger btn-sm">
                                      <a href="javascript:void(0)" onclick="deleteCar(' . $row->id . ')" class="delete btn btn-danger btn-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                      </a>
                                    </button>  
                                  </div>
                              `;
                                }
                            }
                        @endrole
                    ]
                });
            });

            function deleteCar(carId) {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "But you can restore again!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/cars/${carId}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire(
                                    'Deleted!',
                                    'The car has been deleted.',
                                    'success'
                                );
                                $('#cars-table').DataTable().ajax.reload();
                            },
                            error: function(xhr) {
                                Swal.fire(
                                    'Error!',
                                    'There was a problem deleting the car.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }
        </script>
    </x-slot>
</x-app-layout>
