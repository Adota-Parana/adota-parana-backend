<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;

class UserController
{
    public function index(): void
    {     
        $user = \App\Services\Auth::user();
        $this->view('user/dashboard', ['user' => $user]);
    }

    public function edit()
    {
        $user = \App\Services\Auth::user();

        return $this->view('user/profile/edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = \App\Services\Auth::user();

        $user->name  = trim($request->post('name'));
        $user->email = trim($request->post('email'));
        $user->phone = trim($request->post('phone'));

        if (!$user->isValid()) {
            return $this->view('user/profile/edit', [
                'user' => $user,
                'errors' => $user->errors
            ]);
        }

        if ($user->save()) {
            FlashMessage::success('Perfil atualizado!');
            return $this->view('user/dashboard', ['user' => $user]);
        } else {
            FlashMessage::danger('Erro ao atualizar perfil!');
            return $this->view('user/dashboard', ['user' => $user]);
        }
    }



    protected function view(string $viewName, array $data = []): void
{
    $view = __DIR__ . '/../views/' . $viewName . '.php';
     extract($data);
    require __DIR__ . '/../views/layouts/application.phtml';
}
}
