<?php

namespace Database\Seeders;

use App\Constants\AppSettingTypes;
use App\Models\AppSetting;
use App\Models\AppSettingTranslation;
use App\Models\AppSettingValue;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AppSettingsSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    $data = [
      [
        'key' => 'maximum order amount',
        'type' => AppSettingTypes::INTEGER,
        'has_translation' => false,
        'is_deletable' => false,
        'is_key_editable' => false,
        'value' => 500000,
      ],
      [
        'key' => 'minimum order amount',
        'type' => AppSettingTypes::INTEGER,
        'has_translation' => false,
        'is_deletable' => false,
        'is_key_editable' => false,
        'value' => 500,
      ],
      [
        'key' => 'maximum investor order amount',
        'type' => AppSettingTypes::INTEGER,
        'has_translation' => false,
        'is_deletable' => false,
        'is_key_editable' => false,
        'value' => 500000,
      ],
      [
        'key' => 'minimum investor order amount',
        'type' => AppSettingTypes::INTEGER,
        'has_translation' => false,
        'is_deletable' => false,
        'is_key_editable' => false,
        'value' => 5000,
      ],
    ];
    foreach ($data as $item) {
      try {
        // Update or create the app setting
        $appSetting = AppSetting::updateOrCreate(
          ['key' => $item['key']],
          [
            'type' => $item['type'],
            'has_translation' => $item['has_translation'],
            'is_deletable' => $item['is_deletable'],
            'is_key_editable' => $item['is_key_editable'],
          ],
        );
        echo $appSetting->key;

        // Handle translations if applicable
        if (!empty($item['translations']) && $item['has_translation']) {
          foreach ($item['translations'] as $locale => $translation) {
            AppSettingTranslation::updateOrCreate(
              [
                'app_setting_id' => $appSetting->id,
                'locale' => $locale,
              ],
              [
                'value' => $translation['value'],
              ],
            );
          }
        } elseif (isset($item['value'])) {
          AppSettingValue::updateOrCreate(
            [
              'app_setting_id' => $appSetting->id,
            ],
            [
              'value' => $item['value'],
            ],
          );
        }
      } catch (\Exception $e) {
        Log::error('Error seeding app setting: ' . $item['key'], ['error' => $e->getMessage()]);
      }
    }
  }
}
