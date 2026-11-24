
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    if (!meta) {
        throw new Error('CSRF-токен не найден в <meta name="csrf-token">');
    }
    return meta.getAttribute('content');
}

/**
 * ЕДИНАЯ функция загрузки результатов каталога через AJAX.
 * Заменяет 4 дублирующихся блока (пагинация / поиск / сортировка / фильтры).
 *
 * @param {string} url            — адрес запроса
 * @param {number} scrollPosition — куда вернуть скролл после загрузки
 * @param {Function} [onAfterUpdate] — колбэк с распарсенным doc (для доп. обновлений, напр. заголовка сортировки)
 */
function loadCatalog(url, scrollPosition = 0, onAfterUpdate = null) {
    return fetch(url)
        .then(function (response) {
            if (!response.ok) {
                throw new Error('HTTP-ошибка: ' + response.status);
            }
            return response.text();
        })
        .then(function (html) {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newContainer = doc.querySelector('#catalog-results');
            const oldContainer = document.querySelector('#catalog-results');

            if (!newContainer || !oldContainer) {
                throw new Error('Контейнер #catalog-results не найден в ответе');
            }

            oldContainer.innerHTML = newContainer.innerHTML;

            if (typeof onAfterUpdate === 'function') {
                onAfterUpdate(doc);
            }

            window.scrollTo(0, scrollPosition);
            history.pushState({}, '', url);
            
            // 🆕 Синхронизируем слайдер с новым URL
            if (typeof window.syncSliderWithUrl === 'function') {
                window.syncSliderWithUrl();
            }
        })
        .catch(function (error) {
            console.error('Ошибка загрузки каталога:', error);
            window.location.href = url;
        });
}
// =========================================================================
// 1. AJAX-ПАГИНАЦИЯ
// =========================================================================
document.addEventListener('click', function (e) {
    const link = e.target.closest('.catalog__pagination a');
    if (!link) return;
    e.preventDefault();
    loadCatalog(link.getAttribute('href'), window.scrollY);
});

// =========================================================================
// 2. AJAX-ПОИСК
// =========================================================================
const searchForm = document.getElementById('search-form');
if (searchForm) {
    searchForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const params = new URLSearchParams(new FormData(searchForm)).toString();
        loadCatalog(searchForm.action + '?' + params, window.scrollY);
    });
}

// =========================================================================
// 3. AJAX-СОРТИРОВКА
// =========================================================================
document.addEventListener('click', function (e) {
    const sortOption = e.target.closest('.sort__options p');
    if (!sortOption) return;

    const sort = sortOption.getAttribute('data-sort');
    if (!sort) return;

    const url = new URL(window.location.href);
    url.searchParams.set('sort', sort);
    url.searchParams.delete('page');

    loadCatalog(url.toString(), window.scrollY, function (doc) {
        const newTitle = doc.querySelector('.sort__title p');
        const oldTitle = document.querySelector('.sort__title p');
        if (newTitle && oldTitle) {
            oldTitle.textContent = newTitle.textContent;
        }
    });
});

