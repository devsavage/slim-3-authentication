<?php

namespace Savage\Http\Controllers;

class HomeController extends Controller
{
    public function get()
    {
        return $this->render('home');
    }
}
