<h1>Добро пожаловать на главную!</h1>
<p>Это контент, который вставляется в основной шаблон.</p>

<ul>
    <?php foreach ($posts as $post): ?>
        <li><strong><?= htmlspecialchars($post['title']) ?></strong>: <?= htmlspecialchars($post['desc']) ?></li>
    <?php endforeach; ?>
</ul>