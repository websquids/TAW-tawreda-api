<?php

namespace Illuminate\Contracts\Routing {

  use Illuminate\Http\JsonResponse;

  /**
   * @method JsonResponse apiResponse(mixed $payload = [], string $message = 'Operation successful', bool $status = true, int $statusCode = 200)
   */
  interface ResponseFactory {
  }
}
