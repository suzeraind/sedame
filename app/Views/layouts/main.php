<!DOCTYPE html>
<html lang="ru">

    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        />
        <title><?= htmlspecialchars($title ?? 'Мой сайт') ?></title>

        <script src="/assets/scripts/tailwind.js"></script>
        <script
            src="/assets/scripts/main.js"
            defer
        ></script>
        <script
            src="/assets/scripts/alpine.js"
            defer
        ></script>
        <link
            href="/assets/styles/style.css"
            rel="stylesheet"
        />

        <style>
            .container {
                @apply mx-auto px-4 sm:px-6 lg:px-8 max-w-6xl;
            }
        </style>
    </head>

    <body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">
        <div
            id="app"
            class="flex-grow"
        >
            <?php $this->component('header', ['site_name' => 'Мой Блог']) ?>
            <main class="py-8 sm:py-12">
                <?= $content ?? '' ?>
            </main>
        </div>
        <?php $this->component('footer') ?>
    </body>

</html>