document.addEventListener('DOMContentLoaded', function () {

    // --- AJAX-пагинация ---
    document.addEventListener('click', function (e) {
        const link = e.target.closest('.catalog__pagination a');
        if (!link) return;

        e.preventDefault();

        const scrollPosition = window.scrollY;
        const url = link.getAttribute('href');

        fetch(url)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newContainer = doc.querySelector('#catalog-results');
                const oldContainer = document.querySelector('#catalog-results');

                if (newContainer && oldContainer) {
                    oldContainer.innerHTML = newContainer.innerHTML;
                    window.scrollTo(0, scrollPosition);
                    history.pushState({}, '', url);
                }
            })
            .catch(error => {
                console.error('Ошибка AJAX-запроса:', error);
                window.location.href = url;
            });
    });

    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(searchForm);
            const params = new URLSearchParams(formData).toString();
            const url = searchForm.action + '?' + params;

            const scrollPosition = window.scrollY;

            fetch(url)
                .then(response => response.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    const newContainer = doc.querySelector('#catalog-results');
                    const oldContainer = document.querySelector('#catalog-results');

                    if (newContainer && oldContainer) {
                        oldContainer.innerHTML = newContainer.innerHTML;
                        window.scrollTo(0, scrollPosition);
                        history.pushState({}, '', url);
                    }
                })
                .catch(error => {
                    console.error('Ошибка поиска:', error);
                    window.location.href = url;
                });
        });
    }

});