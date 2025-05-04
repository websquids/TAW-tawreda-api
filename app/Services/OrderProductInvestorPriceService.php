<?php

namespace App\Services;

use App\Models\OrderProductInvestorPrice;

class OrderProductInvestorPriceService {
  protected OrderProductInvestorPrice $orderProductInvestorPrice;

  public function __construct(OrderProductInvestorPrice $orderProductInvestorPrice) {
    $this->orderProductInvestorPrice = $orderProductInvestorPrice;
  }
  public function create($data) {
    return $this->orderProductInvestorPrice->create($data);
  }
}
