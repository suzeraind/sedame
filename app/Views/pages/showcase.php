<!-- Page Container -->
<div class="bg-gray-50 min-h-screen">

    <!-- Hero Section -->
    <section class="bg-white shadow-sm">
        <div class="container mx-auto py-16 text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800">Framework Showcase</h1>
            <p class="mt-4 text-lg text-gray-600">Discover the core features of the Sedame framework.</p>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-16">
        <div class="container mx-auto space-y-12">

            <!-- Feature 1: Attribute-based Routing -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Attribute-based Routing</h2>
                <p class="text-gray-600 mb-6">
                    Define your routes directly in your controller methods using PHP 8 attributes.
                    This approach keeps your routing logic co-located with the code that handles the request, making it
                    clean and easy to manage.
                </p>
                <pre><code class="language-php">
                    namespace App\Controllers;

                    use App\Core\Http;
                    use App\Core\Controller;
                    use App\Core\Attributes\Route;

                    class AuthController extends Controller
                    {
                        #[Route(Http::GET, '/login')]
                        public function showLogin(): void
                        {
                            $this->render('auth/login');
                        }

                        #[Route(Http::POST, '/login')]
                        public function login(): void
                        {
                            // ...
                        }
                    }
                </code></pre>
            </div>

            <!-- Feature 2: Controllers & Views -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Controllers & Views</h2>
                <p class="text-gray-600 mb-6">
                    Controllers handle user requests and render views. You can easily pass data from your controller to
                    your view,
                    which will be automatically extracted and made available as variables.
                </p>
                <pre><code class="language-php">
                    // In your Controller:
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
                        
                        // ...
                    }
                </code></pre>
                <pre><code class="language-php">
                    // In your View (auth/login.php):
                    &lt;?php if (isset($error)): ?&gt;
                        &lt;div class="text-red-500 text-sm mb-4"&gt;
                            &lt;?= $error ?&gt;
                        &lt;/div&gt;
                    &lt;?php endif; ?&gt;
                </code></pre>
            </div>

            <!-- Feature 3: Database & Models -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Database & Models</h2>
                <p class="text-gray-600 mb-6">
                    The framework includes a simple yet powerful query builder and an Eloquent-like ORM for interacting
                    with your database.
                    Define your models, and the framework handles the rest.
                </p>
                <pre><code class="language-php">
                    namespace App\Models;

                    use App\Core\Model;

                    class User extends Model
                    {
                        protected string $table = 'users';

                        public function findByEmail(string $email): mixed
                        {
                            return $this->firstWhere('email', '=', $email);
                        }
                    }

                    // --- Usage in Controller ---
                    $user = $this->userModel->findByEmail($email);
                </code></pre>
            </div>

            <!-- Feature 4: Middleware -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Middleware</h2>
                <p class="text-gray-600 mb-6">
                    Protect your routes with middleware. You can apply middleware to controller methods using
                    attributes,
                    making it easy to implement logic for authentication, logging, and more.
                </p>
                <pre><code class="language-php">
                    namespace App\Controllers;
                    
                    use App\Core\Attributes\Middleware;
                    use App\Core\Controller;
                    use App\Core\Http;

                    class AuthController extends Controller
                    {
                        #[Route(Http::GET, '/logout')]
                        #[Middleware('AuthMiddleware')]
                        public function logout(): void
                        {
                            session_destroy();
                            $this->redirect('/login');
                        }
                    }
                </code></pre>
            </div>

            <!-- Feature 5: Advanced Query Builder -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Advanced Query Builder</h2>
                <p class="text-gray-600 mb-6">
                    The query builder supports more complex queries, allowing you to chain multiple conditions, order
                    results, and set limits.
                </p>
                <pre><code class="language-php">
                    // In a Model or directly using the QueryBuilder
                    $recentActiveUsers = User::inst()
                        ->select(['id', 'name', 'email'])
                        ->where('active', '=', 1)
                        ->whereIn('role', ['admin', 'editor'])
                        ->orderBy('created_at', 'DESC')
                        ->limit(10)
                        ->get();
                </code></pre>
            </div>

            <!-- Feature 6: View Layouts & Components -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">View Layouts & Components</h2>
                <p class="text-gray-600 mb-6">
                    Keep your views DRY (Don't Repeat Yourself) by using a main layout file. The content from your page
                    view is automatically injected into the layout. You can also render reusable partials as components.
                </p>
                <pre><code class="language-php">
                    // In your Controller, rendering a view uses the default layout
                    $this->render('home', ['title' => 'Home Page']);

                    // In your layout file (e.g., layouts/main.php)
                    &lt;!DOCTYPE html&gt;
                    &lt;html&gt;
                    &lt;head&gt;
                        &lt;title&gt;&lt;?= $title ?? 'My App' ?&gt;&lt;/title&gt;
                    &lt;/head&gt;
                    &lt;body&gt;
                        &lt;?php $this->component('header') ?&gt;
                        
                        &lt;main&gt;
                            &lt;?= $content ?? '' ?&gt; &lt;!-- Page content is injected here --&gt;
                        &lt;/main&gt;

                        &lt;?php $this->component('footer') ?&gt;
                    &lt;/body&gt;
                    &lt;/html&gt;
                </code></pre>
            </div>

            <!-- Feature 7: Helper Functions -->
            <div class="bg-white p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Helper Functions</h2>
                <p class="text-gray-600 mb-6">
                    The framework provides several global helper functions to simplify common tasks like redirection,
                    asset URL generation, and debugging.
                </p>
                <pre><code class="language-php">
                    // Redirect to a different page
                    redirect('/login');

                    // Get the URL for a CSS or JS file
                    $url = asset('styles/style.css'); // Returns http://your-domain.com/assets/styles/style.css

                    // Dump a variable and die (for debugging)
                    $user = new User()->find(1);
                    pp($user);
                </code></pre>
            </div>

        </div>
    </section>

</div>