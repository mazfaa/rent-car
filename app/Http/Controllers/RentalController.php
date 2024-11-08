<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Car;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;

class RentalController extends Controller
{
  public function getRentals(Request $request)
  {
    if ($request->ajax()) {
      try {
        $userRole = auth()->user()->roles[0]->name ?? null;
        $user_id = auth()->user()->id ?? null;

        if (auth()->user()->roles[0]->name == 'customer') {
          $rents = Rental::where('user_id', auth()->user()->id)->with(['user', 'car'])->get();
        } else {
          $rents = Rental::whereHas('car', function ($query) {
            $query->where('user_id', auth()->user()->id); // Mengakses car owner
          })->with(['car'])->get();
        }

        $datatables = DataTables::of($rents)
          ->addIndexColumn()
          ->addColumn('user', function ($row) {
            return $row->user->name;
          })
          ->addColumn('customer_sim', function ($row) {
            return $row->user->driving_license_number;
          })
          ->addColumn('customer_address', function ($row) {
            return $row->user->address;
          })
          ->addColumn('customer_phone_number', function ($row) {
            return $row->user->phone_number;
          })
          ->addColumn('car', function ($row) {
            return $row->car->car_model->brand->name . ' ' . $row->car->car_model->name;
          })
          ->addColumn('car_owner', function ($row) {
            return $row->car->user->name;
          })
          ->addColumn('start_date', function ($row) {
            return '<span class="inline-block bg-blue-500 text-white px-2 py-1 rounded-full text-sm">' . $row->start_date . '</span>';
          })
          ->addColumn('end_date', function ($row) {
            return '<span class="inline-block bg-red-600 text-white px-2 py-1 rounded-full text-sm">' . $row->end_date . '</span>';
          })
          ->addColumn('total_price', function ($row) {
            return '$' . number_format($row->total_price, 2);
          })
          ->addColumn('status', function ($row) {
            if ($row->status === 'Completed') {
              return '<span class="inline-block bg-gray-800 text-white px-2 py-1 rounded-full text-sm">Completed</span>';
            } elseif ($row->status === 'Rented') {
              return '<span class="inline-block bg-red-600 text-white px-2 py-1 rounded-full text-sm">Rented</span>';
            }
          })
          ->rawColumns(['status', 'start_date', 'end_date', 'total_price']);

        if ($userRole === 'admin') {
          $datatables->addColumn('action', function ($row) {
            return '
                        <div class="flex items-center gap-x-2 justify-center">
                            <a href="' . route('rentals.edit', $row->id) . '" class="edit btn btn-success btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteRental(' . $row->id . ')" class="delete btn btn-danger btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </a>
                        </div>
                    ';
          })->rawColumns(['action', 'status', 'start_date', 'end_date', 'total_price']);
        }

        return $datatables->make(true);
      } catch (\Exception $error) {
        return response()->json(['Error' => $error->getMessage()], 500);
      }
    }

    return view('rentals.index');
  }

  public function index()
  {
    // $rentals = Rental::whereHas('car', function ($query) {
    //   $query->where('user_id', auth()->user()->id); // Mengakses car owner
    // })->with(['user', 'car'])->get();
    // dd(auth()->user()->roles[0]->name);
    return view('rentals.index');
  }

  public function create()
  {
    $cars = Car::where('status', 'Available')->with('car_model')->get();
    return view('rentals.create', compact('cars'));
  }

  public function store(Request $request)
  {
    $request->validate([
      'car_id' => 'required|exists:cars,id',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $car = Car::find($request->car_id);
    $days = (strtotime($request->end_date) - strtotime($request->start_date)) / 86400;
    $total_price = $car->daily_rate * $days;

    $car->status = 'Rented';
    $car->save();

    Rental::create([
      'user_id' => auth()->user()->id,
      'car_id' => $request->car_id,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date,
      'total_price' => $total_price
    ]);

    Alert::success('Rental Created', 'Rental has been successfully created');
    return redirect()->route('rentals.index');
  }

  public function show($id)
  {
    $rental = Rental::with(['user', 'car'])->findOrFail($id);
    return view('rentals.show', compact('rental'));
  }

  public function edit($id)
  {
    $rental = Rental::findOrFail($id);
    $cars = Car::where('status', 'Available')->get();
    return view('rentals.edit', compact('rental', 'cars'));
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'user_id' => 'required|exists:users,id',
      'car_id' => 'required|exists:cars,id',
      'start_date' => 'required|date',
      'end_date' => 'required|date|after_or_equal:start_date',
    ]);

    $rental = Rental::findOrFail($id);
    $car = Car::find($request->car_id);
    $days = (strtotime($request->end_date) - strtotime($request->start_date)) / 86400;
    $total_price = $car->daily_rate * $days;

    $rental->update([
      'user_id' => $request->user_id,
      'car_id' => $request->car_id,
      'start_date' => $request->start_date,
      'end_date' => $request->end_date,
      'total_price' => $total_price
    ]);

    Alert::info('Rental Updated', 'Rental has been updated successfully');
    return redirect()->route('rentals.index');
  }

  public function destroy($id)
  {
    try {
      $rental = Rental::findOrFail($id);
      $rental->delete();

      Alert::warning('Rental Deleted', 'Rental has been deleted');
      return redirect()->route('rentals.index');
    } catch (\Exception $error) {
      return response()->json(['message' => $error->getMessage()]);
    }
  }
}
