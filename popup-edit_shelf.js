document.addEventListener("DOMContentLoaded", function () {
    const popup = document.getElementById("storageDetailsPopup");
    const popupContent = document.getElementById("storageDetailsContent");
    const closeBtn = document.querySelector("#storageDetailsPopup .close");
    const editShelfPopup = document.getElementById("editShelfPopup");
    const closeEditBtn = document.querySelector("#editShelfPopup .close");

    let currentShelfId = 0;
    let currentShelfName = "";
    let currentMaxShelves = 0;

    // Открытие попапа с информацией о складе
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

    // Открытие попапа для редактирования
    function openEditPopup() {
        document.getElementById("editShelfName").value = currentShelfName;
        document.getElementById("editShelfQuantity").value = currentMaxShelves;
        editShelfPopup.style.display = "flex";
    }

    // Закрытие попапа
    closeBtn.addEventListener("click", () => popup.style.display = "none");
    closeEditBtn.addEventListener("click", () => editShelfPopup.style.display = "none");

    // Обработчик формы редактирования
    document.getElementById("editShelfForm").addEventListener("submit", function (event) {
        event.preventDefault();

        const name = document.getElementById("editShelfName").value;
        const quantity = document.getElementById("editShelfQuantity").value;

        fetch("update_shelf.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `shelf_id=${currentShelfId}&name=${name}&quantity=${quantity}`,
        })
            .then(response => response.text())
            .then(data => {
                alert(data);
                editShelfPopup.style.display = "none";
                window.location.reload();
            })
            .catch(error => console.error("Ошибка:", error));
    });

    // Удаление склада из базы данных
    function deleteShelf() {
        if (confirm("Вы уверены, что хотите удалить этот склад?")) {
            fetch("delete_shelf.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `shelf_id=${currentShelfId}`,
            })
                .then(response => response.text())
                .then(data => {
                    alert(data);
                    window.location.reload();
                })
                .catch(error => console.error("Ошибка:", error));
        }
    }

    // Открытие попапа при клике на строку таблицы
    document.querySelectorAll(".storage-row").forEach(row => {
        row.addEventListener("click", function () {
            const shelfName = this.dataset.shelfName;
            const occupiedShelves = this.dataset.occupiedShelves;
            const maxShelves = this.dataset.maxShelves;
            const shelfId = this.dataset.shelfId;
            openPopup(shelfName, occupiedShelves, maxShelves, shelfId);
        });
    });

    // Закрытие попапа при клике на пустое место
    window.addEventListener("click", function (event) {
        if (event.target === popup) {
            popup.style.display = "none";
        }
    });
});
