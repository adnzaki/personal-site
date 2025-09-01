<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $content = [
            'hero'      => view('home/hero'),
            'wrapper'   => view('home/wrapper'),
        ];

        $data = [
            'content' => implode('', $content),
        ];

        return view('layout/main', $data);
    }
}
