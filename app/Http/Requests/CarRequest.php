<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CarRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   */
  public function authorize(): bool
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
   */
  public function rules(): array
  {
    $rules = [
      'brand' => 'required|string|max:255',
      'car_model' => 'required',
      'daily_rate' => 'required|numeric|min:1',
    ];

    $rules['plate_number'] = 'required|string|max:10|unique:cars,plate_number' . ($this->method() === 'PUT' ? ',' . $this->car->id : '');

    return $rules;
  }
}
