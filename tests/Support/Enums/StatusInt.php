<?php

declare(strict_types=1);

namespace Suleymanozev\LaravelEnumHelper\Tests\Support\Enums;

use Suleymanozev\LaravelEnumHelper\LaravelEnumHelper;

/**
 * @method static string pending()
 * @method static string accepted()
 * @method static string discarded()
 * @method static string noResponse()
 */
enum StatusInt: int
{
    use LaravelEnumHelper;

    case PENDING = 0;

    case ACCEPTED = 1;

    case DISCARDED = 2;

    case NO_RESPONSE = 3;
}
