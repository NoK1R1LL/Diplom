<?php
include('db.php');

// Обработка данных при добавлении товара
if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $category_id = $_POST['category_id'];
    $quantity = $_POST['quantity'];
    $shelf_id = $_POST['shelf_id']; // Получаем shelf_id из формы

    // Обработка фото
    if (!empty($_FILES['photo']['name'])) {
        $photo = $_FILES['photo']['name'];
        $photo_tmp = $_FILES['photo']['tmp_name'];
        $upload_dir = 'uploads/';
        move_uploaded_file($photo_tmp, $upload_dir . $photo);

        // Вставка товара с фото
        $stmt = $mysqli->prepare("INSERT INTO Products (name, description, category_id, quantity, shelf_id, photo) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiis", $name, $description, $category_id, $quantity, $shelf_id, $photo);
    } else {
        // Вставка товара без фото
        $stmt = $mysqli->prepare("INSERT INTO Products (name, description, category_id, quantity, shelf_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiii", $name, $description, $category_id, $quantity, $shelf_id);
    }

    if ($stmt->execute()) {
        echo "<p>Товар успешно добавлен! <a href='products.php'>Вернуться к списку</a></p>";
    } else {
        echo "Ошибка добавления товара: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Добавить товар</title>
    <link rel="stylesheet" href="style/styleAddProduct.css">
</head>
<body>
    <header>
        <h1>Добавить товар</h1>
        <nav>
            <ul>
                <li><a href="index.php">Список товаров</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <form action="add_product.php" method="POST" enctype="multipart/form-data">
            <label>Название товара:</label>
            <input type="text" name="name" required>

            <label>Описание товара:</label>
            <textarea name="description" required></textarea>

            <label>Категория:</label>
            <select name="category_id" id="category-select" required>
                <option value="">Выберите категорию</option>
                <option value="create_category">Создать категорию</option>
                <?php
                $categories = $mysqli->query("SELECT * FROM Categories");
                while ($category = $categories->fetch_assoc()) {
                    echo "<option value='{$category['id']}'>{$category['name']}</option>";
                }
                ?>
            </select>

            <label>Полка:</label>
            <select name="shelf_id" id="shelf-select" required>
                <option value="">Выберите полку</option>
                <option value="create_shelf">Создать полку</option>
                <?php
                $shelves = $mysqli->query("SELECT * FROM shelves");
                while ($shelf = $shelves->fetch_assoc()) {
                    echo "<option value='{$shelf['id']}'>{$shelf['name']}</option>";
                }
                ?>
            </select>

            <label>Количество:</label>
            <input type="number" name="quantity" required>

            <label>Фото товара:</label>
            <input type="file" name="photo" accept="image/*">

            <button type="submit" name="submit">Добавить товар</button>
        </form>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('category-select');
            const shelfSelect = document.getElementById('shelf-select');

            categorySelect.addEventListener('change', function() {
                if (this.value === 'create_category') openModal('create-category');
            });

            shelfSelect.addEventListener('change', function() {
                if (this.value === 'create_shelf') openModal('create-shelf');
            });
        });

        function openModal(type) {
            document.getElementById(type + '-modal').style.display = 'flex';
        }

        function closeModal(type) {
            document.getElementById(type + '-modal').style.display = 'none';
        }

        function addCategory() {
            var categoryName = document.getElementById('new-category').value;
            if (categoryName) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_category_shelf.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        alert('Категория добавлена!');
                        location.reload();
                    }
                };
                xhr.send('category_name=' + encodeURIComponent(categoryName));
            } else {
                alert('Введите название категории!');
            }
        }

        function addShelf() {
            var shelfName = document.getElementById('new-shelf').value;
            if (shelfName) {
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'add_category_shelf.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        alert('Полка добавлена!');
                        location.reload();
                    }
                };
                xhr.send('shelf_name=' + encodeURIComponent(shelfName));
            } else {
                alert('Введите название полки!');
            }
        }
    </script>
</body>
</html>
