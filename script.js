$(document).ready(function () {
    $(".delete-button").click(function () {
        let btn = $(this);
        let productId = btn.data("id");

        if (confirm("Вы уверены, что хотите удалить этот товар?")) {
            $.ajax({
                url: "delete_product.php",
                type: "POST",
                data: { id: productId },
                success: function (response) {
                    if (response === "success") {
                        btn.addClass("success");
                        setTimeout(function () {
                            btn.closest("tr").fadeOut();
                        }, 1000);
                    } else {
                        alert("Ошибка при удалении товара");
                    }
                }
            });
        }
    });
    // Закрытие попапа при клике на пустое место
window.addEventListener("click", function (event) {
    if (event.target === popup) {
        popup.style.display = "none";
    }
});

// 🔍 Автопоиск без кнопки
const searchInput = document.getElementById('searchInput');
let timeout = null;

searchInput.addEventListener('input', function () {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        const searchValue = searchInput.value;
        window.location.href = '?search=' + encodeURIComponent(searchValue);
    }, 500); // задержка 0.5 сек
});

});
