<?php

namespace App\Controllers;

use App\Core\Http;
use App\Core\Controller;
use App\Core\Attributes\Route;
use App\Facades\View;

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
        View::layout('main')->with($data)->render('home');
    }

    /**
     * Renders the about page.
     */
    #[Route(Http::GET, '/about')]
    public function about(): void
    {
        View::layout('main')->render('about');
    }



    /**
     * Renders the showcase page.
     */
    #[Route(Http::GET, '/showcase')]
    public function showcase(): void
    {
        View::layout('main')->render('showcase');
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

