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


    #[Route(Http::GET, '/about')]
    public function about()
    {
        return $this->render('pages/about', [
            'team' => [
                ['name' => 'Анна Петрова', 'role' => 'CEO', 'image' => 'https://via.placeholder.com/149'],
                ['name' => 'Дмитрий Сидоров', 'role' => 'CTO', 'image' => 'https://via.placeholder.com/149'],
                ['name' => 'Елена Козлова', 'role' => 'Дизайнер', 'image' => 'https://via.placeholder.com/149'],
                ['name' => 'Иван Новиков', 'role' => 'Разработчик', 'image' => 'https://via.placeholder.com/149'],
            ]
        ]);
    }

    #[Route(Http::GET, '/contact')]
    public function contact()
    {
        return $this->render('pages/contact');
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
