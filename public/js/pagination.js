document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        // Проверяем, что клик был по ссылке внутри .catalog__pagination
        const link = e.target.closest('.catalog__pagination a');
        if (!link) return;

        // Отменяем стандартный переход по ссылке
        e.preventDefault();

        // Запоминаем текущую позицию скролла
        const scrollPosition = window.scrollY;

        // Берём URL из ссылки (например, ?page=2)
        const url = link.getAttribute('href');

        // AJAX-запрос
        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newContainer = doc.querySelector('#catalog-results');
                const oldContainer = document.querySelector('#catalog-results');

                if (newContainer && oldContainer) {
                    oldContainer.innerHTML = newContainer.innerHTML;
                    // Восстанавливаем позицию скролла (без прыжков)
                    window.scrollTo(0, scrollPosition);
                }
            })
            .catch(error => {
                console.error('Ошибка AJAX-запроса:', error);
                // Если AJAX упал — переходим по ссылке обычным путём
                window.location.href = url;
            });
    });
});