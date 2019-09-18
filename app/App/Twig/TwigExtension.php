<?php

namespace App\Twig;

class TwigExtension extends \Twig\Extension\AbstractExtension
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return 'app';
    }

    public function getFunctions()
    {
        return [
            new \Twig\TwigFunction('asset', [$this, 'asset']),
            new \Twig\TwigFunction('getenv', [$this, 'getenv']),
            new \Twig\TwigFunction('config', [$this, 'config']),
            new \Twig\TwigFunction('pagination_url', [$this, 'pagination_url']),
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

    public function config($key)
    {
        return $this->container->config->get($key);
    }

    public function pagination_url($path,$url)
    {

        return $path.ltrim($url, '/');
    }
}
