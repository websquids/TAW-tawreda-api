<?php

namespace App\Constants;

class AddressTypes
{
    public const USER = 'user';
    public const ORDER = 'order';

    public static $modelMapping = [
        self::USER => \App\Models\User::class,
        self::ORDER => \App\Models\Order::class,
    ];

    public static function getAllTypes()
    {
        return [
            self::USER,
            self::ORDER,
        ];
    }
}
