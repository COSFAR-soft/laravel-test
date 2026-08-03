import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';
import '@popperjs/core';
import $ from 'jquery';
import axios from 'axios';

//jQuery
window.$ = window.jQuery = $;

// axios
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

// Проверка загрузки
console.log('✅ Vite + jQuery + Bootstrap работает!');
console.log('📦 jQuery version:', $.fn.jquery);
try {
    // Проверяем через импортированный объект
    console.log('📦 Bootstrap version:', bootstrap?.version || 'unknown');

    //  через window
    console.log('📦 Bootstrap (window):', window.bootstrap?.version || 'unknown');

    // Проверяем, что компоненты доступны
    console.log('📦 Bootstrap components:', {
        Modal: typeof bootstrap.Modal !== 'undefined',
        Tooltip: typeof bootstrap.Tooltip !== 'undefined',
        Popover: typeof bootstrap.Popover !== 'undefined',
        Dropdown: typeof bootstrap.Dropdown !== 'undefined'
    });
} catch (e) {
    console.warn('⚠️ Bootstrap не загружен:', e.message);
}



$(document).ready(function() {
    console.log('📄 DOM загружен');

    // Инициализация Bootstrap компонентов
    initBootstrapComponents();

    // скрипты
    initCustomScripts();
});

// Инициализация Bootstrap
function initBootstrapComponents() {
    // Tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    console.log('Bootstrap компоненты инициализированы');
}

// скрипты
function initCustomScripts() {
    // Пример: Обработка форм
    $('form').on('submit', function(e) {
        console.log('Форма отправлена:', $(this).attr('id'));
    });

    // Пример: AJAX запросы
    $('.ajax-button').on('click', function() {
        const $btn = $(this);
        const url = $btn.data('url') || '/api/data';

        $btn.prop('disabled', true).text('Загрузка...');

        axios.get(url)
            .then(function(response) {
                console.log('Данные получены:', response.data);
            })
            .catch(function(error) {
                console.error('Ошибка:', error);
            })
            .finally(function() {
                $btn.prop('disabled', false).text('Готово');
            });
    });

    // Пример: Анимации
    $('.fade-in').each(function(index) {
        $(this).delay(100 * index).fadeIn(500);
    });

    console.log('Кастомные скрипты загружены');
}

// ============================================
// HMR (Hot Module Replacement)
// ============================================
if (import.meta.hot) {
    import.meta.hot.accept(() => {
        console.log('HMR обновление!');
        initCustomScripts();
    });
}

// ============================================
// Полезные утилиты
// ============================================

//Утилита для показа уведомлений
window.showNotification = function(message, type = 'info') {
    const types = {
        success: 'alert-success',
        error: 'alert-danger',
        warning: 'alert-warning',
        info: 'alert-info'
    };

    const icons = {
        success: 'bi-check-circle-fill',
        error: 'bi-exclamation-triangle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill'
    };

    // Удаляем старые уведомления
    document.querySelectorAll('.notification-toast').forEach(el => el.remove());

    const div = document.createElement('div');
    div.className = `notification-toast alert ${types[type] || types.info} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    div.style.cssText = 'z-index:9999;min-width:300px;max-width:500px;box-shadow:0 0.5rem 1rem rgba(0,0,0,0.15);';
    div.setAttribute('role', 'alert');
    div.innerHTML = `
        <div class="d-flex align-items-start">
            <i class="bi ${icons[type] || 'bi-info-circle-fill'} me-2 fs-5"></i>
            <div class="flex-grow-1">${String(message).replace(/\n/g, '<br>')}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;

    document.body.appendChild(div);

    // Авто-закрытие
    const timeout = type === 'error' ? 8000 : 5000;
    setTimeout(function() {
        // Плавное исчезновение
        div.style.transition = 'opacity 0.3s ease';
        div.style.opacity = '0';

        setTimeout(function() {
            if (div.parentNode) {
                div.remove();
            }
        }, 300);
    }, timeout);

    // Закрытие по клику
    const closeBtn = div.querySelector('.btn-close');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            div.style.transition = 'opacity 0.3s ease';
            div.style.opacity = '0';
            setTimeout(function() {
                if (div.parentNode) {
                    div.remove();
                }
            }, 300);
        });
    }
};

// Пример: Утилита для загрузки
window.showLoading = function(selector) {
    $(selector).addClass('loading').prop('disabled', true);
};

window.hideLoading = function(selector) {
    $(selector).removeClass('loading').prop('disabled', false);
};

// Пример: Утилита для работы с формами
window.serializeForm = function(formSelector) {
    const formData = new FormData($(formSelector)[0]);
    const data = {};
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }
    return data;
};

console.log('✅ Все утилиты загружены!');