// =========================================================================
// 4. ДВОЙНОЙ СЛАЙДЕР ИГРОКОВ (mouse + touch)
// =========================================================================
const sliderContainer = document.querySelector('.slider__container');
if (sliderContainer) {
    const track       = document.getElementById('slider-track');
    const leftHandle  = document.getElementById('slider-left');
    const rightHandle = document.getElementById('slider-right');
    const label       = document.getElementById('slider-label');
    const inputMin    = document.getElementById('players-min');
    const inputMax    = document.getElementById('players-max');

    const MIN = 1;
    const MAX = 18;
    const DEFAULT_MIN = 2;
    const DEFAULT_MAX = 6;

    let currentMin = parseInt(inputMin.value, 10) || DEFAULT_MIN;
    let currentMax = parseInt(inputMax.value, 10) || DEFAULT_MAX;

    function updateSlider() {
        const leftPos  = ((currentMin - MIN) / (MAX - MIN)) * 100;
        const rightPos = ((currentMax - MIN) / (MAX - MIN)) * 100;

        leftHandle.style.left  = leftPos + '%';
        rightHandle.style.left = rightPos + '%';
        track.style.left  = leftPos + '%';
        track.style.right = (100 - rightPos) + '%';

        label.textContent = currentMin + '-' + currentMax;
        inputMin.value = currentMin;
        inputMax.value = currentMax;
    }

    /**
     * 🆕 Синхронизирует слайдер с параметрами URL.
     * Вызывается после каждого AJAX-обновления каталога.
     */
    function syncSliderWithUrl() {
        const urlParams = new URLSearchParams(window.location.search);
        const urlMin = parseInt(urlParams.get('players_min'), 10);
        const urlMax = parseInt(urlParams.get('players_max'), 10);

        if (!isNaN(urlMin) && urlMin >= MIN && urlMin <= MAX) {
            currentMin = urlMin;
        }
        if (!isNaN(urlMax) && urlMax >= MIN && urlMax <= MAX) {
            currentMax = urlMax;
        }

        // Защита: если min >= max, корректируем
        if (currentMin >= currentMax) {
            currentMax = currentMin + 1;
        }

        updateSlider();
    }

    // 🆕 Делаем функцию доступной глобально, чтобы loadCatalog мог её вызвать
    window.syncSliderWithUrl = syncSliderWithUrl;

    function dragHandle(handle, isLeft) {
        function getX(e) {
            return e.touches ? e.touches[0].clientX : e.clientX;
        }

        function onMove(e) {
            const rect = sliderContainer.getBoundingClientRect();
            let percent = (getX(e) - rect.left) / rect.width;
            percent = Math.max(0, Math.min(1, percent));
            const value = Math.round(MIN + percent * (MAX - MIN));

            if (isLeft) {
                if (value >= currentMax) value = currentMax - 1;
                currentMin = value;
            } else {
                if (value <= currentMin) value = currentMin + 1;
                currentMax = value;
            }
            updateSlider();
        }

        function onEnd() {
            document.removeEventListener('mousemove', onMove);
            document.removeEventListener('mouseup', onEnd);
            document.removeEventListener('touchmove', onMove);
            document.removeEventListener('touchend', onEnd);
        }

        function onStart(e) {
            e.preventDefault();
            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onEnd);
            document.addEventListener('touchmove', onMove, { passive: false });
            document.addEventListener('touchend', onEnd);
        }

        handle.addEventListener('mousedown', onStart);
        handle.addEventListener('touchstart', onStart, { passive: false });
    }

    dragHandle(leftHandle, true);
    dragHandle(rightHandle, false);
    updateSlider();
    
    syncSliderWithUrl();
}

// =========================================================================
// 5. AJAX-ФИЛЬТРЫ
// =========================================================================
const applyButton = document.getElementById('apply-filters');
const filterForm  = document.getElementById('filter-form');
if (applyButton && filterForm) {
    applyButton.addEventListener('click', function (e) {
        e.preventDefault();

        const params = new URLSearchParams();
        for (const [key, value] of new FormData(filterForm).entries()) {
            if (value !== '') params.append(key, value);
        }

        loadCatalog(window.location.pathname + '?' + params.toString(), window.scrollY);
    });
}

// =========================================================================
// 6. ИЗБРАННОЕ — ОДИН делегат вместо двух
// =========================================================================
document.addEventListener('click', function (e) {
    const icon = e.target.closest('.favorite-icon');
    const removeIcon = e.target.closest('.remove-favorite');

    const target = icon || removeIcon;
    if (!target) return;
    e.preventDefault();

    const gameId = target.getAttribute('data-game-id');
    const card = removeIcon ? removeIcon.closest('.catalog__cart') : null;

    fetch('/favorite/toggle/' + gameId, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': getCsrfToken(),
            'Content-Type': 'application/json'
        }
    })
        .then(function (response) {
            if (!response.ok) throw new Error('HTTP-ошибка: ' + response.status);
            return response.json();
        })
        .then(function (data) {
            if (data.status === 'added' && icon) {
                icon.classList.add('favorite-icon--active');
                icon.setAttribute('title', 'Убрать из избранного');
            }

            if (data.status === 'removed') {
                if (icon) {
                    icon.classList.remove('favorite-icon--active');
                    icon.setAttribute('title', 'Добавить в избранное');
                }
                if (card) {
                    card.style.transition = 'opacity 0.3s, transform 0.3s';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.9)';
                    setTimeout(function () { card.remove(); }, 300);
                }
            }
        })
        .catch(function (error) {
            console.error('Ошибка избранного:', error);
        });
});

// =========================================================================
// 7. СЛУЧАЙНАЯ ИГРА
// =========================================================================
const randomBtn = document.getElementById('random-game-btn');
if (randomBtn) {
    randomBtn.addEventListener('click', function (e) {
        e.preventDefault();

        fetch('/game/random')
            .then(function (response) {
                if (!response.ok) throw new Error('HTTP-ошибка: ' + response.status);
                return response.json();
            })
            .then(function (data) {
                if (data.id) {
                    window.location.href = '/game/' + data.id;
                } else {
                    alert('Игр пока нет в базе');
                }
            })
            .catch(function (error) {
                console.error('Ошибка случайной игры:', error);
                alert('Не удалось загрузить случайную игру');
            });
    });
}