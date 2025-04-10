<?php
error_reporting(0); // Отключаем все ошибки
ini_set('display_errors', 0); // Отключаем отображение ошибок на экране
ini_set('log_errors', 1); // Включаем логирование ошибок
ini_set('error_log', 'path/to/your/error_log.txt'); // Указываем путь к лог-файлу

include('db.php');

// Фильтрация по поиску
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

// Получаем уникальные наименования складов и количество занятых полок
$sql = "SELECT 
            s.id,
            s.name AS shelf_name, 
            COUNT(p.shelf_id) AS occupied_shelves, 
            COALESCE(s.total_shelves, 0) AS total_shelves
        FROM shelves s
        LEFT JOIN products p ON s.id = p.shelf_id
        WHERE s.name LIKE ?
        GROUP BY s.name
        ORDER BY FIELD(s.name, 'Малый склад', 'Большой склад')";

$stmt = $mysqli->prepare($sql);
$searchParam = "%$search%";
$stmt->bind_param("s", $searchParam);
$stmt->execute();
$result = $stmt->get_result();

// Обработка формы для добавления склада
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['shelfName']) && isset($_POST['totalShelves'])) {
    $shelfName = $_POST['shelfName'];
    $totalShelves = $_POST['totalShelves'];

    // Вставка нового склада в базу данных
    $insertQuery = "INSERT INTO shelves (name, total_shelves) VALUES (?, ?)";
    $stmt = $mysqli->prepare($insertQuery);
    $stmt->bind_param("si", $shelfName, $totalShelves);
    
    if ($stmt->execute()) {
        echo "<script>alert('Склад успешно добавлен!');</script>";
    } else {
        echo "<script>alert('Ошибка при добавлении склада!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <title>Места хранения</title>
    <link rel="stylesheet" href="style/styles.css">
    <script src="search-shelf.js" defer></script>
    <script src="popup-add_shelf.js" defer></script>
    <script src="popup-edit_shelf.js" defer></script>

    <script>
        // Функция для автоматического поиска
        function searchStorage() {
            let searchValue = document.getElementById('searchInput').value.trim();
            if (searchValue !== "") {
                window.location.href = '?search=' + encodeURIComponent(searchValue); // Перезагружаем страницу с параметром поиска
            } else {
                window.location.href = 'storage.php'; // Если поле пустое, очищаем фильтр
            }
        }

        // Событие на input для поиска
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            searchInput.addEventListener('input', function(event) {
                searchStorage(); // Активируем поиск по мере ввода
            });
        });
    </script>
</head>

