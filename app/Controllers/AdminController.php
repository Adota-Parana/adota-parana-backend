<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Controllers\Controller;
use App\Services\Auth;
use App\Models\User;
use Lib\FlashMessage;

class AdminController extends Controller
{
    public function index(Request $request): void
    {
        $page = (int) $request->getParam('page', 1);
        
        $paginator = User::paginate(
            route: 'admin.paginated',
            page: $page,
            per_page: 10
        );
        
        $users = $paginator->registers();

        if ($request->acceptJson()) {
            $this->renderJson('index', compact('paginator', 'users'));
        } else {
            $this->render('admin/index', compact('paginator', 'users'));
        }
    }


    public function usersDelete(Request $request)
    {
        $id = (int) $request->getParam('id');
        $userToDelete = User::findBy(['id' => $id]);

        if ($userToDelete && $userToDelete->id != Auth::user()->id) {
            $userToDelete->destroy();
            FlashMessage::success('Usuário deletado com sucesso!');
        } else {
            FlashMessage::danger('Não é possível deletar seu próprio usuário!');
        }

        $this->redirect('/admin/dashboard');
    }
}