<!DOCTYPE html>
<html lang="ru">

    <head>
        <meta charset="UTF-8">
        <title><?= $title ?? 'Мой сайт' ?></title>
        <script src="./assets/scripts/browser@4.js"></script>
        <script src="./assets/scripts/main.js"></script>
        <link
            href="./assets/styles/style.css"
            rel="stylesheet"
        >
        </link>
    </head>

    <body>
        <div class="container">
            <?php $this->component('header', ['site_name' => 'Мой Блог']) ?>
            <h1 class="text-3xl font-bold underline">Bambalelo</h1>
            <?= $content ?? '' ?>

        </div>
        <?php $this->component('footer') ?>
    </body>

</html>