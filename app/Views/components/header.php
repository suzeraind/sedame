<?php
$active_page = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$is_authenticated = isset($_SESSION['user_id']);
$user_name = $_SESSION['username'] ?? 'Пользователь';
?>
<header
    class="bg-white shadow-sm sticky top-0 z-50"
    x-data="{ mobileMenuOpen: false }"
>
    <div class="container mx-auto flex items-center justify-between p-4">
        <a
            href="/"
            class="text-2xl font-bold text-indigo-600"
        >
            <?= htmlspecialchars($site_name ?? 'My Website') ?>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-2">
            <a
                href="/"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors
                       <?= ($active_page ?? '') === '/'
                           ? 'text-indigo-600 bg-indigo-50 font-semibold'
                           : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-100' ?>"
            >
                Главная
            </a>
            <a
                href="/about"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors
                       <?= ($active_page ?? '') === '/about'
                           ? 'text-indigo-600 bg-indigo-50 font-semibold'
                           : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-100' ?>"
            >
                О нас
            </a>
            <a
                href="/contact"
                class="nav-link px-3 py-2 rounded-md text-sm font-medium transition-colors
                       <?= ($active_page ?? '') === '/contact'
                           ? 'text-indigo-600 bg-indigo-50 font-semibold'
                           : 'text-gray-700 hover:text-indigo-600 hover:bg-gray-100' ?>"
            >
                Контакты
            </a>
        </nav>

        <!-- User Menu / Auth Buttons -->
        <div class="hidden md:flex items-center space-x-4">
            <?php if ($is_authenticated): ?>
                <!-- Authenticated User Menu -->
                <div
                    class="relative"
                    x-data="{ userMenuOpen: false }"
                >
                    <button
                        @click="userMenuOpen = !userMenuOpen"
                        @click.away="userMenuOpen = false"
                        type="button"
                        class="flex items-center space-x-2 text-sm font-medium text-gray-700 hover:text-indigo-600 focus:outline-none"
                        aria-haspopup="true"
                        :aria-expanded="userMenuOpen"
                    >
                        <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-semibold text-sm">
                                <?= strtoupper(substr($user_name, 0, 1)) ?>
                            </span>
                        </div>
                        <span class="hidden sm:inline-block"><?= htmlspecialchars($user_name) ?></span>
                        <svg
                            class="h-5 w-5 text-gray-400"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </button>

                    <div
                        x-show="userMenuOpen"
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none"
                        role="menu"
                        aria-orientation="vertical"
                        aria-labelledby="user-menu-button"
                    >
                        <div class="py-1">
                            <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                                <p class="font-medium"><?= htmlspecialchars($user_name) ?></p>
                                <p class="text-gray-500 text-xs">Пользователь</p>
                            </div>
                            <a
                                href="/profile"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                role="menuitem"
                            >
                                Профиль
                            </a>
                            <a
                                href="/settings"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                role="menuitem"
                            >
                                Настройки
                            </a>
                            <a
                                href="/logout"
                                class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50"
                                role="menuitem"
                            >
                                Выйти
                            </a>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <!-- Guest Buttons -->
                <a
                    href="/login"
                    class="text-sm font-medium text-gray-700 hover:text-indigo-600"
                >
                    Войти
                </a>
                <a
                    href="/register"
                    class="text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-md transition-colors"
                >
                    Регистрация
                </a>
            <?php endif; ?>
        </div>

        <!-- Mobile Menu Button -->
        <button
            class="md:hidden text-gray-600 hover:text-indigo-600 focus:outline-none cursor-pointer focus:ring-2 focus:ring-indigo-500 rounded p-2"
            type="button"
            aria-controls="mobile-menu"
            :aria-expanded="mobileMenuOpen"
            @click="mobileMenuOpen = !mobileMenuOpen"
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

    <!-- Mobile Menu -->
    <div
        id="mobile-menu"
        x-show="mobileMenuOpen"
        class="md:hidden bg-white border-t border-gray-200"
        role="menu"
        aria-orientation="vertical"
    >
        <nav class="flex flex-col">
            <a
                href="/"
                @click="mobileMenuOpen = false"
                class="nav-link px-5 py-3 text-gray-700 hover:bg-gray-100 hover:text-indigo-600
                       <?= ($active_page ?? '') === '/' ? 'bg-indigo-50 font-medium' : '' ?>"
                role="menuitem"
            >
                Главная
            </a>
            <a
                href="/about"
                @click="mobileMenuOpen = false"
                class="nav-link px-5 py-3 text-gray-700 hover:bg-gray-100 hover:text-indigo-600
                       <?= ($active_page ?? '') === '/about' ? 'bg-indigo-50 font-medium' : '' ?>"
                role="menuitem"
            >
                О нас
            </a>
            <a
                href="/contact"
                @click="mobileMenuOpen = false"
                class="nav-link px-5 py-3 hover:bg-gray-100 hover:text-indigo-600
                       <?= ($active_page ?? '') === '/contact' ? 'bg-indigo-50 text-indigo-600 font-medium' : '' ?>"
                role="menuitem"
            >
                Контакты
            </a>

            <!-- Mobile User Menu -->
            <?php if ($is_authenticated): ?>
                <div class="border-t border-gray-200 py-3 px-5">
                    <div class="flex items-center space-x-3 py-2">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-semibold">
                                <?= strtoupper(substr($user_name, 0, 1)) ?>
                            </span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($user_name) ?></p>
                            <p class="text-xs text-gray-500">Пользователь</p>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1">
                        <a
                            href="/profile"
                            @click="mobileMenuOpen = false"
                            class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md"
                            role="menuitem"
                        >
                            Профиль
                        </a>
                        <a
                            href="/settings"
                            @click="mobileMenuOpen = false"
                            class="block px-3 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-md"
                            role="menuitem"
                        >
                            Настройки
                        </a>
                        <a
                            href="/logout"
                            @click="mobileMenuOpen = false"
                            class="block px-3 py-2 text-sm text-red-600 hover:bg-red-50 rounded-md"
                            role="menuitem"
                        >
                            Выйти
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="border-t border-gray-200 py-3 px-5 space-y-2">
                    <a
                        href="/login"
                        class="block w-full text-center px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md"
                    >
                        Войти
                    </a>
                    <a
                        href="/register"
                        class="block w-full text-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md"
                    >
                        Регистрация
                    </a>
                </div>
            <?php endif; ?>
        </nav>
    </div>
</header>