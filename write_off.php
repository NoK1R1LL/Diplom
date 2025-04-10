<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $barcode = $_POST['barcode'];
    $quantity = $_POST['quantity'];

    // Проверка на корректность введенных данных
    if (!empty($barcode) && is_numeric($quantity) && $quantity > 0) {
        // Получаем товар по штрих-коду
        $sql = "SELECT * FROM products WHERE barcode = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("s", $barcode);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($row = $result->fetch_assoc()) {
            // Если товар найден, уменьшаем количество
            $newQuantity = $row['quantity'] - $quantity;

            if ($newQuantity >= 0) {
                // Обновляем количество товара в базе данных
                $updateSql = "UPDATE products SET quantity = ? WHERE barcode = ?";
                $updateStmt = $mysqli->prepare($updateSql);
                $updateStmt->bind_param("is", $newQuantity, $barcode);
                $updateStmt->execute();

                echo "Товар списан. Новое количество: " . $newQuantity;
            } else {
                echo "Недостаточно товара для списания.";
            }
        } else {
            echo "Товар с таким штрих-кодом не найден.";
        }
    } else {
        echo "Введите корректные данные.";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Выписка товара</title>
    <link rel="stylesheet" href="style/styles.css">
</head>
<body>

<div class="main-container">
    <div class="body-column">
        <h2>Выписка товара</h2>
        
        <form method="POST" action="write_off.php">
            <label for="barcode">Штрих-код:</label>
            <input type="text" id="barcode" name="barcode" placeholder="Сканируйте штрих-код" required>
            <br><br>
            <label for="quantity">Количество для списания:</label>
            <input type="number" id="quantity" name="quantity" placeholder="Количество" min="1" required>
            <br><br>
            <button type="submit">Выписать</button>
        </form>
        
        <a href="index.php" class="back-button">Назад</a>
    </div>
</div>

</body>
</html>
