<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalReturn extends Model
{
  protected $fillable = ['rental_id', 'return_date', 'total_cost'];
  public function rental()
  {
    return $this->belongsTo(Rental::class);
  }
}
