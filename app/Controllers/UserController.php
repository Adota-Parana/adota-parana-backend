<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;

class UserController
{
    public function index(): string
    {     
       return $this->view('user/dashboard');
    }

    public function editProfile()
    {
        $user = \App\Services\Auth::user();

        return $this->view('user/profile/edit', ['user' => $user]);
    }

    public function updateProfile(\Core\Http\Request $request)
    {
        $user = \App\Services\Auth::user();

        $user->name = $request->post('name');
        $user->email = $request->post('email');
        $user->phone = $request->post('phone');

        if ($user->save()) {
            FlashMessage::success('Perfil atualizado!');
            return $this->view('user/dashboard', ['user' => $user]);
        } 
        
        else {
            FlashMessage::error('Erro ao atualizar perfil!');
            return $this->view('user/profile/edit', ['user' => $user]);
        }
    }



    protected function view(string $path, array $data = []): string
    {
        extract($data);
        $user = \App\Services\Auth::user(); 
        return include __DIR__ . "/../views/{$path}.phtml";
    }
}
