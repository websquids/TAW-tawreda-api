<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddressStoreRequest;
use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller {
  /**
   * Display a listing of the resource.
   */
  public function index() {
        //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create() {
        //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(AddressStoreRequest $request) {
    $address = new Address($request->validated());
    $user = User::find(Auth::user()->id);
    $user->addresses()->save($address);
    return response()->json(['message' => 'Address added successfully'], 201);
  }

  /**
   * Display the specified resource.
   */
  public function show(string $id) {
        //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(string $id) {
        //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $id) {
        //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id) {
        //
  }
}
