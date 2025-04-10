<?php
include('db.php'); // Подключение к базе данных

// Получаем данные из POST-запроса
$shelfName = isset($_POST['shelf_name']) ? trim($_POST['shelf_name']) : '';
$totalShelves = isset($_POST['total_shelves']) ? (int)$_POST['total_shelves'] : 0;

// Проверка, что все данные переданы
if ($shelfName == '' || $totalShelves <= 0) {
    echo "Ошибка: не указаны все необходимые данные!";
    exit;
}

// Вставляем склад в таблицу shelves
$sql = "INSERT INTO shelves (name, total_shelves) VALUES (?, ?)";
$stmt = $mysqli->prepare($sql);
if (!$stmt) {
    echo "Ошибка подготовки запроса: " . $mysqli->error;
    exit;
}

$stmt->bind_param("si", $shelfName, $totalShelves);
if (!$stmt->execute()) {
    echo "Ошибка при выполнении запроса: " . $stmt->error;
    exit;
}

// Получаем ID нового склада
$shelfId = $stmt->insert_id;

// Вставляем полки в таблицу с соответствующими номерами
$sqlInsertShelf = "INSERT INTO products (shelf_id, shelf_number) VALUES (?, ?)";
$stmtInsertShelf = $mysqli->prepare($sqlInsertShelf);

if (!$stmtInsertShelf) {
    echo "Ошибка подготовки запроса для полок: " . $mysqli->error;
    exit;
}

for ($i = 1; $i <= $totalShelves; $i++) {
    $shelfNumber = $i;
    $stmtInsertShelf->bind_param("ii", $shelfId, $shelfNumber);
    if (!$stmtInsertShelf->execute()) {
        echo "Ошибка при добавлении полки с номером $shelfNumber: " . $stmtInsertShelf->error;
        exit;
    }
}

echo "Склад и полки успешно добавлены!";
?>
