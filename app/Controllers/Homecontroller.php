<?php

namespace App\Controllers;

use App\Core\Http;
use App\Core\Controller;
use App\Core\Attributes\Route;
use App\Core\Attributes\Middleware;

class Homecontroller extends Controller
{
    /**
     * Renders the home page.
     */
    #[Route(Http::GET, '/')]
    #[Route(Http::GET, '/home')]
    public function index(): void
    {
        $data = [
            'title' => 'Home — My Website',
            'posts' => [
                ['title' => 'First Post', 'desc' => 'About PHP and templates'],
                ['title' => 'Second Post', 'desc' => 'How autoloading works'],
                ['title' => 'Third Post', 'desc' => 'Frontend without JS frameworks'],
            ]
        ];
        $this->render('home', $data);
    }

    /**
     * Renders the about page.
     */
    #[Route(Http::GET, '/about')]
    public function about(): void
    {
        $this->render('about', [
            'team' => [
                ['name' => 'Anna Petrova', 'role' => 'CEO', 'image' => 'https://via.placeholder.com/149'],
                ['name' => 'Dmitry Sidorov', 'role' => 'CTO', 'image' => 'https://via.placeholder.com/149'],
                ['name' => 'Elena Kozlova', 'role' => 'Designer', 'image' => 'https://via.placeholder.com/149'],
                ['name' => 'Ivan Novikov', 'role' => 'Developer', 'image' => 'https://via.placeholder.com/149'],
            ]
        ]);
    }

    /**
     * Renders the contact page.
     */
    #[Route(Http::GET, '/contact')]
    #[Middleware('AuthMiddleware')]
    public function contact(): void
    {
        $this->render('contact');
    }

    /**
     * A simple hello world endpoint.
     *
     * @param string $name
     */
    #[Route(Http::GET, '/hello/{name}')]
    public function hello(string $name): void
    {
        echo "Hello {$name}";
    }

    /**
     * Returns a JSON response for a POST request.
     */
    #[Route(Http::POST, '/json')]
    public function jsonPostResponse(): void
    {
        $this->json([
            'hello' => 'post'
        ]);
    }

    /**
     * Returns a JSON response for a GET request.
     */
    #[Route(Http::GET, '/json')]
    public function jsonGetResponse(): void
    {
        $this->json([
            'hello' => 'get'
        ]);
    }
}

