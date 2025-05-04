<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Twilio\Rest\Client;

class TwilioService {
  // Add your methods here
  public function sendWhatsApp($to, $message) {
    // Twilio WhatsApp sending logic goes here
    // You can use the Twilio PHP SDK or any other WhatsApp service provider
    // Example:
    // $twilio = new TwilioClient($accountSid, $authToken);
    // $twilio->messages->create($to, ['from' => $from, 'body' => $message]);
  }
  public static function sendSMS($phone, $message) {
    try {
      $formattedPhone = self::formatEgyptianNumber($phone);

      if (!$formattedPhone) {
        Log::warning("Invalid phone number format: " . $phone);
        return;
      }

      $client = new Client(env("TWILIO_SID"), env("TWILIO_TOKEN"));
      $client->messages->create($formattedPhone, [
        "from" => env("TWILIO_FROM"),
        "body" => $message,
      ]);
      // $twilio->message($phone, $message);
    } catch (\Throwable $th) {
      Log::warning("Error Sending SMS: " . $th->getMessage());
    }
  }

  private static function formatEgyptianNumber($phone) {
    // Remove all non-digit characters
    $phone = preg_replace('/\D/', '', $phone);

    // Case 1: Starts with "1" only (missing 0), add "0" then country code
    if (preg_match('/^1[0-9]{9}$/', $phone)) {
      return '+2' . '0' . $phone;
    }

    // Case 2: Starts with "01", convert to "+201"
    if (preg_match('/^01[0-9]{8}$/', $phone)) {
      return '+2' . $phone;
    }

    // Case 3: Starts with "201", just add "+"
    if (preg_match('/^201[0-9]{8}$/', $phone)) {
      return '+' . $phone;
    }

    // Case 4: Already in correct format
    if (preg_match('/^\+201[0-9]{8}$/', $phone)) {
      return $phone;
    }

    return false; // Invalid format
  }
}
