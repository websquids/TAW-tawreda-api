<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model {
  use HasFactory;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'type',
    'user_id',
    'total',
  ];

  protected static array $fields = [
    'type' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'total' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'status' => [
      'searchable' => true,
      'sortable' => true,
    ],
    'created_at' => [
      'searchable' => false,
      'sortable' => true,
    ],
    'updated_at' => [
      'searchable' => false,
      'sortable' => true,
    ],
  ];
  public static function getFields(): array {
    $fields = self::$fields;
    return $fields;
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function cartItems() {
    return $this->hasMany(CartItem::class);
  }
}
