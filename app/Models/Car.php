<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
  use SoftDeletes;

  protected $fillable = ['user_id', 'brand_id', 'car_model_id', 'plate_number', 'daily_rate', 'status'];

  public function car_model()
  {
    return $this->belongsTo(CarModel::class);
  }

  public function rentals()
  {
    return $this->hasMany(Rental::class);
  }

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
