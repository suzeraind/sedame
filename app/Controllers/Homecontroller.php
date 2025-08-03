<?php

namespace App\Controllers;

use App\Core\Http;
use App\Core\Controller;
use App\Core\Attributes\Route;

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
        $this->render('about');
    }



    /**
     * Renders the showcase page.
     */
    #[Route(Http::GET, '/showcase')]
    public function showcase(): void
    {
        $this->render('showcase');
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

