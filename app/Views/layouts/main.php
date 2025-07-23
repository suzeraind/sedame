<!DOCTYPE html>
<html lang="ru">

    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        />
        <title><?= htmlspecialchars($title ?? 'Мой сайт') ?></title>

        <!-- Подключаем Tailwind CSS через CDN (для прототипа) -->
        <script src="https://cdn.tailwindcss.com"></script>

        <!-- Шрифт Inter для лучшего вида -->
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
            rel="stylesheet"
        >

        <!-- Ваши скрипты (оставлены как есть) -->
        <script src="/assets/scripts/browser@4.js"></script>
        <script
            src="/assets/scripts/main.js"
            defer
        ></script>

        <!-- Ваши кастомные стили (если нужно что-то дополнить) -->
        <link
            href="/assets/styles/style.css"
            rel="stylesheet"
        />

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }

            .container {
                @apply mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl;
            }
        </style>
    </head>

    <body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

        <!-- Основной контент -->
        <div
            id="app"
            class="flex-grow"
        >
            <?php $this->component('header', ['site_name' => 'Мой Блог']) ?>

            <!-- Основное содержимое страницы -->
            <main class="py-8 sm:py-12">
                <?= $content ?? '' ?>
            </main>
        </div>

        <!-- Подвал -->
        <?php $this->component('footer') ?>

    </body>

</html>