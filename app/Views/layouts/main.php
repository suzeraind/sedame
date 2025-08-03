<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8" />
        <meta
            name="viewport"
            content="width=device-width, initial-scale=1.0"
        />
        <title><?= htmlspecialchars($title ?? 'My Website') ?></title>

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

        <!-- Prism.js for code highlighting -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/themes/prism-tomorrow.min.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/components/prism-core.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.29.0/plugins/autoloader/prism-autoloader.min.js"></script>

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
            <?php $this->component('header', ['site_name' => 'Sedame']) ?>
            <main class="pt-20 sm:pt-24">
                <?= $content ?? '' ?>
            </main>
        </div>
        <?php $this->component('footer') ?>
    </body>

</html>