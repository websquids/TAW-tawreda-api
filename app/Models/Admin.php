<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Admin extends Model {
  use HasApiTokens;
  protected $fillable = ['first_name', 'last_name', 'email', 'password',];
  protected $hidden = ['password', 'remember_token',];
}
