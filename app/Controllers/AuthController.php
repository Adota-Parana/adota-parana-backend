<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;
use App\Models\User;

class AuthController
{
    public function showRegistrationForm(): void
    {
        $this->view('auth/register');
    }

    public function register(Request $request)
    {
        $user = new User();

        // Popula os dados do form
        $user->name = trim($request->getParam('name') ?? '');
        $user->email = trim($request->getParam('email') ?? '');
        $user->phone = trim($request->getParam('phone') ?? '');
        $user->password = trim($request->getParam('password') ?? '');
        $user->password_confirmation = trim($request->getParam('password_confirmation') ?? '');
        $user->role = 'user';

        // Valida o usuário
        if (!$user->isValid()) {
            // Se houver erros, retorna para o form
            return $this->view('auth/register', [
                'user' => $user,
                'errors' => $user->errors
            ]);
        }

        // Salva no banco
        if ($user->save()) {
            FlashMessage::success('Registro realizado com sucesso! Efetue o login.');
            header('Location: /login');
            exit;
        } else {
            FlashMessage::danger('Erro ao registrar usuário.');
            return $this->view('auth/register', [
                'user' => $user,
                'errors' => $user->errors
            ]);
        }
    }


    public function showLoginForm(): void
    {
     $this->view('auth/login', ['title' => 'login']);
    }

    public function login(Request $request): void
    {
        $email = $request->getParam('email');
        $password = $request->getParam('password');

        if (empty($email) || empty($password)) {
            FlashMessage::danger('Preencha todos os campos.');
            header('Location: /login');
            exit();
        }
        
        $user = User::findByEmail($email);
        
        if ($user && $user->authenticate($password)) {
            Auth::login($user);
            FlashMessage::success('Login realizado com sucesso!');

            if($user->role === 'admin'){
                header('Location: /admin/dashboard');
            } else {
                header('Location: /user/dashboard');
            }

        } else {
            FlashMessage::danger('Credenciais inválidas.');
            header('Location: /login');
        }
    }

    public function logout(): void
    {
        Auth::logout();
        FlashMessage::success('Você saiu da sua conta.');
        header('Location: /');
        exit;
    }

    protected function view(string $viewName, array $data = []): void
{
    $view = __DIR__ . '/../views/' . $viewName . '.php';
    extract($data);
    require __DIR__ . '/../views/layouts/application.phtml';
}
}
