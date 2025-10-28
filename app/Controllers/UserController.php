<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;
use Core\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(): void
    {     
        $user = Auth::user();
        $this->render('user/dashboard', ['user' => $user]);
    }

    public function edit()
    {
        $user = \App\Services\Auth::user();
        $this->render('user/profile/edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $user = \App\Services\Auth::user();

        $user->name  = trim($request->post('name'));
        $user->email = trim($request->post('email'));
        $user->phone = trim($request->post('phone'));

        if (!$user->isValid()) {
            return $this->render('user/profile/edit', [
                'user' => $user,
                'errors' => $user->errors
            ]);
        }

        if ($user->save()) {
            FlashMessage::success('Perfil atualizado!');
            return $this->render('user/dashboard', ['user' => $user]);
        } else {
            FlashMessage::danger('Erro ao atualizar perfil!');
            return $this->render('user/dashboard', ['user' => $user]);
        }
    }

}

