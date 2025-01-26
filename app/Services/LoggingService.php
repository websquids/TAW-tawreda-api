<?php

namespace App\Services;

use App\Models\Log;

class LoggingService {
  /**
   * Log an action to the database.
   *
   * @param string $level
   * @param string $message
   * @param array $context
   * @param int|null $userId
   * @return void
   */
  public function logAction(string $level, string $message, array $context = [], ?int $userId = null): void {
    Log::create([
      'level' => $level,
      'message' => $message,
      'context' => $context,
      'user_id' => $userId,
      'ip_address' => request()->ip(),
      'user_agent' => request()->userAgent(),
    ]);
  }
}
