<?php

namespace NormanHuth\Library\Lib;

use NormanHuth\Library\Commands\Development\ConsoleMakeCommand;
use NormanHuth\Library\Commands\Development\IdeHelperCommand;
use NormanHuth\Library\Commands\Development\PivotMigrateMakeCommand;

class CommandRegistry
{
    /**
     * Get development commands as an array.
     */
    public static function devCommands(): array
    {
        $commands = [
            ConsoleMakeCommand::class,
            PivotMigrateMakeCommand::class,
        ];

        if (class_exists('Barryvdh\LaravelIdeHelper\Generator')) {
            $commands[] = IdeHelperCommand::class;
        }

        return $commands;
    }
}
