<?php

namespace Fiserv\Services;

class ConfigService
{
    private static array $config = [];

    public static function get(string $key)
    {
        if (isset(self::$config[$key])) {
            return self::$config[$key];
        }

        $parts = explode('.', $key);

        if (count($parts) !== 2) {
            throw new \Exception('Config key must be in format of "section.key"');
        }

        if (!is_file(__DIR__ . '/../../config/' . $parts[0] . '.php')) {
            return null;
        }

        $config = require __DIR__ . '/../../config/' . $parts[0] . '.php';

        if (isset($config[$parts[1]])) {
            self::$config[$key] = $config[$parts[1]];

            return $config[$parts[1]];
        } else {
            throw new \Exception('Config key not found');
        }
    }
}