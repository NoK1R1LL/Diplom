<?php
include('db.php');

// Проверка на наличие переданного ID склада
if (isset($_POST['shelf_id']) && is_numeric($_POST['shelf_id'])) {
    $shelfId = $_POST['shelf_id'];

    // Выполнение запроса на удаление склада
    $sql = "DELETE FROM shelves WHERE id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $shelfId);

    if ($stmt->execute()) {
        echo "Склад успешно удален!";
    } else {
        echo "Ошибка при удалении склада!";
    }

    $stmt->close();
} else {
    echo "Некорректный ID склада!";
}
?>
