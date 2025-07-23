<h3><?= $site_name ?></h3>
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