<?php

namespace App\Services;

use App\Models\Address;

class AddressService {
  public function create($addressData) {
    $address = Address::create($addressData);
    return $address;
  }
}
