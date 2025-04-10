document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('product-photo');
    const photoPreview = document.getElementById('product-photo-preview');

    fileInput.addEventListener('change', function(event) {
        const file = event.target.files[0];

        if (file) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                // Устанавливаем изображение в превью
                photoPreview.src = e.target.result;
                photoPreview.style.display = 'block'; // Показываем фото
            };
            
            reader.readAsDataURL(file); // Загружаем фото как DataURL
        }
    });
});
