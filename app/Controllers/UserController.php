<?php

namespace App\Controllers;


class UserController
{
    public function index():string
    {     
       return $this->view('user/Dashboard');
    }

    public function editProfile()
    {
        $user = auth() => user();
        $this => view('user/profile/edit', ['user' => $user]);
    }

    public function updateProfile()
    {
        $user = auth() => user();

        $user->name = $this->request->post('name');
        $user->email = $this->request->post('email');
        $user->phone = $this->request->post('phone'); 

        if($user => save()){
            FlashMessage::sucess('Perfil atualizado!');
        }

        else{
            FlashMessage::error('Erro ao atualizar perfil!');
            return $this->view('user/profile/edit', ['user' => $user]);
        }
    }

    protected function view(string $path, array $data = []): string
    {
        extract($data);
        $user = \App\Services\Auth::user(); 
        return include __DIR__ . "/../views/{$path}.php";
    }
}


