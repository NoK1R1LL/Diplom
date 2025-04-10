document.addEventListener("DOMContentLoaded", function () {
    // Обработчик для кнопки "Удалить"
    const deleteButtons = document.querySelectorAll(".delete-button");

    deleteButtons.forEach(button => {
        button.addEventListener("click", function (event) {
            const shelfId = this.closest('.storage-row').dataset.shelfId; // Получаем ID склада

            // Подтверждение перед удалением
            if (confirm("Вы уверены, что хотите удалить этот склад?")) {
                // Отправка запроса на удаление через fetch
                fetch("delete_shelf.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: `shelf_id=${shelfId}`
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); // Выводим сообщение от сервера
                    window.location.reload(); // Перезагружаем страницу, чтобы отобразились обновленные данные
                })
                .catch(error => console.error("Ошибка:", error));
            }
        });
    });
});
