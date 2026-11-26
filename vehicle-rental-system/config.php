<?php
// config.php - database settings and one PDO connection named $pdo.
// Change $username and $password to match your own MySQL server.
// (XAMPP default: user "root" with an empty password.)
$host = 'localhost';
$dbName = 'vehicle_rental_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    exit('Database connection failed. Check config.php.');
}
