<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto flex items-center justify-between p-4">
        <!-- Логотип -->
        <a
            href="/"
            class="text-2xl font-bold text-indigo-600"
        >
            <?= htmlspecialchars($site_name) ?>
        </a>

        <!-- Навигация (десктоп) -->
        <nav class="hidden md:flex items-center space-x-2">
            <a
                href="/"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors
                       <?= ($active_page ?? '') === 'home'
                           ? 'text-indigo-600 bg-indigo-50 font-semibold'
                           : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-100' ?>"
            >
                Главная
            </a>
            <a
                href="/about"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors
                       <?= ($active_page ?? '') === 'about'
                           ? 'text-indigo-600 bg-indigo-50 font-semibold'
                           : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-100' ?>"
            >
                О нас
            </a>
            <a
                href="/contact"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors
                       <?= ($active_page ?? '') === 'contact'
                           ? 'text-indigo-600 bg-indigo-50 font-semibold'
                           : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-100' ?>"
            >
                Контакты
            </a>
        </nav>

        <!-- Кнопка мобильного меню -->
        <button
            class="md:hidden text-gray-600 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 rounded p-2"
            type="button"
            aria-controls="mobile-menu"
            aria-expanded="false"
            onclick="toggleMobileMenu()"
        >
            <span class="sr-only">Открыть меню</span>
            <svg
                class="h-6 w-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
        </button>
    </div>

    <!-- Мобильное меню -->
    <div
        id="mobile-menu"
        class="hidden md:hidden bg-white border-t border-gray-200"
    >
        <nav class="flex flex-col">
            <a
                href="/"
                class="nav-link px-5 py-3 text-gray-700 hover:bg-gray-100 hover:text-indigo-600
                       <?= ($active_page ?? '') === 'home' ? 'bg-indigo-50 text-indigo-600 font-medium' : '' ?>"
            >
                Главная
            </a>
            <a
                href="/about"
                class="nav-link px-5 py-3 text-gray-700 hover:bg-gray-100 hover:text-indigo-600
                       <?= ($active_page ?? '') === 'about' ? 'bg-indigo-50 text-indigo-600 font-medium' : '' ?>"
            >
                О нас
            </a>
            <a
                href="/contact"
                class="nav-link px-5 py-3 text-gray-700 hover:bg-gray-100 hover:text-indigo-600
                       <?= ($active_page ?? '') === 'contact' ? 'bg-indigo-50 text-indigo-600 font-medium' : '' ?>"
            >
                Контакты
            </a>
        </nav>
    </div>
</header>

<!-- Скрипт для мобильного меню -->
<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobile-menu');
        const button = document.querySelector('[aria-controls="mobile-menu"]');
        const isExpanded = menu.classList.toggle('hidden');
        button.setAttribute('aria-expanded', !isExpanded);
    }
</script>