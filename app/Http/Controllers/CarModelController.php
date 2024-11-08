<?php

namespace App\Http\Controllers;

use App\Models\CarModel;
use Illuminate\Http\Request;

class CarModelController extends Controller
{
  public function getModelsByBrand($brand)
  {
    $models = CarModel::where('brand_id', $brand)->get();
    return response()->json($models);
  }
}
