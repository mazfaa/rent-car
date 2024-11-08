<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\RentalReturn;
use App\Models\Rental;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;

class RentalReturnController extends Controller
{
  public function getReturns(Request $request)
  {
    if ($request->ajax()) {
      try {
        $userRole = auth()->user()->roles[0]->name ?? null;
        $user_id = auth()->user()->id;

        if ($userRole == 'customer') {
          $rentals = RentalReturn::whereHas('rental', function ($query) use ($user_id) {
            $query->where('user_id', $user_id);
          })->get();
        } else {
          $rentals = RentalReturn::whereHas('rental.car', function ($query) use ($user_id) {
            $query->where('user_id', $user_id); // Filter berdasarkan pemilik mobil
          })->get();
        }

        $datatables = DataTables::of($rentals)
          ->addIndexColumn()

          ->addColumn('customer', function ($row) {
            return $row->rental->user->name;
          })
          ->addColumn('car_owner', function ($row) {
            return $row->rental->car->user->name;
          })
          ->addColumn('car', function ($row) {
            return '<span class="inline-block bg-gray-800 text-white px-2 py-1 rounded-full text-sm">' . $row->rental->car->car_model->brand->name . ' ' . $row->rental->car->car_model->name . '</span>';
          })
          ->addColumn('total_cost', function ($row) {
            return '$' . number_format($row->total_cost, 2);
          })
          ->addColumn('return_date', function ($row) {
            return '<span class="inline-block bg-blue-500 text-white px-2 py-1 rounded-full text-sm">' . $row->return_date . '</span>';
          })
          ->rawColumns(['return_date', 'car']);

        // if ($userRole === 'admin') {
        //   $datatables->addColumn('action', function ($row) {
        //     return '
        //                 <div class="flex items-center gap-x-2 justify-center">
        //                     <a href="' . route('returns.edit', $row->id) . '" class="edit btn btn-success btn-sm">
        //                         <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        //                             <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
        //                         </svg>
        //                     </a>
        //                 </div>
        //             ';
        //   })->rawColumns(['action', 'return_date', 'car']);
        // }

        return $datatables->make(true);
      } catch (\Exception $error) {
        return response()->json(['Error' => $error->getMessage()], 500);
      }
    }

    return view('returns.index');
  }
  public function index()
  {
    $rentalReturns = RentalReturn::with('rental')->get();
    return view('returns.index', compact('rentalReturns'));
  }

  public function create()
  {
    return view('returns.create');
  }

  public function store(Request $request)
  {
    $plate_number = strtoupper($request->plate_number);

    $request->validate([
      'plate_number' => 'required:exists:car,plate_number',
    ]);

    $car = Car::where('plate_number', $plate_number)
      ->where('status', '!=', 'Available')
      ->first();

    $car->status = 'Available';
    $car->save();

    $rental = Rental::where('car_id', $car->id)->where('status', 'Rented')->first();
    $rental->status = 'Completed';
    $rental->save();

    RentalReturn::create([
      'rental_id' => $rental->id,
      'return_date' => now(),
      'total_cost' => $rental->total_price
    ]);

    Alert::success('Returned', 'Car Returned successfully');
    return redirect()->route('returns.index');
  }

  public function show($id)
  {
    $rentalReturn = RentalReturn::with('rental')->findOrFail($id);
    return view('returns.show', compact('rentalReturn'));
  }

  public function edit($id)
  {
    $rental = RentalReturn::findOrFail($id);
    // $rentals = Rental::doesntHave('rentalReturn')->get();
    return view('returns.edit', compact('rental'));
  }

  public function update(Request $request, $id)
  {
    $request->validate([
      'rental_id' => 'required|exists:rentals,id',
      'return_date' => 'required|date',
    ]);

    $rentalReturn = RentalReturn::findOrFail($id);
    $rental = Rental::find($request->rental_id);
    $return_date = \Carbon\Carbon::parse($request->return_date);
    $late_days = $return_date->diffInDays($rental->end_date, false);
    $late_fee = ($late_days > 0) ? $late_days * 100000 : 0;

    $rentalReturn->update([
      'rental_id' => $rental->id,
      'return_date' => $return_date,
      'late_fee' => $late_fee
    ]);

    Alert::info('Return Updated', 'Return has been updated successfully');
    return redirect()->route('return-rentals.index');
  }

  public function destroy($id)
  {
    $rentalReturn = RentalReturn::findOrFail($id);
    $rentalReturn->delete();

    Alert::warning('Return Deleted', 'Return has been deleted');
    return redirect()->route('return-rentals.index');
  }
}
