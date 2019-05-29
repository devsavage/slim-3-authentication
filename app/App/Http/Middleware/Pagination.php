<?php

namespace App\Http\Middleware;

use App\Http\Middleware\Middleware;

class Pagination extends Middleware
{
    public function __invoke($request, $response, $next)
    {
        $view = $this->container->view;
        \Illuminate\Pagination\Paginator::viewFactoryResolver( function() use ( $view ) {
            return new class( $view ) {
                private $view;
                private $template;
                private $data;
                public function __construct( \Slim\Views\Twig $view )
                {
                    $this->view  = $view;
                }
                public function make( string $template, $data = null )
                {
                    $this->template = 'pagination.twig';
                    $this->data = $data;
                    return $this;
                }
                public function render()
                {
                    return $this->view->fetch($this->template, $this->data);
                }
            };
        });
        \Illuminate\Pagination\Paginator::currentPageResolver(function() use ($request) {
            return $request->getParam('page');
        });

        return $next( $request, $response ) ;
    }
}
