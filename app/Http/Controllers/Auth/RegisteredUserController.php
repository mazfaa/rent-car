<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
  /**
   * Display the registration view.
   */
  public function create(): View
  {
    return view('auth.register');
  }

  /**
   * Handle an incoming registration request.
   *
   * @throws \Illuminate\Validation\ValidationException
   */
  public function store(Request $request): RedirectResponse
  {
    $request->validate([
      'name' => ['required', 'string', 'max:255'],
      'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
      'address' => ['required', 'string'],
      'phone_number' => ['required', 'string', 'max:13'],
      'driving_license_number' => ['required', 'string', 'max:255'],
      'password' => ['required', 'confirmed', Rules\Password::defaults()],
    ]);

    $user = User::create([
      'name' => $request->name,
      'email' => $request->email,
      'address' => $request->address,
      'phone_number' => $request->phone_number,
      'driving_license_number' => $request->driving_license_number,
      'password' => Hash::make($request->password),
    ]);

    $request->role == 'admin' ? $user->assignRole('admin') : $user->assignRole('customer');

    event(new Registered($user));

    Auth::login($user);

    return redirect(route('dashboard', absolute: false));
  }
}
