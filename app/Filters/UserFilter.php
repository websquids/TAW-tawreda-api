<?php

namespace App\Filters;

use App\Models\User;

class UserFilter extends BaseFilter {
  public function __construct() {
    parent::__construct(User::class);
  }
}
