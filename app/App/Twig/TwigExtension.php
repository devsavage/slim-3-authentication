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
            new \Twig_SimpleFunction('env', [$this, 'env']),
        ];
    }

    public function asset($name)
    {
        return getenv('APP_URL') . '/' . $name;
    }

    public function env($what)
    {
        return getenv($what);
    }
}
