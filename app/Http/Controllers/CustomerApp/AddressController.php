<?php

namespace App\Http\Controllers\CustomerApp;

use App\Constants\AddressTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerApp\AddressStoreRequest;
use App\Http\Requests\CustomerApp\AddressUpdateRequest;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller {
  // Get all addresses of a given model
  public function index(Request $request) {
    $request->validate([
      'model_type' => [
        'required',
        'string',
        'in:' . implode(',', AddressTypes::getAllTypes()),
      ],
      'model_id' => 'required|integer',
    ]);

    $modelClass = $this->resolveModelClass($request->get('model_type'));
    $modelId = $request->get('model_id');
    $modelData = $modelClass::findOrFail($modelId);
    if (!$modelData) {
      return response()->json(['error' => 'Invalid model ID'], 400);
    }
    // dd($modelData->addresses()->get());
    $addresses = $modelData->addresses()->get();
    return response()->json($addresses);
  }

  // Create a new address
  public function store(AddressStoreRequest $request) {
    try {
      DB::beginTransaction();
      $modelClass = $this->resolveModelClass($request->get('model_type'));
      $modelId = $request->get('model_id');
      $modelInstance = $modelClass::find($modelId);
      if (!$modelInstance) {
        return response()->json(['error' => 'Invalid model ID'], 400);
      }
      $address = $modelInstance->addresses()->create($request->validated());
      DB::commit();
      return response()->json($address, 201);
    } catch (\Exception $e) {
      DB::rollBack();
      return response()->json(['error' => 'Failed to create address'], 500);
    }
  }

  // Update an existing address
  public function update(AddressUpdateRequest $request, Address $address) {
    try {
      DB::beginTransaction();
      $address = Address::find($request->route('id'));
      $modelClass = $this->resolveModelClass($request->get('model_type'));
      $modelId = $request->get('model_id');
      $modelInstance = $modelClass::find($modelId);
      if (!$modelInstance) {
        return response()->json(['error' => 'Invalid model ID'], 400);
      }
      $address->update($request->validated());
      $address->save();
      DB::commit();
      return response()->json($address);
    } catch (\Exception $e) {
      dd($e);
      DB::rollBack();
      return response()->json(['error' => 'Failed to update address'], 500);
    }
  }

  protected function resolveModelClass(string $addressType): ?string {
    return AddressTypes::$modelMapping[$addressType] ?? null;
  }
}
