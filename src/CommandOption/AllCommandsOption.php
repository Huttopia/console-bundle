<?php

declare(strict_types=1);

namespace Huttopia\ConsoleBundle\CommandOption;

class AllCommandsOption
{
    public static function parseAllCommandsOption(array &$argv): bool
    {
        $allCommandsKey = array_search('--all-commands', $argv);
        if (is_int($allCommandsKey) || is_string($allCommandsKey)) {
            unset($argv[$allCommandsKey]);
            if (array_key_exists($allCommandsKey, $_SERVER['argv'])) {
                unset($_SERVER['argv'][$allCommandsKey]);
            }
            $return = true;
        }

        return $return ?? false;
    }
}
