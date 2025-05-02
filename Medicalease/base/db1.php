<?php
$dsn = 'mysql:host=localhost;dbname=medical_ease;charset=utf8mb4'; 
$user = 'root';
$passwd = '';

try {
    $conn = new PDO($dsn, $user, $passwd, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}