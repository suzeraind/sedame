<?php
$active_page = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?: '/';
$is_authenticated = isset($_SESSION['user_id']);
$user_name = $_SESSION['username'] ?? 'User';
?>

<!--
This header uses Alpine.js for two features:
1.  `mobileMenuOpen`: Toggles the visibility of the mobile navigation menu.
2.  `headerScrolled`: Tracks the page scroll position. When the user scrolls down more than 50 pixels,
    it adds a background color and shadow to the header for better visibility.
-->
<header
    x-data="{ mobileMenuOpen: false, headerScrolled: false }"
    @scroll.window="headerScrolled = (window.pageYOffset > 50)"
    :class="{ 'bg-white shadow-md': headerScrolled, 'bg-transparent': !headerScrolled }"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
>
    <div class="container mx-auto flex items-center justify-between p-4 h-20">
        <!-- Logo -->
        <a href="/" class="text-2xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 transition-all">
            <?= htmlspecialchars($site_name ?? 'Sedame') ?>
        </a>

        <!-- Desktop Navigation -->
        <nav class="hidden md:flex items-center space-x-8">
            <a href="/" class="text-gray-600 hover:text-indigo-600 transition-colors pb-2 border-b-2 <?= $active_page === '/' ? 'border-indigo-500' : 'border-transparent' ?>">Home</a>
            <a href="/about" class="text-gray-600 hover:text-indigo-600 transition-colors pb-2 border-b-2 <?= $active_page === '/about' ? 'border-indigo-500' : 'border-transparent' ?>">About</a>
            <a href="/showcase" class="text-gray-600 hover:text-indigo-600 transition-colors pb-2 border-b-2 <?= $active_page === '/showcase' ? 'border-indigo-500' : 'border-transparent' ?>">Showcase</a>
        </nav>

        <!-- Right Section: Auth Buttons-->
        <div class="hidden md:flex items-center space-x-4">
            <?php if ($is_authenticated): ?>
                <!-- Authenticated User Menu -->
                <div class="relative" x-data="{ userMenuOpen: false }">
                    <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" type="button" class="flex items-center space-x-2 focus:outline-none">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center">
                            <span class="text-indigo-600 font-semibold"><?= strtoupper(substr($user_name, 0, 1)) ?></span>
                        </div>
                        <span class="text-gray-700"><?= htmlspecialchars($user_name) ?></span>
                    </button>
                    <div x-show="userMenuOpen" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 ring-1 ring-black ring-opacity-5">
                        <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                        <a href="/logout" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <!-- Guest Buttons -->
                <a href="/login" class="text-gray-600 hover:text-indigo-600">Login</a>
                <a href="/register" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition-all">
                    Register
                </a>
            <?php endif; ?>
        </div>

        <!-- Mobile Menu Button -->
        <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-600">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m4 6H4" /></svg>
        </button>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-white shadow-lg">
        <nav class="flex flex-col p-4 space-y-2">
            <a href="/" class="text-gray-700 hover:bg-gray-100 p-2 rounded-md">Home</a>
            <a href="/about" class="text-gray-700 hover:bg-gray-100 p-2 rounded-md">About</a>
            <a href="/showcase" class="text-gray-700 hover:bg-gray-100 p-2 rounded-md">Showcase</a>
            <div class="border-t border-gray-200 pt-4 mt-4">
                <?php if ($is_authenticated): ?>
                    <a href="/profile" class="block w-full text-left p-2 text-gray-700 hover:bg-gray-100">Profile</a>
                    <a href="/logout" class="block w-full text-left p-2 text-red-600 hover:bg-red-50">Logout</a>
                <?php else: ?>
                    <a href="/login" class="block w-full text-center p-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md">Login</a>
                    <a href="/register" class="block w-full text-center mt-2 p-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-md">Register</a>
                <?php endif; ?>
            </div>
        </nav>
    </div>
</header>
