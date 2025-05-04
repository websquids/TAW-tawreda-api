<?php

namespace App\Filters;

use App\Models\AppSetting;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class AppSettingsFilter extends BaseFilter {
  public function __construct() {
    parent::__construct(AppSetting::class);
  }

  public function apply(Builder $query, Request $request): Builder {
    parent::apply($query, $request);
    return $query;
  }

  public function filterByKey(Builder $query, $key): Builder {
    $query->where('key', $key);
    return $query;
  }
}
