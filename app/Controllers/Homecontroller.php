<?php

namespace App\Controllers;

use App\Core\Attributes\Route;
use App\Core\Controller;
use App\Core\Http;
use App\Core\Response;
use App\Facades\View;

class Homecontroller extends Controller
{
    /**
     * Renders the home page.
     */
    #[Route(Http::GET, '/')]
    #[Route(Http::GET, '/home')]
    public function index(): Response
    {
        $data = [
            'title' => 'Home — My Website',
            'posts' => [
                ['title' => 'First Post', 'desc' => 'About PHP and templates'],
                ['title' => 'Second Post', 'desc' => 'How autoloading works'],
                ['title' => 'Third Post', 'desc' => 'Frontend without JS frameworks'],
            ]
        ];
        $content = View::layout('main')->with($data)->render('home');
        return new Response($content);
    }

    /**
     * Renders the about page.
     */
    #[Route(Http::GET, '/about')]
    public function about(): Response
    {
        $content = View::layout('main')->render('about');
        return new Response($content);
    }

    /**
     * Renders the showcase page.
     */
    #[Route(Http::GET, '/showcase')]
    public function showcase(): Response
    {
        $content = View::layout('main')->render('showcase');
        return new Response($content);
    }

    /**
     * A simple hello world endpoint.
     *
     * @param string $name
     */
    #[Route(Http::GET, '/hello/{name}')]
    public function hello(string $name): Response
    {
        return new Response("Hello {$name}");
    }

    /**
     * Returns a JSON response for a POST request.
     */
    #[Route(Http::POST, '/json')]
    public function jsonPostResponse(): Response
    {
        return new Response(json_encode(['hello' => 'post']), 200, ['Content-Type' => 'application/json']);
    }

    /**
     * Returns a JSON response for a GET request.
     */
    #[Route(Http::GET, '/json')]
    public function jsonGetResponse(): Response
    {
        return new Response(json_encode(['hello' => 'get']), 200, ['Content-Type' => 'application/json']);
    }
}
