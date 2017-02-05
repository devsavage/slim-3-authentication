<?php
namespace App\Twig;

class TwigExtension extends \Twig_Extension
{
    protected $container;

    public function getName()
    {
        return 'app';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('asset', [$this, 'asset']),
            new \Twig_SimpleFunction('getenv', [$this, 'getenv']),
        ];
    }

    public function asset($name)
    {
        return env('APP_URL') . '/' . $name;
    }

    public function getenv($key, $default = null)
    {
        return env($key, $default);
    }
}
