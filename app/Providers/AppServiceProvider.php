<?php

namespace App\Providers;

use Dedoc\Scramble\Scramble;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider {
  /**
   * Register any application services.
   */
  public function register(): void {
            //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void {
    Scramble::registerApi('customer_app', [
      'api_path' => 'api/customer_app',
    ]);
  }
}
