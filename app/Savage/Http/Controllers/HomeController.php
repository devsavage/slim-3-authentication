<?php
namespace Savage\Http\Controllers;

/**
 * HomeController handles our '/' route.
 */

class HomeController extends Controller
{
    public function get()
    {
        return $this->render('home');
    }

    public function getTest()
    {
        $this->mail()->send('/email/test.twig', ['abc' => 123], function($message) {
            $message->to('test@example.com');
            $message->subject('Test Message');
        });

        return "Tested";
    }
}
