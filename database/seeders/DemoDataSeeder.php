<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Unit;
use App\Models\Product;

class DemoDataSeeder extends Seeder {
  public function run() {
    // Clear existing data
    // DB::statement('SET FOREIGN_KEY_CHECKS=0');
    Product::truncate();
    Category::truncate();
    Brand::truncate();
    Unit::truncate();
    // DB::statement('SET FOREIGN_KEY_CHECKS=1');

    // Categories with parent
    $categories = [
      ['id' => 1, 'parent_id' => null, 'en' => ['title' => 'Fruits', 'description' => 'Fresh and organic fruits'], 'ar' => ['title' => 'فواكه', 'description' => 'فواكه طازجة وعضوية']],
      ['id' => 2, 'parent_id' => null, 'en' => ['title' => 'Vegetables', 'description' => 'Healthy green vegetables'], 'ar' => ['title' => 'خضروات', 'description' => 'خضروات صحية']],
      ['id' => 3, 'parent_id' => 1, 'en' => ['title' => 'Citrus Fruits', 'description' => 'Citrus family fruits like oranges'], 'ar' => ['title' => 'حمضيات', 'description' => 'فواكه عائلة الحمضيات مثل البرتقال']],
      ['id' => 4, 'parent_id' => 2, 'en' => ['title' => 'Leafy Vegetables', 'description' => 'Fresh leafy greens'], 'ar' => ['title' => 'خضروات ورقية', 'description' => 'الخضروات الورقية الطازجة']],
    ];

    foreach ($categories as $data) {
      $category = Category::create($data);
      // $category->addMedia(storage_path('app/public/' . $data['photo']))
            //     ->preservingOriginal()
            //     ->toMediaCollection('categories');
    }

    // Brands
    $brands = [
      ['en' => ['name' => 'Almarai'], 'ar' => ['name' => 'المراعي']],
      ['en' => ['name' => 'Farm Fresh'], 'ar' => ['name' => 'فارم فريش']],
      ['en' => ['name' => 'Sun Fruit'], 'ar' => ['name' => 'صن فروت']],
    ];

    foreach ($brands as $data) {
      $brand = Brand::create($data);
      // $brand->addMedia(storage_path('app/public/' . $data['photo']))
            //     ->preservingOriginal()
            //     ->toMediaCollection('brands');
    }

    // Units
    $units = [
      ['en' => ['name' => 'Kilogram'], 'ar' => ['name' => 'كيلو جرام']],
      ['en' => ['name' => 'Piece'], 'ar' => ['name' => 'قطعة']],
      ['en' => ['name' => 'Box'], 'ar' => ['name' => 'صندوق']],
    ];
    foreach ($units as $data) {
      Unit::create($data);
    }


    // Products
    $products = [
      ['category_id' => 3, 'brand_id' => 3, 'unit_id' => 1, 'en' => ['title' => 'Orange'], 'ar' => ['title' => 'برتقال'], 'price' => 2.5, 'discount' => 0.1, 'current_stock_quantity' => 100, 'min_order_quantity' => 1, 'max_order_quantity' => 50, 'min_storage_quantity' => 10, 'max_storage_quantity' => 500],
      ['category_id' => 3, 'brand_id' => 2, 'unit_id' => 1, 'en' => ['title' => 'Apple'], 'ar' => ['title' => 'تفاح'], 'price' => 3.0, 'discount' => 0.2, 'current_stock_quantity' => 150, 'min_order_quantity' => 2, 'max_order_quantity' => 40, 'min_storage_quantity' => 20, 'max_storage_quantity' => 400],
      ['category_id' => 4, 'brand_id' => 2, 'unit_id' => 1, 'en' => ['title' => 'Spinach'], 'ar' => ['title' => 'سبانخ'], 'price' => 1.8, 'discount' => 0.05, 'current_stock_quantity' => 200, 'min_order_quantity' => 5, 'max_order_quantity' => 30, 'min_storage_quantity' => 50, 'max_storage_quantity' => 300],
      ['category_id' => 4, 'brand_id' => 1, 'unit_id' => 1, 'en' => ['title' => 'Tomato'], 'ar' => ['title' => 'طماطم'], 'price' => 1.2, 'discount' => 0.15, 'current_stock_quantity' => 180, 'min_order_quantity' => 3, 'max_order_quantity' => 60, 'min_storage_quantity' => 15, 'max_storage_quantity' => 450],
      ['category_id' => 4, 'brand_id' => 1, 'unit_id' => 1, 'en' => ['title' => 'Lettuce'], 'ar' => ['title' => 'خس'], 'price' => 1.5, 'discount' => 0.1, 'current_stock_quantity' => 120, 'min_order_quantity' => 2, 'max_order_quantity' => 20, 'min_storage_quantity' => 10, 'max_storage_quantity' => 200],
    ];

    foreach ($products as $data) {
      $product = Product::create($data);
      // $product->addMedia(storage_path('app/public/' . $data['photo']))
            //     ->preservingOriginal()
            //     ->toMediaCollection('products');
    }
  }
}
