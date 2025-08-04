<div class="relative bg-white overflow-hidden">
    <!-- Hero Section -->
    <div class="relative pt-6 pb-16 sm:pb-24 lg:pb-32">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <div class="sm:text-center md:max-w-2xl md:mx-auto lg:col-span-6 lg:text-left lg:flex lg:items-center">
                    <div>
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block">Sedame Framework</span>
                            <span class="block text-indigo-600">A Simple PHP MVC Kit</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto lg:mx-0">
                            Welcome to Sedame, a lightweight and educational PHP MVC framework. It's designed to provide a solid starting point for building modern web applications, focusing on simplicity and core MVC concepts.
                        </p>
                        <div class="mt-8 sm:max-w-lg sm:mx-auto sm:text-center lg:mx-0">
                            <div class="flex flex-wrap gap-4">
                                <a href="/register" class="flex-shrink-0 px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                    Get Started
                                </a>
                                <a href="#features" class="flex-shrink-0 px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50">
                                    Learn More
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-12 relative sm:max-w-lg sm:mx-auto lg:mt-0 lg:max-w-none lg:mx-0 lg:col-span-6 lg:flex lg:items-center">
                    <div class="relative mx-auto w-full rounded-lg shadow-lg">
                        <div class="relative block w-full bg-white rounded-lg overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <div class="absolute inset-0 bg-gray-800 opacity-75"></div>
                            <div class="relative p-8">
                                <h3 class="text-xl font-bold text-white">Code Example</h3>
                                <pre class="mt-4 rounded-md bg-gray-900 p-4 overflow-auto"><code class="language-php text-sm text-gray-300">
// app/Controllers/AuthController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Facades\View;
use App\Models\User;
use App\Core\Attributes\Route;
use App\Core\Attributes\Middleware;

class AuthController extends Controller
{
    // The User model is automatically injected
    // by the service container.
    public function __construct(private User $userModel)
    {
    }

    #[Route(Http::GET, '/login')]
    #[Middleware('GuestMiddleware')]
    public function showLogin(): void
    {
        // Use the View facade for rendering.
        // It provides a clean, static interface.
        View::layout('main')->render('auth/login');
    }
}
                                </code></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Framework Features</h2>
                <p class="mt-4 text-lg text-gray-500">Core components that make development easier and more organized.</p>
            </div>
            <div class="mt-12 grid gap-8 md:grid-cols-2 lg:grid-cols-4">
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <!-- Icon: MVC -->
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-medium text-gray-900">MVC Architecture</h3>
                    <p class="mt-2 text-base text-gray-500">Separation of concerns with Models, Views, and Controllers for organized and maintainable code.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <!-- Icon: Routing -->
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-medium text-gray-900">Attribute-based Routing</h3>
                    <p class="mt-2 text-base text-gray-500">Define routes directly in your controller methods using PHP 8 attributes for a clean and declarative API.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <!-- Icon: Auth -->
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-medium text-gray-900">Authentication</h3>
                    <p class="mt-2 text-base text-gray-500">Built-in authentication system with middleware to protect routes for authenticated or guest users.</p>
                </div>
                <div class="p-6 bg-white rounded-lg shadow-md">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-500 text-white">
                        <!-- Icon: Database -->
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10m16-10v10M4 14h16M4 10h16M4 7h16" /></svg>
                    </div>
                    <h3 class="mt-5 text-lg font-medium text-gray-900">Query Builder & Models</h3>
                    <p class="mt-2 text-base text-gray-500">A simple yet powerful Query Builder and Model system for easy database interactions.</p>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Tech Stack Section -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Powered By</h2>
                <p class="mt-4 text-lg text-gray-500">Built with modern and reliable technologies.</p>
            </div>
            <div class="mt-10 flex justify-center items-center space-x-8 md:space-x-12">
                <div class="flex items-center justify-center">
                    <span class="text-4xl md:text-5xl font-bold text-gray-700">PHP</span>
                </div>
                <div class="flex items-center justify-center">
                    <span class="text-4xl md:text-5xl font-bold text-gray-700">TailwindCSS</span>
                </div>
                <div class="flex items-center justify-center">
                    <span class="text-4xl md:text-5xl font-bold text-gray-700">Alpine.js</span>
                </div>
            </div>
        </div>
    </section>
</div>
