<div class="text-center max-w-3xl mx-auto mb-12">
    <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-4 tracking-tight">
        Добро пожаловать на главную!
    </h1>
    <p class="text-lg text-gray-600 leading-relaxed">
        Это контент, который вставляется в основной шаблон.
    </p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-6xl mx-auto">
    <?php foreach ($posts as $post): ?>
        <article
            class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-100 group"
        >
            <h2 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors">
                <?= htmlspecialchars($post['title']) ?>
            </h2>
            <p class="text-gray-700 leading-relaxed text-sm md:text-base line-clamp-3">
                <?= htmlspecialchars($post['desc']) ?>
            </p>
            <div class="mt-4">
                <span class="inline-block px-3 py-1 bg-indigo-100 text-indigo-700 text-xs font-medium rounded-full">
                    Читать далее
                </span>
            </div>
        </article>
    <?php endforeach; ?>
</div>

<!-- Если постов нет -->
<?php if (empty($posts)): ?>
    <div class="text-center mt-10">
        <p class="text-gray-500 text-lg">Пока нет доступных постов.</p>
    </div>
<?php endif; ?>
