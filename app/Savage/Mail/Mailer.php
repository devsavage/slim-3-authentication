<?php
namespace Savage\Mail;


class Mailer
{
    protected $mailer, $container, $view;

    public function __construct($mailer, $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
        $this->view = $container->twig;
    }

    public function send($template, $data, $callback)
    {
        $message = new Message($this->mailer);

        $message->body($this->view->render($template, [
            'data' => $data
        ]));

        call_user_func($callback, $message);

        $this->mailer->send();
    }
}