<body>

    <div class="main-container">
        <div class="header-column">
            <div class="logo">
                <img src="uploads/logo/logo.png" alt="Логотип">
            </div>
            <div class="header-items">
                <h2 onclick="window.location.href='index.php'">Товары</h2>
                <h2 onclick="window.location.href='storage.php'">Места хранения</h2>
            </div>
        </div>

        <div class="body-column">
            <div class="tabs">
                <div class="tab active">Места хранения</div>
            </div>

            <div class="product-section">
                <div class="search-filter">
                    <input type="text" id="searchInput" placeholder="Поиск..." value="<?= htmlspecialchars($search) ?>">
                </div>
                <div class="action-buttons">
                    <a href="#" class="create-button" id="openCreateShelfPopup">Создать склад</a>
                </div>
            </div>

            <table>
                <tr>
                    <th>№ п/п</th>
                    <th>Наименование</th>
                    <th>Занято полок</th>
                </tr>

                <?php
                $counter = 1;
                while ($row = $result->fetch_assoc()):
                    $shelfName = htmlspecialchars($row['shelf_name']);
                    $occupiedShelves = $row['occupied_shelves'];
                    $totalShelves = $row['total_shelves'];
                ?>
                    <tr class="storage-row"
                        data-shelf-id="<?= $row['id'] ?>"
                        data-shelf-name="<?= htmlspecialchars($row['shelf_name']) ?>"
                        data-occupied-shelves="<?= $row['occupied_shelves'] ?> / <?= $row['total_shelves'] ?>"
                        data-max-shelves="<?= $row['total_shelves'] ?>">
                        <td><?= $counter++ ?></td>
                        <td><?= htmlspecialchars($row['shelf_name']) ?></td>
                        <td><?= $row['occupied_shelves'] ?> / <?= $row['total_shelves'] ?></td>
                    </tr>

                <?php endwhile; ?>

            </table>
        </div>
    </div>

    <div id="createShelfPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <h2>Создать новый склад</h2>
            <form method="POST" id="createShelfForm">
                <input type="text" id="shelfName" name="shelfName" placeholder="Наименование склада" required>
                <input type="number" id="totalShelves" name="totalShelves" placeholder="Количество полок" required>
                <button type="submit">Добавить склад</button>
            </form>
        </div>
    </div>

    <div id="storageDetailsPopup" class="popup">
        <div id="storageDetailsContent" class="popup-content">
            <span class="close">&times;</span>
        </div>
    </div>

    <div id="editShelfPopup" class="popup">
        <div class="popup-content">
            <span class="close">&times;</span>
            <h2>Редактирование склада</h2>
            <form id="editShelfForm">
                <label for="editShelfName">Название:</label>
                <input type="text" id="editShelfName" name="name">
                <label for="editShelfQuantity">Количество полок:</label>
                <input type="number" id="editShelfQuantity" name="quantity">
                <button type="submit">Сохранить изменения</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const popup = document.getElementById("storageDetailsPopup");
            const popupContent = document.getElementById("storageDetailsContent");
            const closeBtn = document.querySelector("#storageDetailsPopup .close");
            const editShelfPopup = document.getElementById("editShelfPopup");
            const closeEditBtn = document.querySelector("#editShelfPopup .close");
            let currentShelfId = 0;
            let currentShelfName = "";
            let currentMaxShelves = 0;

            function openPopup(shelfName, occupiedShelves, maxShelves, shelfId) {
                currentShelfId = shelfId;
                currentShelfName = shelfName;
                currentMaxShelves = maxShelves;

                popupContent.innerHTML = ` 
                <h2>${shelfName}</h2>
                <p>Занято полок: ${occupiedShelves}</p>
                <div class="popup-button-container">
                    <button id="editShelfButton" class="popup-button">Редактировать</button>
                    <button id="deleteShelfButton" class="popup-button">Удалить</button>
                </div>
            `;

                popup.style.display = "flex";

                document.getElementById("editShelfButton").addEventListener("click", openEditPopup);
                document.getElementById("deleteShelfButton").addEventListener("click", deleteShelf);
            }

            function openEditPopup() {
                document.getElementById("editShelfName").value = currentShelfName;
                document.getElementById("editShelfQuantity").value = currentMaxShelves;
                editShelfPopup.style.display = "flex";
            }

            closeBtn.addEventListener("click", () => {
                popup.style.display = "none";
            });

            closeEditBtn.addEventListener("click", () => {
                editShelfPopup.style.display = "none";
            });

            document.getElementById("editShelfForm").addEventListener("submit", function(event) {
                event.preventDefault();

                const name = document.getElementById("editShelfName").value;
                const quantity = document.getElementById("editShelfQuantity").value;

                fetch("update_shelf.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `shelf_id=${currentShelfId}&name=${name}&quantity=${quantity}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        alert(data);
                        editShelfPopup.style.display = "none";
                        window.location.reload();
                    })
                    .catch(error => console.error("Ошибка:", error));
            });

            function deleteShelf() {
                if (confirm("Вы уверены, что хотите удалить этот склад?")) {
                    fetch("delete_shelf.php", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/x-www-form-urlencoded"
                            },
                            body: `shelf_id=${currentShelfId}`
                        })
                        .then(response => response.text())
                        .then(data => {
                            alert(data);
                            window.location.reload();
                        })
                        .catch(error => console.error("Ошибка:", error));
                }
            }

            document.querySelectorAll(".storage-row").forEach(row => {
                row.addEventListener("click", function() {
                    const shelfName = this.dataset.shelfName;
                    const occupiedShelves = this.dataset.occupiedShelves;
                    const maxShelves = this.dataset.maxShelves;
                    const shelfId = this.dataset.shelfId;
                    openPopup(shelfName, occupiedShelves, maxShelves, shelfId);
                });
            });

            window.addEventListener("click", function(event) {
                if (event.target === popup) {
                    popup.style.display = "none";
                }
            });
        });
    </script>

</body>

</html>
