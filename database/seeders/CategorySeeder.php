<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    // $categories = [
        //     [
        //         'translations' => [
        //             'en' => ['title' => 'Electronics', 'description' => 'Electronics Description'],
        //             'ar' => ['title' => 'الإلكترونيات', 'description' => 'الإلكترونيات الوصف'],
        //         ],
        //         'parent_id' => null,
        //     ],
        //     [
        //         'translations' => [
        //             'en' => [
        //                 'title' => 'Books',
        //                 'description' => 'Books Description',
        //             ],
        //             'ar' => [
        //                 'title' => 'الكتب',
        //                 'description' => 'الكتب الوصف',
        //             ],
        //         ],
        //         'parent_id' => null,
        //     ],

    // ];

    $categories = [
      [
        'en' => [
          'title' => 'Title 1 En',
          'description' => 'Description 1 En',
        ],
        'ar' => [
          'title' => 'Title 1 Ar',
          'description' => 'Description 1 Ar',
        ],
        'parent_id' => null,
      ],
      [
        'en' => [
          'title' => 'Title 2 En',
          'description' => 'Description 2 En',
        ],
        'ar' => [
          'title' => 'Title 2 Ar',
          'description' => 'Description 2 Ar',
        ],
        'parent_id' => null,
      ],
      [
        'en' => [
          'title' => 'Title 3 En',
          'description' => 'Description 3 En',
        ],
        'ar' => [
          'title' => 'Title 3 Ar',
          'description' => 'Description 3 Ar',
        ],
        'parent_id' => 1,
      ],
    ];

    foreach ($categories as $category) {
      // dd($category);
      Category::query()->create($category);
    }
  }
}
