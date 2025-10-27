<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use App\Services\Auth;
use Lib\FlashMessage;

class Authenticate implements Middleware
{
    public function handle(Request $request): void
    {
        if (!Auth::user()) {
            FlashMessage::danger('Você deve estar logado para acessar essa página');
            header('Location: /');
            exit;
        }
    }
}
