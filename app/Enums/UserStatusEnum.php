<?php

namespace App\Enums;

use Spatie\Enum\Laravel\Enum;

/**
 * @method statis self user()
 * @method statis self vendor()
 */

final class UserStatusEnum extends Enum
{
    protected static function labels()
    {
        return [
            'user' => 'user',
            'vendor' => 'vendor'
        ];
    }
}
