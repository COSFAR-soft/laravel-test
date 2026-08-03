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

// Пример: Утилита для показа уведомлений
window.showNotification = function(message, type = 'info') {
    const types = {
        success: 'alert-success',
        error: 'alert-danger',
        warning: 'alert-warning',
        info: 'alert-info'
    };

    const alert = $(`
        <div class="alert ${types[type] || types.info} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $('.notifications-container').prepend(alert);

    setTimeout(function() {
        alert.alert('close');
    }, 5000);
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
