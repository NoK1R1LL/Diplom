<?php
include('db.php');

$sql = "SELECT 
            p.id,
            p.photo, 
            p.name, 
            p.description, 
            c.name AS category, 
            p.quantity, 
            s.shelf_number AS shelf
        FROM products p
        JOIN categories c ON p.category_id = c.id
        JOIN shelves s ON p.shelf_id = s.id";

$result = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Список товаров</title>
    <link rel="stylesheet" href="style/styles.css">
    <script src="popup.js" defer></script>
    <script src="popup-edit.js" defer></script>
</head>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const popup = document.getElementById("barcodePopup");
        const openPopup = document.getElementById("barcode-button");
        const closePopup = document.querySelector(".close");

        // Открытие попапа
        openPopup.addEventListener("click", function() {
            popup.style.display = "flex";
        });

        // Закрытие попапа при клике на "×"
        closePopup.addEventListener("click", function() {
            popup.style.display = "none";
        });

        // Закрытие попапа при клике вне его области
        window.addEventListener("click", function(event) {
            if (event.target === popup) {
                popup.style.display = "none";
            }
        });

        // Открытие попапа для редактирования товара по клику на строку
        const rows = document.querySelectorAll("table tr[data-id]");
        rows.forEach(row => {
            row.addEventListener("click", function() {
                const productId = this.getAttribute("data-id");
                const productName = this.querySelector(".product-name").textContent;
                const productDescription = this.querySelector(".product-description").textContent;
                const productCategory = this.querySelector(".product-category").textContent;
                const productQuantity = this.querySelector(".product-quantity").textContent;
                const productShelf = this.querySelector(".product-shelf").textContent;

                // Заполнение формы редактирования
                document.getElementById("edit-product-id").value = productId;
                document.getElementById("edit-product-name").value = productName;
                document.getElementById("edit-product-description").value = productDescription;
                document.getElementById("edit-product-category").value = productCategory;
                document.getElementById("edit-product-quantity").value = productQuantity;
                document.getElementById("edit-product-shelf").value = productShelf;

                // Показать попап
                document.getElementById("editProductPopup").style.display = "flex";
            });
        });
    });
</script>

<body>

    <div class="main-container">
        <!-- Первая колонка (header) -->
        <div class="header-column">
            <!-- Логотип -->
            <div class="logo">
                <img src="uploads/logo/logo.png" alt="Логотип">
            </div>
            <!-- Элементы "Товары" и "Места хранения" -->
            <div class="header-items">
            <h2 onclick="window.location.href='index.php'">Товары</h2>
            <h2 onclick="window.location.href='storage.php'">Места хранения</h2>
            </div>
        </div>

        <!-- Вторая колонка (body) -->
        <div class="body-column">
            <!-- Вкладки навигации -->
            <div class="tabs">
                <div class="tab active">Товары</div>
                <div class="tab">Категории</div>
            </div>

            <!-- Секция товаров с поиском и фильтрацией -->
            <div class="product-section">
                <div class="search-filter">
                    <input type="text" placeholder="Поиск...">
                    <select>
                        <option>Фильтрация</option>
                        <option>Полка</option>
                    </select>
                </div>

                <div class="action-buttons">
                    <a href="#" id="barcode-button" class="barcode-button">Штрих-код</a>
                    <a href="export_excel.php" class="export-button">ЭКСПОРТ</a>
                    <a href="write_off.php" class="write-off-button">Выписать</a>
                    <a href="add_product.php" class="create-button">Создать</a>
                </div>
            </div>

            <!-- Таблица товаров -->
            <table>
                <tr>
                    <th>Фото</th>
                    <th>Название</th>
                    <th>Описание</th>
                    <th>Категория</th>
                    <th>Количество</th>
                    <th>Полка</th>
                </tr>

                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr data-id="<?= $row['id'] ?>" class="table-row">
                        <td>
                            <?php
                            $photoPath = 'uploads/' . basename($row['photo']);
                            if (!empty($row['photo']) && file_exists($photoPath)): ?>
                                <img src="<?= $photoPath ?>" class="product-img">
                            <?php else: ?>
                                <img src="img/no-image.png" class="product-img">
                            <?php endif; ?>
                        </td>
                        <td class="product-name"><?= htmlspecialchars($row['name']) ?></td>
                        <td class="product-description"><?= htmlspecialchars($row['description']) ?></td>
                        <td class="product-category"><?= htmlspecialchars($row['category']) ?></td>
                        <td class="product-quantity"><?= htmlspecialchars($row['quantity']) ?></td>
                        <td class="product-shelf"><?= htmlspecialchars($row['shelf']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>

    <!-- Попап для выбора товара -->
    <div id="barcodePopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <h2>Выберите товар</h2>
            <select id="product-select">
                <?php
                $productQuery = $mysqli->query("SELECT id, name FROM products");
                while ($product = $productQuery->fetch_assoc()) {
                    echo "<option value='{$product['id']}'>{$product['name']}</option>";
                }
                ?>
            </select>
            <button id="generate-barcode">Генерировать</button>
        </div>
    </div>

    <!-- Попап редактирования -->
    <div class="popup" id="popup-edit">
        <div class="popup-content">
            <span class="close" id="close-popup-edit">&times;</span>
            <h2>Редактировать продукт</h2>
            <div>
                <center><label for="product-photo">Фотография</label>
                <input type="file" id="product-photo" name="photo" accept="image/*"></center>
                <img id="product-photo-preview" src="" alt="Фотография продукта" style="max-width: 100%; display: none; margin-top: 10px;">
            </div>

            <div>
                <label for="edit-product-name">Название</label>
                <input type="text" id="edit-product-name" name="name" required>
            </div>
            <div>
                <label for="edit-product-description">Описание</label>
                <input type="text" id="edit-product-description" name="description" required>
            </div>
            <div>
                <label for="edit-product-category">Категория</label>
                <input type="text" id="edit-product-category" name="category" required>
            </div>
            <div>
                <label for="edit-product-quantity">Количество</label>
                <input type="number" id="edit-product-quantity" name="quantity" required>
            </div>
            <div>
                <label for="edit-product-shelf">Место на складе</label>
                <input type="text" id="edit-product-shelf" name="shelf" required>
            </div>

            <button type="submit" id="edit-save">Сохранить</button>
        </div>
    </div>



</body>

</html>