<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class resetPassword extends Model {
  protected $table = 'reset_passwords';
  protected $fillable = ['identifier', 'identifier_type', 'token'];


  public function scopeByIdentifier($query, $identifier) {
    return $query->where('identifier', $identifier);
  }

  public function scopeByIdentifierType($query, $type) {
    return $query->where('identifier_type', $type);
  }
}
