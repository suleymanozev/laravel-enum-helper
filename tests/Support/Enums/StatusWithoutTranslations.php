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
enum StatusWithoutTranslations
{
    use LaravelEnumHelper;

    case PENDING;

    case ACCEPTED;

    case DISCARDED;

    case NO_RESPONSE;
}
