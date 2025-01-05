<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberType;

class ValidPhoneNumber implements ValidationRule {
  /**
   * Run the validation rule.
   *
   * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
   */
  public function validate(string $attribute, mixed $value, Closure $fail): void {
    if (preg_match('/\s/', $value)) {
      $fail(__('The :attribute must not contain spaces.'));
      return;
    }

    try {
      $phoneUtil = PhoneNumberUtil::getInstance();
      $phoneNumber = $phoneUtil->parse($value, 'EG');
      $numberType = $phoneUtil->getNumberType($phoneNumber);

      if (!$phoneUtil->isValidNumberForRegion($phoneNumber, 'EG') || $numberType !== PhoneNumberType::MOBILE) {
        $fail(__('The :attribute must be a valid Egyptian mobile number.'));
      }
    } catch (NumberParseException $e) {
      $fail(__('The :attribute must be a valid Egyptian mobile number.'));
    }
  }
}
