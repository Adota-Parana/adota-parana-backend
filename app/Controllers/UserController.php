<?php

namespace App\Controllers;

class UserController
{
    public function index():string
    {     
       return $this->view('user/Dashboard');
    }


    protected function view(string $path, array $data = []): string
    {
        extract($data); // transforma array em variáveis
        $user = \App\Services\Auth::user(); 
        return include __DIR__ . "/../views/{$path}.php";
    }
}


