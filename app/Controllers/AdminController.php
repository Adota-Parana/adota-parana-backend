<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;
use App\Models\User;

class AdminController
{
    public function index(): void
    {
        $users = User::all();
        $this->view('admin/index', ['users' => $users]);
    }

    public function usersDelete(Request $request)
    {
        $id = (int) $request->getParam('id');
        $userToDelete = User::findById($id);

        if ($userToDelete && $userToDelete->id != Auth::user()->id) {
            $userToDelete->destroy();
            FlashMessage::success('Usuário deletado!');
        } 
        else 
        {
            FlashMessage::danger('Não é possível deletar seu próprio usuário!');
        }

        return header('Location: /admin/dashboard');
    }

    protected function view(string $viewName, array $data = []): void
    {
        $view = __DIR__ . '/../views/' . $viewName . '.php';
        extract($data);
        require __DIR__ . '/../views/layouts/application.phtml';
    }
}
