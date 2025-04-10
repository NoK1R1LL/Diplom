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
