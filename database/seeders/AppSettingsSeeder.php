<?php

namespace Database\Seeders;

use App\Constants\AppSettingTypes;
use App\Models\AppSetting;
use App\Models\AppSettingTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class AppSettingsSeeder extends Seeder {
  /**
   * Run the database seeds.
   */
  public function run(): void {
    $data = [
      [
        'key' => 'terms and conditions',
        'type' => AppSettingTypes::HTML,
        'has_translation' => true,
        'is_deletable' => false,
        'is_value_editable' => false,
        'translations' => [
          'en' => [
            'value' => '<div style="font-family: Arial, sans-serif; padding: 20px;">
                                    <h1 style="color: #333; font-size: 24px;">Terms and Conditions</h1>
                                    <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                        By using this app, you agree to the following terms and conditions...
                                    </p>
                                    <ul style="list-style-type: disc; padding-left: 20px;">
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">Do not misuse the app.</li>
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">Respect other users.</li>
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">Follow the community guidelines.</li>
                                    </ul>
                                </div>',
          ],
          'ar' => [
            'value' => '<div style="font-family: Arial, sans-serif; padding: 20px; direction: rtl;">
                                    <h1 style="color: #333; font-size: 24px;">الشروط والاحكام</h1>
                                    <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                        باستخدامك لهذا التطبيق، فإنك توافق على الشروط والأحكام التالية...
                                    </p>
                                    <ul style="list-style-type: disc; padding-left: 20px;">
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">لا تسيء استخدام التطبيق.</li>
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">احترم المستخدمين الآخرين.</li>
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">اتبع إرشادات المجتمع.</li>
                                    </ul>
                                </div>',
          ],
        ],
      ],
      [
        'key' => 'about us',
        'type' => AppSettingTypes::HTML,
        'has_translation' => true,
        'is_deletable' => false,
        'is_value_editable' => false,
        'translations' => [
          'en' => [
            'value' => '<div style="font-family: Arial, sans-serif; padding: 20px;">
                                    <h1 style="color: #333; font-size: 24px;">About Us</h1>
                                    <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                        We are a team of passionate developers working to create amazing apps...
                                    </p>
                                    <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                        Our mission is to make your life easier through technology.
                                    </p>
                                </div>',
          ],
          'ar' => [
            'value' => '<div style="font-family: Arial, sans-serif; padding: 20px; direction: rtl;">
                                    <h1 style="color: #333; font-size: 24px;">عن المشروع</h1>
                                    <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                        نحن فريق من المطورين المتحمسين الذين يعملون على إنشاء تطبيقات رائعة...
                                    </p>
                                    <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                        مهمتنا هي جعل حياتك أسهل من خلال التكنولوجيا.
                                    </p>
                                </div>',
          ],
        ],
      ],
      [
        'key' => 'privacy policy',
        'type' => AppSettingTypes::HTML,
        'has_translation' => true,
        'translations' => [
          'en' => [
            'value' => '<div style="font-family: Arial, sans-serif; padding: 20px;">
                                    <h1 style="color: #333; font-size: 24px;">Privacy Policy</h1>
                                    <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                        Your privacy is important to us. This policy outlines how we handle your data...
                                    </p>
                                    <ul style="list-style-type: disc; padding-left: 20px;">
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">We do not sell your data.</li>
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">We use encryption to protect your information.</li>
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">You have control over your data.</li>
                                    </ul>
                                </div>',
          ],
          'ar' => [
            'value' => '<div style="font-family: Arial, sans-serif; padding: 20px; direction: rtl;">
                                    <h1 style="color: #333; font-size: 24px;">سياسة الخصوصية</h1>
                                    <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                        خصوصيتك مهمة بالنسبة لنا. توضح هذه السياسة كيفية تعاملنا مع بياناتك...
                                    </p>
                                    <ul style="list-style-type: disc; padding-left: 20px;">
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">نحن لا نبيع بياناتك.</li>
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">نستخدم التشفير لحماية معلوماتك.</li>
                                        <li style="color: #666; font-size: 16px; margin-bottom: 10px;">لديك التحكم في بياناتك.</li>
                                    </ul>
                                </div>',
          ],
        ],
      ],
      [
        'key' => 'faqs',
        'type' => AppSettingTypes::HTML,
        'has_translation' => true,
        'is_deletable' => false,
        'is_value_editable' => false,
        'translations' => [
          'en' => [
            'value' => '<div style="font-family: Arial, sans-serif; padding: 20px;">
                                    <h1 style="color: #333; font-size: 24px;">Frequently Asked Questions</h1>
                                    <div style="margin-bottom: 20px;">
                                        <h2 style="color: #333; font-size: 20px;">How do I reset my password?</h2>
                                        <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                            To reset your password, go to the login page and click on "Forgot Password." Follow the instructions sent to your email.
                                        </p>
                                    </div>
                                    <div style="margin-bottom: 20px;">
                                        <h2 style="color: #333; font-size: 20px;">How can I contact support?</h2>
                                        <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                            You can contact support by emailing us at support@example.com or by calling +123456789.
                                        </p>
                                    </div>
                                    <div style="margin-bottom: 20px;">
                                        <h2 style="color: #333; font-size: 20px;">Is my data secure?</h2>
                                        <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                            Yes, we use industry-standard encryption to protect your data.
                                        </p>
                                    </div>
                                </div>',
          ],
          'ar' => [
            'value' => '<div style="font-family: Arial, sans-serif; padding: 20px; direction: rtl;">
                                    <h1 style="color: #333; font-size: 24px;">الأسئلة الشائعة</h1>
                                    <div style="margin-bottom: 20px;">
                                        <h2 style="color: #333; font-size: 20px;">كيف يمكنني إعادة تعيين كلمة المرور؟</h2>
                                        <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                            لإعادة تعيين كلمة المرور، انتقل إلى صفحة تسجيل الدخول وانقر على "نسيت كلمة المرور." اتبع التعليمات المرسلة إلى بريدك الإلكتروني.
                                        </p>
                                    </div>
                                    <div style="margin-bottom: 20px;">
                                        <h2 style="color: #333; font-size: 20px;">كيف يمكنني التواصل مع الدعم؟</h2>
                                        <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                            يمكنك التواصل مع الدعم عن طريق إرسال بريد إلكتروني إلى support@example.com أو الاتصال على +123456789.
                                        </p>
                                    </div>
                                    <div style="margin-bottom: 20px;">
                                        <h2 style="color: #333; font-size: 20px;">هل بياناتي آمنة؟</h2>
                                        <p style="color: #666; font-size: 16px; line-height: 1.5;">
                                            نعم، نستخدم تشفيرًا قياسيًا في الصناعة لحماية بياناتك.
                                        </p>
                                    </div>
                                </div>',
          ],
        ],
      ],
    ];
    foreach ($data as $item) {
      try {
        $appSetting = AppSetting::updateOrCreate(
          ['key' => $item['key']],
          [
            'type' => $item['type'],
            'has_translation' => $item['has_translation'],
          ],
        );

        if ($item['has_translation']) {
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
        } else {
          $appSetting->value()->updateOrCreate(
            ['app_setting_id' => $appSetting->id],
            ['value' => $item['value']],
          );
        }
      } catch (\Exception $e) {
        // Log the error and continue with the next item
        Log::error('Error processing app setting: ' . $item['key'], [
          'error' => $e->getMessage(),
        ]);
        continue;
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
