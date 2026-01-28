<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        echo view('plantilla/header');
        echo view('welcome_message');
        echo view('plantilla/footer');
    }
}
