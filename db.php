<?php
$host = 'localhost';
$dbname = 'warehouse';
$user = 'root';
$pass = '';

// Подключение через MySQLi
$mysqli = new mysqli($host, $user, $pass, $dbname);

// Проверка соединения
if ($mysqli->connect_error) {
    die("Ошибка подключения: " . $mysqli->connect_error);
}?>
