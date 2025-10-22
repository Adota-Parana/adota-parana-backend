<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;
use App\Models\User;

class AdminController
{
    public function index()
    {
        $users = User::all();
        $this->view('admin/index', ['users' => $users]);
    }

    public function usersDelete(Request $request, int $id)
    {
        $userToDelete = User::find($id);
        $currentUser = Auth::user();

        if (!$userToDelete) {
            FlashMessage::danger('Usuário não encontrado.');
            header('Location: /admin/dashboard');
            return;
        }

        if ($userToDelete->id === $currentUser->id) {
            FlashMessage::danger('Você não pode excluir seu próprio usuário.');
            header('Location: /admin/dashboard');
            return;
        }

        if ($userToDelete->delete()) {
            FlashMessage::success('Usuário excluído com sucesso!');
        } else {
            FlashMessage::danger('Ocorreu um erro ao excluir o usuário.');
        }

        header('Location: /admin/dashboard');
        return;
    }

    protected function view(string $viewName, array $data = []): void
    {
        $view = __DIR__ . '/../views/' . $viewName . '.phtml';
        extract($data);
        require __DIR__ . '/../views/layouts/application.phtml';
    }
}
