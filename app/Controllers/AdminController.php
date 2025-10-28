<?php

namespace App\Controllers;

use Core\Http\Request;
use Core\Http\Controllers\Controller;
use App\Services\Auth;
use App\Models\User;
use App\Models\User;
use PDOException;
use Lib\FlashMessage;
use Core\Http\Controllers\Controller;

class AdminController extends Controller
class AdminController extends Controller
{
    public function index(Request $request): void
    {
        $users = User::all();
        $this->render('admin/index', ['users' => $users]);
    }
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


    public function usersDelete(Request $request): void
    {
        $id = (int) $request->getParam('id');
        $user = User::findById($id);

        if ($userToDelete && $userToDelete->id != Auth::user()->id) {
            $userToDelete->destroy();
            FlashMessage::success('Usuário deletado com sucesso!');
        } else {
            FlashMessage::danger('Não é possível deletar seu próprio usuário!');
        }

        $this->redirectTo('/admin/dashboard');
    }
}