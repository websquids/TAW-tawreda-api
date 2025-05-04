<?php

namespace App\Http\Controllers\CustomerApp;

use App\Http\Controllers\Controller;
use App\Services\BrandService;
use Illuminate\Http\Request;

class BrandController extends Controller {
  protected BrandService $brandService;

  public function __construct(BrandService $brandService) {
    $this->brandService = $brandService;
  }

  public function index(Request $request) {
    $brands = $this->brandService-> getFilteredBrands($request);
    return response()->apiResponse($brands);
  }
}
