<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
      'name',
      'email',
      'phone',
      'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected static array $fields = [
      'name' => [
        'searchable' => true,
        'sortable' => true,
      ],
      'email' => [
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

    public static function getFields(): array
    {
        $instance = new static(); // Create an instance of the model
        $fields = self::$fields;
        return $fields;
    }

    protected $hidden = [
      'password',
      'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
          'email_verified_at' => 'datetime',
          'password' => 'hashed',
        ];
    }

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    public function fcmTokens()
    {
        return $this->hasMany(FcmToken::class);
    }
}
