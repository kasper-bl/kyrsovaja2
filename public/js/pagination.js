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

    // --- AJAX-поиск ---
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

    // --- AJAX-сортировка ---
    document.addEventListener('click', function (e) {
        const sortOption = e.target.closest('.sort__options p');
        if (!sortOption) return;
        const sort = sortOption.getAttribute('data-sort');
        if (!sort) return;
        const url = new URL(window.location.href);
        url.searchParams.set('sort', sort);
        url.searchParams.delete('page');
        const scrollPosition = window.scrollY;
        fetch(url.toString())
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContainer = doc.querySelector('#catalog-results');
                const oldContainer = document.querySelector('#catalog-results');
                if (newContainer && oldContainer) {
                    oldContainer.innerHTML = newContainer.innerHTML;
                    const newTitle = doc.querySelector('.sort__title p');
                    const oldTitle = document.querySelector('.sort__title p');
                    if (newTitle && oldTitle) {
                        oldTitle.textContent = newTitle.textContent;
                    }
                    window.scrollTo(0, scrollPosition);
                    history.pushState({}, '', url.toString());
                }
            })
            .catch(error => {
                console.error('Ошибка сортировки:', error);
                window.location.href = url.toString();
            });
    });

    // --- Слайдер игроков ---
    const sliderContainer = document.querySelector('.slider__container');
    if (sliderContainer) {
        const track = document.getElementById('slider-track');
        const leftHandle = document.getElementById('slider-left');
        const rightHandle = document.getElementById('slider-right');
        const label = document.getElementById('slider-label');
        const inputMin = document.getElementById('players-min');
        const inputMax = document.getElementById('players-max');

        const MIN = 1;
        const MAX = 18;

        let currentMin = parseInt(inputMin.value) || 2;
        let currentMax = parseInt(inputMax.value) || 6;

        function updateSlider() {
            const containerWidth = sliderContainer.offsetWidth;
            const leftPos = ((currentMin - MIN) / (MAX - MIN)) * 100;
            const rightPos = ((currentMax - MIN) / (MAX - MIN)) * 100;

            leftHandle.style.left = leftPos + '%';
            rightHandle.style.left = rightPos + '%';
            track.style.left = leftPos + '%';
            track.style.right = (100 - rightPos) + '%';

            label.textContent = currentMin + '-' + currentMax;
            inputMin.value = currentMin;
            inputMax.value = currentMax;
        }

        function dragHandle(handle, isLeft) {
            function onMouseMove(e) {
                const rect = sliderContainer.getBoundingClientRect();
                let percent = (e.clientX - rect.left) / rect.width;
                percent = Math.max(0, Math.min(1, percent));
                let value = Math.round(MIN + percent * (MAX - MIN));

                if (isLeft) {
                    if (value >= currentMax) value = currentMax - 1;
                    currentMin = value;
                } else {
                    if (value <= currentMin) value = currentMin + 1;
                    currentMax = value;
                }
                updateSlider();
            }

            function onMouseUp() {
                document.removeEventListener('mousemove', onMouseMove);
                document.removeEventListener('mouseup', onMouseUp);
            }

            handle.addEventListener('mousedown', function () {
                document.addEventListener('mousemove', onMouseMove);
                document.addEventListener('mouseup', onMouseUp);
            });
        }

        dragHandle(leftHandle, true);
        dragHandle(rightHandle, false);
        updateSlider();
    }

    // --- AJAX-фильтры ---
    const applyButton = document.getElementById('apply-filters');
    const filterForm = document.getElementById('filter-form');
    if (applyButton && filterForm) {
        applyButton.addEventListener('click', function (e) {
            e.preventDefault();

            const formData = new FormData(filterForm);
            const params = new URLSearchParams();

            // Массив categories[]
            for (const [key, value] of formData.entries()) {
                if (value !== '') {
                    params.append(key, value);
                }
            }

            const url = window.location.pathname + '?' + params.toString();
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
                    console.error('Ошибка фильтров:', error);
                    window.location.href = url;
                });
        });
    }

});