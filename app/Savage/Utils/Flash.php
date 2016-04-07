<?php

namespace Savage\Utils;

/**
 * Flash extends Slim\Flash\Messages and implements a way to flash messages with the current request.
 */
class Flash extends \Slim\Flash\Messages
{
    protected $forNow = [];

    public function addMessageNow($key, $message)
    {
        if(!isset($this->forNow[$key])) {
            $this->forNow[$key] = [];
        }

        $this->forNow[$key][] = $message;
    }

    public function getMessages()
    {
        $messages = $this->fromPrevious;

        foreach ($this->forNow as $key => $values) {
            if(!isset($messages[$key])) {
                $messages[$key] = [];
            }

            foreach ($values as $value) {
                array_push($messages[$key], $value);
            }
        }

        return $messages;
    }

    public function getMessage($key)
    {
        $messages = $this->getMessages();
        return (isset($messages[$key])) ? $messages[$key] : null;
    }
}
