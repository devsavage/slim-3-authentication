<?php

namespace App\Validation\Violin\Support;

use RecursiveArrayIterator;
use RecursiveIteratorIterator;

use App\Validation\Violin\Contracts\MessageBagContract;

class MessageBag implements MessageBagContract
{
    /**
     * The registered messages.
     *
     * @var array
     */
    protected $messages = [];

    /**
     * Creates a new instange of the MessageBag instance.
     *
     * @param array $messages
     * @return void
     */
    public function __construct(array $messages)
    {
        foreach ($messages as $key => $value) {
            $this->messages[$key] = (array) $value;
        }
    }

    /**
     * Checks if the bag has messages for a given key.
     *
     * @param  string  $key
     * @return boolean
     */
    public function has($key)
    {
        return ! is_null($this->first($key));
    }

    /**
     * Get the first message with a given key.
     * If the given key doesn't exist, it returns the first
     * message of the bag.
     * Returns null if the bag is empty.
     *
     * @param  string $key
     * @return string|null
     */
    public function first($key = null)
    {
        $messages = is_null($key) ? $this->all() : $this->get($key);
        return ($messages && count($messages) > 0) ? $messages[0] : null;
    }

    /**
     * Get all of the messages from a given key.
     * Returns null if the given key is empty, or
     * if it doesn't exist.
     *
     * @param  string $key
     * @return array|null
     */
    public function get($key)
    {
        if (array_key_exists($key, $this->messages)) {
            return !empty($this->messages[$key]) ? $this->messages[$key] : null;
        }

        return null;
    }

    /**
     * Get all of the messages in the bag.
     *
     * @return array
     */
    public function all()
    {
        return iterator_to_array(new RecursiveIteratorIterator(
            new RecursiveArrayIterator($this->messages)
        ), false);
    }

    /**
     * Return all of the keys in the bag.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->messages);
    }
}
