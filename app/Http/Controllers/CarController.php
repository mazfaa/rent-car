<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarRequest;
use App\Models\Brand;
use App\Models\Car;
use App\Models\CarModel;
use Error;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\DataTables;

class CarController extends Controller
{
  public function getCars(Request $request)
  {
    if ($request->ajax()) {
      try {
        $userRole = auth()->user()->roles[0]->name ?? null;
        if ($userRole === 'customer') {
          $cars = Car::with('user')->get();
        } else {
          $cars = Car::where('user_id', auth()->user()->id)->with('user')->get();
        }

        $datatables = DataTables::of($cars)
          ->addIndexColumn()
          ->addColumn('car_owner', function ($row) {
            return $row->user->name;
          })
          ->addColumn('car_model_id', function ($row) {
            return $row->car_model->name;
          })
          ->addColumn('plate_number', function ($row) {
            return
              '<span class="inline-block bg-gray-800 text-white px-2 py-1 rounded-full text-sm">' . $row->plate_number . '</span>';
          })
          ->addColumn('brand', function ($row) {
            return $row->car_model->brand->name;
          })
          ->addColumn('daily_rate', function ($row) {
            return '$' . number_format($row->daily_rate, 2);
          })
          ->addColumn('status', function ($row) {
            if ($row->status === 'Available') {
              return '<span class="inline-block bg-gray-800 text-white px-2 py-1 rounded-full text-sm">Available</span>';
            } elseif ($row->status === 'Rented') {
              return '<span class="inline-block bg-red-600 text-white px-2 py-1 rounded-full text-sm">Rented</span>';
            }
          })
          ->rawColumns(['status', 'plate_number']);

        if ($userRole === 'admin') {
          $datatables->addColumn('action', function ($row) {
            return '
                        <div class="flex items-center gap-x-2 justify-center">
                            <a href="' . route('cars.edit', $row->id) . '" class="edit btn btn-success btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            <a href="javascript:void(0)" onclick="deleteCar(' . $row->id . ')" class="delete btn btn-danger btn-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </a>
                        </div>
                    ';
          })->rawColumns(['action', 'status', 'plate_number']);
        }

        return $datatables->make(true);
      } catch (\Exception $error) {
        return response()->json(['Error' => $error->getMessage()], 500);
      }
    }

    return view('cars.index');
  }

  public function index()
  {
    // dd(auth()->user()->roles);
    $cars = Car::all();
    return view('cars.index', compact('cars'));
  }

  public function create()
  {
    return view('cars.create', ['brands' => Brand::all()]);
  }

  public function store(CarRequest $request)
  {
    // dd($request->all());
    try {
      $brand = Brand::firstOrCreate(['name' => $request->brand]);

      // Check and save model
      $model = CarModel::firstOrCreate([
        'name' => $request->car_model,
        'brand_id' => $brand->id,
      ]);

      // dd($model->id);

      Car::create([
        'user_id' => auth()->user()->id,
        'car_model_id' => $model->id,
        'plate_number' => $request->plate_number,
        'daily_rate' => $request->daily_rate,
        'status' => 'Available',
      ]);
      Alert::success('Succeed', 'Car Sucessfully Created!');
      return redirect()->route('cars.index')->with('success', 'Car added successfully');
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function show(Car $car)
  {
    return view('cars.show', compact('car'));
  }

  public function edit(Car $car)
  {
    $brands = Brand::all();
    $models = CarModel::all();
    return view('cars.edit', compact('car', 'brands', 'models'));
  }

  public function update(CarRequest $request, Car $car)
  {
    // dd($request->all());
    $brand = Brand::firstOrCreate(['name' => $request->brand]);

    // Check and save model
    $model = CarModel::firstOrCreate([
      'name' => $request->car_model,
      'brand_id' => $brand->id,
    ]);

    $car->update([
      'user_id' => auth()->user()->id,
      'car_model_id' => $model->id,
      'plate_number' => $request->plate_number,
      'daily_rate' => $request->daily_rate,
      'status' => $request->status,
    ]);
    Alert::info('Updated', 'Car Successfully updated!');
    return redirect()->route('cars.index')->with('success', 'Car updated successfully');
  }

  public function destroy($id)
  {
    try {
      $car = Car::findOrFail($id);
      $car->delete();

      return response()->json(['success' => 'Car deleted successfully']);
    } catch (\Exception $e) {
      return response()->json(['error' => 'Failed to delete car'], 500);
    }
  }
}
