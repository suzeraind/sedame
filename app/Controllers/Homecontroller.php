<?php

namespace App\Controllers;

use App\Core\Http;
use App\Core\Controller;
use App\Core\Attributes\Route;

class Homecontroller extends Controller
{

    #[Route(Http::GET, '/')]
    #[Route(Http::GET, '/home')]
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

    #[Route(Http::GET, '/hello/{name}')]
    public function hello(string $name): void
    {
        echo "Hello {$name}";
    }


    #[Route(Http::POST, '/json')]
    public function jsonPostResponse()
    {
        $this->json([
            'hello' => 'post'
        ]);
    }

    #[Route(Http::GET, '/json')]
    public function jsonGetResponse()
    {
        $this->json([
            'hello' => 'get'
        ]);
    }

}
