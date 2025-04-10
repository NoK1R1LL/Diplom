<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shelfId = $_POST['shelf_id'];
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];

    $sql = "UPDATE shelves SET name = ?, total_shelves = ? WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('sii', $name, $quantity, $shelfId);
    if ($stmt->execute()) {
        echo "Склад обновлен успешно!";
    } else {
        echo "Ошибка при обновлении склада.";
    }
}
?>
