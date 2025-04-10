<?php
include('db.php'); // Подключаем файл с данными для соединения с БД

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id']; // ID продукта
    $productName = $_POST['name'];
    $productDescription = $_POST['description'];
    $productCategory = $_POST['category'];
    $productQuantity = $_POST['quantity'];
    $productShelf = $_POST['shelf'];
    $newPhoto = $_FILES['photo']; // Файл фотографии

    // Проверка на обязательные поля
    if (empty($productName) || empty($productDescription) || empty($productCategory) || empty($productQuantity) || empty($productShelf)) {
        echo "Пожалуйста, заполните все обязательные поля.";
        exit;
    }

    // Обработка загрузки фото
    $photoPath = null;
    if (!empty($newPhoto['name'])) {
        $targetDir = 'uploads/';
        $targetFile = $targetDir . basename($newPhoto['name']);
        $fileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

        // Проверка типа файла (например, изображение)
        if (!in_array($fileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            echo "Ошибка: поддерживаются только изображения (.jpg, .jpeg, .png, .gif).";
            exit;
        }

        // Ограничение на размер файла (например, 5MB)
        if ($newPhoto['size'] > 5000000) {
            echo "Ошибка: файл слишком большой (максимум 5MB).";
            exit;
        }

        // Перемещаем загруженный файл в папку uploads
        if (move_uploaded_file($newPhoto['tmp_name'], $targetFile)) {
            $photoPath = $targetFile;
        } else {
            echo "Ошибка при загрузке файла.";
            exit;
        }
    }

    // Обновляем запись в базе данных
    $sql = "UPDATE products SET name=?, description=?, category_id=?, quantity=?, shelf_id=?";
    if ($photoPath) {
        $sql .= ", photo=?";
    }
    $sql .= " WHERE id=?";

    $stmt = $mysqli->prepare($sql);

    // Привязываем параметры в зависимости от наличия фото
    if ($photoPath) {
        $stmt->bind_param("ssiiisi", $productName, $productDescription, $productCategory, $productQuantity, $productShelf, $photoPath, $productId);
    } else {
        $stmt->bind_param("ssiiii", $productName, $productDescription, $productCategory, $productQuantity, $productShelf, $productId);
    }

    if ($stmt->execute()) {
        echo "Изменения сохранены.";
    } else {
        echo "Ошибка: " . $stmt->error;
    }
}
