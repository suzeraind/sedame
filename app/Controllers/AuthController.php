<?php


namespace App\Controllers;

use App\Core\Http;
use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Facades\View;
use App\Core\Attributes\Route;
use App\Core\Attributes\Middleware;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Constructor for AuthController.
     * Initializes the parent controller and the User model.
     *
     * @param User $userModel The user model instance.
     */
    public function __construct(private User $userModel)
    {
    }

    /**
     * Displays the login form.
     * This route is accessible only to guests.
     *
     * @return Response
     */
    #[Route(Http::GET, '/login')]
    #[Middleware('GuestMiddleware')]
    public function showLogin(): Response
    {
        $content = View::layout('main')->render('auth/login');
        return new Response($content);
    }

    /**
     * Handles the user login process.
     * Validates credentials and redirects on success, or shows errors on failure.
     *
     * @param Request $request
     * @return Response
     */
    #[Route(Http::POST, '/login')]
    #[Middleware('GuestMiddleware')]
    public function login(Request $request): Response
    {
        $email = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');

        if ($email === '' || $password === '') {
            $content = View::layout('main')->with([
                'error' => 'Please fill in all fields'
            ])->render('auth/login');
            return new Response($content, 422);
        }

        $user = $this->userModel->findByEmail($email);

        if ($user !== null && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'] ?? $user['username'];
            return new Response('', 302, ['Location' => '/home']);
        } else {
            $content = View::layout('main')->with([
                'error' => 'Invalid email or password',
                'old_email' => $email
            ])->render('auth/login');
            return new Response($content, 422);
        }
    }

    /**
     * Displays the registration form.
     * This route is accessible only to guests.
     *
     * @return Response
     */
    #[Route(Http::GET, '/register')]
    #[Middleware('GuestMiddleware')]
    public function showRegister(): Response
    {
        $content = View::layout('main')->render('auth/register');
        return new Response($content);
    }

    /**
     * Handles the user registration process.
     * Validates input, creates a new user, and redirects on success, or shows errors on failure.
     *
     * @param Request $request
     * @return Response
     */
    #[Route(Http::POST, '/register')]
    #[Middleware('GuestMiddleware')]
    public function register(Request $request): Response
    {
        $name = trim((string) $request->input('name', ''));
        $email = trim((string) $request->input('email', ''));
        $password = (string) $request->input('password', '');
        $password_confirm = (string) $request->input('password_confirm', '');

        $errors = [];

        if ($name === '') {
            $errors[] = 'Name is required';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid email';
        }

        if ($password === '' || strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters long';
        }

        if ($password !== $password_confirm) {
            $errors[] = 'Passwords do not match';
        }

        if (empty($errors)) {
            if ($this->userModel->findByEmail($email) !== null) {
                $errors[] = 'User with this email already exists';
            }
        }

        if (!empty($errors)) {
            $content = View::layout('main')->with([
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email
                ]
            ])->render('auth/register');
            return new Response($content, 422);
        }

        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'active' => 1
        ];

        try {
            $this->userModel->create($userData);
            return new Response('', 302, ['Location' => '/login?registered=1']);
        } catch (\Exception $e) {
            $content = View::layout('main')->with([
                'errors' => ['Error during registration: ' . $e->getMessage()]
            ])->render('auth/register');
            return new Response($content, 500);
        }
    }

    /**
     * Logs out the current user by destroying the session.
     *
     * @return Response
     */
    #[Route(Http::GET, '/logout')]
    #[Middleware('AuthMiddleware')]
    public function logout(): Response
    {
        session_destroy();
        $_SESSION = [];
        return new Response('', 302, ['Location' => '/login']);
    }
}