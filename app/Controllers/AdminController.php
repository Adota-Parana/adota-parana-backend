<?php

namespace App\Controllers;

use Core\Http\Request;
use App\Models\User;
use PDOException;
use Lib\FlashMessage;
use Core\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $this->render('admin/index', ['users' => $users]);
    }

    public function usersDelete(Request $request): void
    {
        $id = (int) $request->getParam('id');
        $user = User::findById($id);

        if (!$user) {
            FlashMessage::danger('Usuário não encontrado.');
            header('Location: /admin/dashboard');
            exit;
        }

        try {
            $user->destroy();
            FlashMessage::success('Usuário removido com sucesso.');
        } 
        catch (PDOException $e) {
            // Foreign key violation (cannot delete parent while children exist)
            if ((int)$e->getCode() === 23000 || stripos($e->getMessage(), '1451') !== false) {
                FlashMessage::danger('Não é possível excluir usuário: existem pets vinculados. Remova ou transfira os pets antes.');
            } 
            else {
                FlashMessage::danger('Erro ao remover usuário.');
            }
        }

        header('Location: /admin/dashboard');
        exit;
    }
}
