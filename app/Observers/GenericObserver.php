<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;
use App\Services\LoggingService;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;
use Illuminate\Support\Facades\Log;

class GenericObserver implements ShouldHandleEventsAfterCommit {
  protected $loggingService;

  /**
   * Constructor.
   */
  public function __construct(LoggingService $loggingService) {
    $this->loggingService = $loggingService;
  }

  /**
   * Handle the "created" event.
   */
  public function created($model): void {
    $this->logAction('created', $model);
  }

  /**
   * Handle the "updated" event.
   */
  public function updated($model): void {
    Log::info('Updated event called for: ' . class_basename($model), [
      'changes' => $model->getDirty(),
      'original' => $model->getOriginal(),
    ]);
    $changes = $model->getDirty();
    $original = $model->getOriginal();

    $formattedChanges = [];
    foreach ($changes as $key => $newValue) {
      $formattedChanges[$key] = [
        'old' => $original[$key] ?? null,
        'new' => $newValue,
      ];
    }

    $this->logAction('updated', $model, ['changes' => $formattedChanges]);
  }


  /**
   * Handle the "deleted" event.
   */
  public function deleted($model): void {
    $this->logAction('deleted', $model);
  }

  /**
   * Handle the "restored" event.
   */
  public function restored($model): void {
    $this->logAction('restored', $model);
  }

  /**
   * Handle the "force deleted" event.
   */
  public function forceDeleted($model): void {
    $this->logAction('forceDeleted', $model);
  }

  /**
   * Log the action.
   */
  protected function logAction(string $action, $model, array $extraData = []): void {
    $data = array_merge([
      'action' => $action,
      'model' => class_basename($model),
      'model_id' => $model->id ?? null,
    ], $extraData);

    $this->loggingService->logAction(
      'info',
      ucfirst($action) . ' ' . class_basename($model),
      $data,
      Auth::id(),
    );
  }
}
