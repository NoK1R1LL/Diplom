document.addEventListener("DOMContentLoaded", function() {
    // Открытие попапа редактирования товара по клику на строку
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
            document.getElementById("edit-product-name").value = productName;
            document.getElementById("edit-product-description").value = productDescription;
            document.getElementById("edit-product-category").value = productCategory;
            document.getElementById("edit-product-quantity").value = productQuantity;
            document.getElementById("edit-product-shelf").value = productShelf;

            // Открытие попапа
            document.getElementById("popup-edit").style.display = "flex";
        });
    });

    // Закрытие попапа
    document.getElementById("close-popup-edit").addEventListener("click", function() {
        document.getElementById("popup-edit").style.display = "none";
    });

    // Закрытие попапа при клике вне его области
    window.addEventListener("click", function(event) {
        if (event.target === document.getElementById("popup-edit")) {
            document.getElementById("popup-edit").style.display = "none";
        }
    });
});
