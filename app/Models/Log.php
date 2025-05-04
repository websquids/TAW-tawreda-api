<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model {
  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'level',        // Log level (info, warning, error, etc.)
    'message',
    'context',
    'ip_address',
    'user_agent',
    'user_id',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'context' => 'array',
  ];

  /**
   * Define the relationship with the User model.
   */
  public function user() {
    return $this->belongsTo(User::class);
  }
}
