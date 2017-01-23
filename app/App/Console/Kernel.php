<?php
namespace App\Console;

class Kernel
{
    protected $commands = [
        
    ];

    protected $defaultCommands = [
        \App\Console\Commands\Generator\ControllerGeneratorCommand::class,
        \App\Console\Commands\Generator\MiddlewareGeneratorCommand::class,
    ];

    public function getCommands()
    {
        return array_merge($this->commands, $this->defaultCommands);
    }
}
