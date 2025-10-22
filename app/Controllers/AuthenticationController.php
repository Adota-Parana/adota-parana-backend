<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;
use App\Models\User;
use Core\Http\Controllers\Controller;

class AuthenticationController extends Controller
{
    public function showRegistrationForm(Request $request): void
    {
        $this->render('auth/Register');
    }

    public function register(Request $request): void
    {
        $user = new User();
        $user->name = $request->getParam('name');
        $user->email = $request->getParam('email');
        $user->phone = $request->getParam('phone');
        $user->password = $request->getParam('password');
        $user->password_confirmation = $request->getParam('password_confirmation');
        $user->role = 'user';


        if ($user->save()) {
            FlashMessage::success('Registro realizado com sucesso! Efetue o login.');
            $this->redirectTo(route('/login'));
        } else {
            $errors = $user->getErrors();
            FlashMessage::danger(implode("<br>", $errors));
            $this->redirectTo(route('/register'));
        }
    }

    public function showLoginForm(Request $request): void
    {
        require __DIR__ . '/../views/auth/Login.phtml';
    }

    public function login(Request $request): void
    {
        $email = $request->getParam('email');
        $password = $request->getParam('password');

        if (empty($email) || empty($password)) {
            FlashMessage::danger('Preencha todos os campos.');
            $this->redirectTo(route('/login'));
            exit();
        }
        
        $user = User::findByEmail($email);
        
        if ($user && $user->authenticate($password)) {
            Auth::login($user);
            FlashMessage::success('Login realizado com sucesso!');

            if($user->role === 'admin'){
                $this->redirectTo(route('/admin/dashboard'));
            } else {
                $this->redirectTo(route('/'));
            }

        } else {
            FlashMessage::danger('Credenciais inválidas.');
            $this->redirectTo(route('/login'));
        }
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        FlashMessage::success('Você saiu da sua conta.');
        $this->redirectTo(route('/login'));
        exit;
    }
}
