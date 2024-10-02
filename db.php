<?php
$host = 'localhost';
$db_name = 'income_expenses_management';
$username = 'newuser';
$password = 'mysql';
$port = '3307';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db_name", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database: " . $e->getMessage());
}
?>
