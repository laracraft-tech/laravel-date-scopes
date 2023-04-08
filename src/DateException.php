<?php

namespace LaracraftTech\LaravelDateScopes;

use InvalidArgumentException;

final class DateException extends InvalidArgumentException
{
    public static function invalidValue()
    {
        throw new self('The $value parameter must be a positive integer.');
    }
}
