<?php

require_once __DIR__ . '/../bootstrap/app.php';

use App\Models\User;

echo "=== ТЕСТИРОВАНИЕ МОДЕЛИ USER ===\n\n";

// 1. Получить всех пользователей
echo "1. Все пользователи:\n";
$users = new User()->all();
foreach ($users as $user) {
    echo "   - {$user['name']} ({$user['email']})\n";
}

echo "\n";

// 2. Найти пользователя по ID
echo "2. Пользователь с ID = 1:\n";
$user = new User()->find(1);
if ($user) {
    echo "   Имя: {$user['name']}\n";
    echo "   Email: {$user['email']}\n";
} else {
    echo "   Пользователь не найден\n";
}

echo "\n";

// 3. Создать нового пользователя
echo "3. Создание нового пользователя:\n";
$newUser = new User()->create([
    'name' => 'Test User 3',
    'email' => 'test@gmail.com',
    'password' => password_hash('testpass', PASSWORD_DEFAULT),
    'active' => 1
]);

if ($newUser) {
    echo "   ✅ Создан пользователь: {$newUser['name']} (ID: {$newUser['id']})\n";
} else {
    echo "   ❌ Ошибка создания\n";
}

echo "\n";

// 4. Обновить пользователя
echo "4. Обновление пользователя:\n";
$updatedUser = new User()->update($newUser['id'], [
    'name' => 'Updated Test User',
    'active' => 0
]);

if ($updatedUser) {
    echo "   ✅ Обновлен пользователь: {$updatedUser['name']}\n";
} else {
    echo "   ❌ Ошибка обновления\n";
}

echo "\n";

// 5. Поиск по email
echo "5. Поиск по email 'jane@example.com':\n";
$userByEmail = new User()->findByEmail('jane@example.com');
if ($userByEmail) {
    echo "   ✅ Найден: {$userByEmail['name']}\n";
} else {
    echo "   ❌ Не найден\n";
}

echo "\n";

// 6. Активные пользователи
echo "6. Активные пользователи:\n";
$activeUsers = new User()->active()->get();
foreach ($activeUsers as $user) {
    echo "   - {$user['name']} (активен)\n";
}

echo "\n";

// 7. Сложный запрос
echo "7. Сложный запрос (активные, сортировка):\n";
$complexQuery = new User()
    ->active()
    ->orderBy('created_at', 'DESC')
    ->limit(5)
    ->get();

foreach ($complexQuery as $user) {
    echo "   - {$user['name']} (создан: {$user['created_at']})\n";
}

echo "\n";

// 8. ТЕСТ УДАЛЕНИЯ
echo "8. Тест удаления пользователя:\n";

// Сначала проверим, что пользователь существует
$userToDelete = new User()->find($newUser['id']);
if ($userToDelete) {
    echo "   Найден пользователь для удаления: {$userToDelete['name']}\n";

    // Удаляем пользователя
    $deleted = new User()->delete($newUser['id']);

    if ($deleted) {
        echo "   ✅ Пользователь успешно удален\n";

        // Проверим, что пользователь действительно удален
        $checkUser = new User()->find($newUser['id']);
        if (!$checkUser) {
            echo "   ✅ Подтверждено: пользователь больше не существует\n";
        } else {
            echo "   ❌ Ошибка: пользователь все еще существует\n";
        }
    } else {
        echo "   ❌ Ошибка при удалении пользователя\n";
    }
} else {
    echo "   ❌ Пользователь для удаления не найден\n";
}

echo "\n";

// 9. Финальная проверка - количество пользователей
echo "9. Финальная проверка:\n";
$finalUsers = new User()->all();
echo "   Всего пользователей в базе: " . count($finalUsers) . "\n";

echo <<<EOF
\n🎉 Тестирование завершено!\n
EOF;
