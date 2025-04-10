<?php
include('db.php');

// Проверка, добавляется ли категория
if (isset($_POST['category_name'])) {
    $category_name = $_POST['category_name'];

    // Добавление категории в БД
    $stmt = $mysqli->prepare("INSERT INTO Categories (name) VALUES (?)");
    $stmt->bind_param("s", $category_name);
    if ($stmt->execute()) {
        echo "Категория добавлена успешно!";
    } else {
        echo "Ошибка добавления категории: " . $stmt->error;
    }
}

// Проверка, добавляется ли полка
if (isset($_POST['shelf_name'])) {
    $shelf_name = $_POST['shelf_name'];

    // Добавление полки в БД
    $stmt = $mysqli->prepare("INSERT INTO shelves (name) VALUES (?)");
    $stmt->bind_param("s", $shelf_name);
    if ($stmt->execute()) {
        echo "Полка добавлена успешно!";
    } else {
        echo "Ошибка добавления полки: " . $stmt->error;
    }
}
?>
