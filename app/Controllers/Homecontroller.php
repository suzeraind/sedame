<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Attributes\Route;



class Homecontroller extends Controller
{

    #[Route('GET', '/')]
    #[Route('GET', '/home')]
    public function index()
    {
        $data = [
            'title' => 'Главная — Мой сайт',
            'active_page' => 'home',
            'posts' => [
                ['title' => 'Первай', 'desc' => 'Про PHP и шаблоны'],
                ['title' => 'Вторая статья', 'desc' => 'Как работает автозагрузка'],
                ['title' => 'Третья статья', 'desc' => 'Frontend без JS-фреймворков'],
            ]
        ];

        return $this->render('pages/home', $data);
    }

    #[Route('GET', '/hello/{name}')]
    public function hello(string $name): void
    {
        echo "Hello {$name}";
    }
}
