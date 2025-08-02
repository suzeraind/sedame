<?php

namespace App\Controllers;

use App\Core\Http;
use App\Core\Controller;
use App\Core\Attributes\Route;
use App\Core\Attributes\Middleware;
use App\Models\User;

class AuthController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = User::inst();
    }

    /**
     * Show login form only for guests
     */
    #[Route(Http::GET, '/login')]
    #[Middleware('GuestMiddleware')]
    public function showLogin(): void
    {
        $this->render('auth/login');
    }

    /**
     * Handle login
     */
    #[Route(Http::POST, '/login')]
    #[Middleware('GuestMiddleware')]
    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $this->render('auth/login', [
                'error' => 'Заполните все поля'
            ]);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['name'] ?? $user['username'];
            $this->redirect('/home');
        } else {
            $this->render('auth/login', [
                'error' => 'Неверный email или пароль',
                'old_email' => $email
            ]);
        }
    }

    /**
     * Show registration form (только для гостей)
     */
    #[Route(Http::GET, '/register')]
    #[Middleware('GuestMiddleware')]
    public function showRegister(): void
    {
        $this->render('auth/register');
    }

    /**
     * Handle registration
     */
    #[Route(Http::POST, '/register')]
    #[Middleware('GuestMiddleware')]
    public function register(): void
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Имя обязательно';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email';
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Пароль должен быть не менее 6 символов';
        }

        if ($password !== $password_confirm) {
            $errors[] = 'Пароли не совпадают';
        }

        if (empty($errors)) {
            if ($this->userModel->findByEmail($email)) {
                $errors[] = 'Пользователь с таким email уже существует';
            }
        }

        if (!empty($errors)) {
            $this->render('auth/register', [
                'errors' => $errors,
                'old' => [
                    'name' => $name,
                    'email' => $email
                ]
            ]);
            return;
        }

        $userData = [
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'active' => 1
        ];

        try {
            $this->userModel->create($userData);
            $this->redirect('/login?registered=1');
        } catch (\Exception $e) {
            $this->render('auth/register', [
                'errors' => ['Ошибка при регистрации: ' . $e->getMessage()]
            ]);
        }
    }

    /**
     * Logout user
     */
    #[Route(Http::GET, '/logout')]
    #[Middleware('AuthMiddleware')]
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }
}
