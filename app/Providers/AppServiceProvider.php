<?php

namespace App\Providers;

use App\Models\Address;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\Unit;
use App\Observers\GenericObserver;
use Dedoc\Scramble\Scramble;
use Dedoc\Scramble\Support\Generator\OpenApi;
use Dedoc\Scramble\Support\Generator\SecurityScheme;
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
    Scramble::configure()
      ->withDocumentTransformers(function (OpenApi $openApi) {
        $openApi->secure(
          SecurityScheme::http('bearer'),
        );
      });
    Scramble::registerApi('customer_app', [
      'api_path' => 'api/customer_app',
    ]);
    $models = [
      Product::class,
      Category::class,
      Unit::class,
      Brand::class,
      Address::class,
      Cart::class,
      Order::class,
    ];
    foreach ($models as $model) {
      $model::observe(GenericObserver::class);
    }
  }
}
