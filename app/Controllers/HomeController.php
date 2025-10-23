<?php

namespace App\Controllers;

use Core\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index');
    }

    protected function view(string $viewName, array $data = []): void
    {
        $view = __DIR__ . '/../views/' . $viewName . '.phtml';
        extract($data);
        require __DIR__ . '/../views/layouts/application.phtml';
    }
}
