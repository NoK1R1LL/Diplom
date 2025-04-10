document.addEventListener("DOMContentLoaded", function () {
    const createShelfPopup = document.getElementById("createShelfPopup");
    const closeCreateBtn = createShelfPopup.querySelector(".close");
    const createShelfForm = document.getElementById("createShelfForm");

    // Открытие попапа создания склада
    document.getElementById("openCreateShelfPopup").addEventListener("click", function () {
        createShelfPopup.style.display = "flex";
    });

    // Закрытие попапа
    closeCreateBtn.addEventListener("click", function () {
        createShelfPopup.style.display = "none";
    });

    // Обработчик формы создания склада
    createShelfForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const shelfName = document.getElementById("shelfName").value;
        const totalShelves = document.getElementById("totalShelves").value;

        fetch("add_shelf.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `name=${shelfName}&total_shelves=${totalShelves}`,
        })
            .then(response => response.text())
            .then(data => {
                alert(data);
                createShelfPopup.style.display = "none";
                window.location.reload();
            })
            .catch(error => console.error("Ошибка:", error));
    });
});
