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

    public function edit()
    {
        $user = \App\Services\Auth::user();

        return $this->view('user/profile/edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = \App\Services\Auth::user();
        // Pega os dados do POST
        $name  = trim($request->post('name'));
        $email = trim($request->post('email'));
        $phone = trim($request->post('phone'));

        $errors = [];

        // Validação de nome
        if (empty($name)) {
            $errors['name'] = "O nome é obrigatório.";
        }

        // Validação de email
        if (empty($email)) {
            $errors['email'] = "O email é obrigatório.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "O email informado não é válido.";
        }

        // Validação de telefone
        if (empty($phone)) {
            $errors['phone'] = "O telefone é obrigatório.";
        } elseif (!preg_match('/^\+?[0-9\s\-]{8,15}$/', $phone)) {
            $errors['phone'] = "O telefone informado não é válido.";
        }

        if (!empty($errors)) {
            return $this->view('user/profile/edit', [
                'user' => $user,
                'errors' => $errors
            ]);
        }

        // Atualiza os dados
        $user->name  = $name;
        $user->email = $email;
        $user->phone = $phone;


    if ($user->save()) {
        FlashMessage::success('Perfil atualizado!');
        return $this->view('user/dashboard', ['user' => $user]);
    } else {
        FlashMessage::danger('Erro ao atualizar perfil!');
        return $this->view('user/dashboard', ['user' => $user]);
    }
    }



    protected function view(string $path, array $data = []): string
    {
        extract($data);
        $user = \App\Services\Auth::user(); 
        return include __DIR__ . "/../views/{$path}.phtml";
    }
}
