<?php

declare(strict_types=1);

namespace InternationsBehat\Context\API;

class SharedContext
{
    const TOKEN = 'TOKEN';

    private static $register;

    public static function set(string $key, $value)
    {
        self::$register[$key] = $value;
    }

    public static function get(string $key)
    {
        return self::$register[$key];
    }
}