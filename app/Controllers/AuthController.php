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

    /**
     * Constructor for AuthController.
     * Initializes the parent controller and the User model.
     *
     * @param User|null $userModel The user model instance.
     */
    public function __construct(?User $userModel = null)
    {
        parent::__construct();
        $this->userModel = $userModel ?? User::inst();
    }

    /**
     * Displays the login form.
     * This route is accessible only to guests.
     *
     * @return void
     */
    #[Route(Http::GET, '/login')]
    #[Middleware('GuestMiddleware')]
    public function showLogin(): void
    {
        $this->render('auth/login');
    }

    /**
     * Handles the user login process.
     * Validates credentials and redirects on success, or shows errors on failure.
     *
     * @return void
     */
    #[Route(Http::POST, '/login')]
    #[Middleware('GuestMiddleware')]
    public function login(): void
    {
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');

        if ($email === '' || $password === '') {
            $this->render('auth/login', [
                'error' => 'Заполните все поля'
            ]);
            return;
        }

        $user = $this->userModel->findByEmail($email);

        if ($user !== null && password_verify($password, $user['password'])) {
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
     * Displays the registration form.
     * This route is accessible only to guests.
     *
     * @return void
     */
    #[Route(Http::GET, '/register')]
    #[Middleware('GuestMiddleware')]
    public function showRegister(): void
    {
        $this->render('auth/register');
    }

    /**
     * Handles the user registration process.
     * Validates input, creates a new user, and redirects on success, or shows errors on failure.
     *
     * @return void
     */
    #[Route(Http::POST, '/register')]
    #[Middleware('GuestMiddleware')]
    public function register(): void
    {
        $name = trim((string) ($_POST['name'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $password_confirm = (string) ($_POST['password_confirm'] ?? '');

        $errors = [];

        if ($name === '') {
            $errors[] = 'Имя обязательно';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Введите корректный email';
        }

        if ($password === '' || strlen($password) < 6) {
            $errors[] = 'Пароль должен быть не менее 6 символов';
        }

        if ($password !== $password_confirm) {
            $errors[] = 'Пароли не совпадают';
        }

        if (empty($errors)) {
            if ($this->userModel->findByEmail($email) !== null) {
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
     * Logs out the current user by destroying the session.
     *
     * @return void
     */
    #[Route(Http::GET, '/logout')]
    #[Middleware('AuthMiddleware')]
    public function logout(): void
    {
        session_destroy();
        $this->redirect('/login');
    }
}
