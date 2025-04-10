<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $total_shelves = $_POST['total_shelves'];

    if ($name && $total_shelves) {
        // Запрос на добавление склада в БД
        $stmt = $mysqli->prepare("INSERT INTO shelves (name, total_shelves) VALUES (?, ?)");
        $stmt->bind_param("si", $name, $total_shelves);
        $stmt->execute();
        echo "Склад добавлен успешно!";
    } else {
        echo "Все поля обязательны для заполнения!";
    }
}
?>
