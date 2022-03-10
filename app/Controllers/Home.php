<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'script' => 'js/pages/login/login.js'
        ];

        return view('/pages/login/index', $data);
    }

    public function prueba(){
        return view('/pages/login/index');
    }

    public function login(){
        return view('/pages/login/index');
    }
}
