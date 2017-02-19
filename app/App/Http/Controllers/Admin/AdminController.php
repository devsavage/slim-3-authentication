<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function get()
    {
        return $this->render('admin/home');
    }
}
