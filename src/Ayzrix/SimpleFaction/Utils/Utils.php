<?php

namespace Ayzrix\SimpleFaction\Utils;

use Ayzrix\SimpleFaction\Main;

class Utils {

    /**
     * @return string
     */
    public static function getPrefix(): string {
        return self::getIntoConfig("Prefix");
    }

    /**
     * @param string $text
     * @param array $args
     * @return string
     */
    public static function getConfigMessage(string $text, array $args = array()): string {
        $config = Main::getInstance()->getConfig();
        $message = $config->get($text);
        if (!empty($args)) {
            foreach ($args as $arg) {
                $message = preg_replace("/[%]/", $arg, $message, 1);
            }
        }
        $message = str_replace('{prefix}', self::getPrefix(), $message);
        return $message;
    }

    /**
     * @param string $value
     * @return bool|string|int|array
     */
    public static function getIntoConfig(string $value) {
        $config = Main::getInstance()->getConfig();
        return $config->get($value);
    }
}