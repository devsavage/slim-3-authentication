<?php

namespace App\Console\Traits;

trait Generatable
{
    protected $stubDirectory = __DIR__ . '/../stubs';

    public function generateStub($name, $replacements)
    {
        return str_replace(
            array_keys($replacements),
            $replacements,
            file_get_contents($this->stubDirectory . '/' . $name . '.stub')
        );
    }
}
