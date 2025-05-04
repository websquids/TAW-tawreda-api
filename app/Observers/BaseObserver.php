<?php

namespace App\Observers;

use App\Services\LoggingService;
use Illuminate\Support\Facades\Auth;

abstract class BaseObserver {
  protected $loggingService;
  /**
   * Constructor.
   */
  public function __construct(LoggingService $loggingService) {
    $this->loggingService = $loggingService;
  }

  /**
   * Log the action.
   *
   * @param string $level
   * @param string $message
   * @param array $context
   * @param int|null $userId
   */
  protected function logAction(string $level, string $message, array $context, ?int $userId = null): void {
    $this->loggingService->logAction(
      $level,
      $message,
      $context,
      $userId ?? Auth::id(),
    );
  }

  /**
   * Handle the "created" event.
   *
   * @param object $model
   */
  public function created($model): void {
    $this->logAction(
      'info',
      'Created ' . class_basename($model),
      $model->toArray(),
      Auth::id() ?? null,
    );
  }

  /**
   * Handle the "updated" event.
   *
   * @param object $model
   */
  public function updated($model): void {
    $changes = $model->getDirty();
    $original = $model->getOriginal();

    $formattedChanges = [];
    foreach ($changes as $key => $newValue) {
      $formattedChanges[$key] = [
        'old' => $original[$key] ?? null,
        'new' => $newValue,
      ];
    }

    $this->logAction(
      'info',
      'Updated ' . class_basename($model),
      [
        'id' => $model->id,
        'changes' => $formattedChanges,
      ],
      Auth::id() ?? null,
    );
  }

  /**
   * Handle the "deleted" event.
   *
   * @param object $model
   */
  public function deleted($model): void {
    $this->logAction(
      'warning',
      'Deleted ' . class_basename($model),
      $model->toArray(),
      Auth::id() ?? null,
    );
  }

  /**
   * Handle the "restored" event.
   *
   * @param object $model
   */
  public function restored($model): void {
    $this->logAction(
      'info',
      'Restored ' . class_basename($model),
      $model->toArray(),
      Auth::id() ?? null,
    );
  }

  /**
   * Handle the "force deleted" event.
   *
   * @param object $model
   */
  public function forceDeleted($model): void {
    $this->logAction(
      'critical',
      'Permanently deleted ' . class_basename($model),
      $model->toArray(),
      Auth::id() ?? null,
    );
  }
}
