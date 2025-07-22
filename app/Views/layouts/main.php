<!DOCTYPE html>
<html lang="ru">

    <head>
        <meta charset="UTF-8">
        <title><?= $title ?? 'Мой сайт' ?></title>
        <style>
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 20px;
                background: #f4f4f4;
            }

            .container {
                max-width: 960px;
                margin: 0 auto;
                background: white;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }

            nav {
                margin-bottom: 20px;
            }

            nav a {
                margin-right: 15px;
                text-decoration: none;
                color: #007cba;
            }

            nav a.active {
                font-weight: bold;
            }

            footer {
                margin-top: 30px;
                color: #777;
                text-align: center;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <nav>
                <a
                    href="/"
                    class="<?= $active_page === 'home' ? 'active' : '' ?>"
                >
                    Главная</a>
                <a
                    href="/about"
                    class="<?= $active_page === 'about' ? 'active' : '' ?>"
                >
                    О нас
                </a>
                <a href="/contact">Контакты</a>
            </nav>

            <?= $content ?? '' ?>

            <footer>
                &copy; <?= date('Y') ?> — Мой PHP-сайт
            </footer>
        </div>
    </body>

</html>