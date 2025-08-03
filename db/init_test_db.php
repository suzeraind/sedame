<?php

require_once __DIR__ . '/../bootstrap/app.php';

use App\Core\Database;

$dbPath = __DIR__ . '/db.sqlite';

try {
    $dbExists = file_exists($dbPath);
    if (!$dbExists) {
        touch($dbPath);
        echo "✅ Database file created at: $dbPath\n";
    } else {
        echo "ℹ️  Database file already exists at: $dbPath\n";
    }

    $db = Database::getInstance()->getPdo();

    $sql = "
        CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            active INTEGER DEFAULT 1,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )
    ";

    $db->exec($sql);
    echo "✅ Users table created\n";

    $stmt = $db->prepare(
        "
        INSERT OR IGNORE INTO users (name, email, password, active) 
        VALUES (?, ?, ?, ?)
    "
    );

    $testUsers = [
        ['John Doe', 'john@example.com', password_hash('password123', PASSWORD_DEFAULT), 1],
        ['Jane Smith', 'jane@example.com', password_hash('password456', PASSWORD_DEFAULT), 1],
        ['Bob Johnson', 'bob@example.com', password_hash('password789', PASSWORD_DEFAULT), 0],
    ];

    foreach ($testUsers as $user) {
        $stmt->execute($user);
    }

    echo "✅ Test data added\n";
    echo "🎉 Done! You can now test the functionality\n";
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
}
