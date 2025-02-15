<?php

namespace Database\Seeders;

use App\Constants\AppSettingTypes;
use App\Models\AppSetting;
use App\Models\AppSettingTranslation;
use Illuminate\Database\Seeder;

class AppSettingsSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    $data = [
      [
        'key' => 'terms and conditions',
        'type' => AppSettingTypes::STRING,
        'has_translation' => true,
        'translations' => [
          'en' => [
            'value' => 'terms and conditions',
          ],
          'ar' => [
            'value' => 'الشروط والاحكام',
          ],
        ],

      ],
      [
        'key' => 'about us',
        'type' => AppSettingTypes::STRING,
        'has_translation' => true,
        'translations' => [
          'en' => [
            'value' => 'about us',
          ],
          'ar' => [
            'value' => 'عن المشروع',
          ],
        ],
      ],
      [
        'key' => 'privacy policy',
        'type' => AppSettingTypes::STRING,
        'has_translation' => true,
        'translations' => [
          'en' => [
            'value' => 'privacy policy',
          ],
          'ar' => [
            'value' => 'سياسة الخصوصية',
          ],
        ],
      ],
    ];
    foreach ($data as $item) {
      $appSetting = AppSetting::create([
        'key' => $item['key'],
        'type' => $item['type'],
        'has_translation' => $item['has_translation'],
      ]);
      if ($item['has_translation']) {
        foreach ($item['translations'] as $key => $value) {
          AppSettingTranslation::create([
            'app_setting_id' => $appSetting->id,
            'locale' => $key,
            'value' => $value['value'],
          ]);
        }
      } else {
        $appSetting->value()->create([
          'value' => $item['value'],
        ]);
      }
    }

    // AppSetting::factory()->create([
        //     'key' => 'about us',
        //     'type' => AppSettingTypes::STRING,
    // ]);

    // AppSetting::factory()->create([
        //     'key' => 'currency',
        //     'value' => 'USD',
    // ]);
  }
}
