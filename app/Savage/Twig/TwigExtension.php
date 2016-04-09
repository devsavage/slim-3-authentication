<?php

namespace Savage\Twig;

class TwigExtension extends \Twig_Extension
{
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getName()
    {
        return 'savage';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('asset', [$this, 'asset']),
        ];
    }

    public function asset($location)
    {
        return $this->container['settings']['baseUrl'] . $location;
    }
}
