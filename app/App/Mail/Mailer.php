<?php
namespace App\Mail;

use App\Mail\Mailtrap\Message;

class Mailer
{
    protected $mailer;
    protected $container;

    public function __construct($mailer, $container)
    {
        $this->mailer = $mailer;
        $this->container = $container;
    }

    public function send($template, $data, $callback)
    {
        $message = new Message($this->mailer);

        $message->body($this->container->twig->render($template, [
            'data' => $data
        ]));

        call_user_func($callback, $message);

        $this->mailer->send();
    }
}