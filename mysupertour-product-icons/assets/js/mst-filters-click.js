/**
 * MySuperTour Filters - Click Handler
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 * ФИКС: 100% РАБОЧИЕ КЛИКИ НА ФИЛЬТРЫ!
 */

(function() {
    'use strict';
    
    function initMSTFilters() {
        console.log('🎯 MST Filters: инициализация кликов');
        
        // === ЧИПЫ ФОРМАТА И ТРАНСПОРТА ===
        const chips = document.querySelectorAll('.mst-chip-inline');
        console.log('Найдено чипов:', chips.length);
        
        chips.forEach(function(chip, index) {
            // Убираем все старые обработчики
            const newChip = chip.cloneNode(true);
            chip.parentNode.replaceChild(newChip, chip);
            
            // Добавляем новый обработчик
            newChip.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                
                const checkbox = this.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    
                    if (checkbox.checked) {
                        this.classList.add('active');
                        console.log('✅ Чип активирован:', checkbox.value);
                    } else {
                        this.classList.remove('active');
                        console.log('✅ Чип деактивирован:', checkbox.value);
                    }
                }
                
                return false;
            }, true);
            
            console.log('Обработчик добавлен для чипа:', index);
        });

        // === DROPDOWN (ПАРАМЕТРЫ) ===
        const dropdownToggle = document.querySelector('.mst-dropdown-toggle');
        const dropdownMenu = document.querySelector('.mst-dropdown-menu');
        
        if (dropdownToggle && dropdownMenu) {
            console.log('Dropdown найден');
            
            // Убираем старые обработчики
            const newToggle = dropdownToggle.cloneNode(true);
            dropdownToggle.parentNode.replaceChild(newToggle, dropdownToggle);
            
            newToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                this.classList.toggle('active');
                dropdownMenu.classList.toggle('show');
                console.log('✅ Dropdown переключен');
                
                return false;
            }, true);
            
            // Опции dropdown
            const dropdownOptions = document.querySelectorAll('.mst-dropdown-option');
            dropdownOptions.forEach(function(option, index) {
                const newOption = option.cloneNode(true);
                option.parentNode.replaceChild(newOption, option);
                
                newOption.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    const checkbox = this.querySelector('input[type="checkbox"]');
                    if (checkbox) {
                        checkbox.checked = !checkbox.checked;
                        
                        if (checkbox.checked) {
                            this.classList.add('active');
                            console.log('✅ Опция выбрана:', checkbox.value);
                        } else {
                            this.classList.remove('active');
                            console.log('✅ Опция снята:', checkbox.value);
                        }
                    }
                    
                    return false;
                }, true);
            });
            
            // Закрытие при клике вне
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.mst-dropdown-wrapper')) {
                    newToggle.classList.remove('active');
                    dropdownMenu.classList.remove('show');
                }
            });
        }

        // === КНОПКА СБРОСА ===
        const resetBtn = document.querySelector('.mst-btn-clear');
        if (resetBtn) {
            const newResetBtn = resetBtn.cloneNode(true);
            resetBtn.parentNode.replaceChild(newResetBtn, resetBtn);
            
            newResetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('🔄 Сброс всех фильтров');
                
                // Сбрасываем чипы
                document.querySelectorAll('.mst-chip-inline').forEach(function(chip) {
                    const checkbox = chip.querySelector('input[type="checkbox"]');
                    if (checkbox) {
                        checkbox.checked = false;
                        chip.classList.remove('active');
                    }
                });
                
                // Сбрасываем dropdown
                document.querySelectorAll('.mst-dropdown-option').forEach(function(option) {
                    const checkbox = option.querySelector('input[type="checkbox"]');
                    if (checkbox) {
                        checkbox.checked = false;
                        option.classList.remove('active');
                    }
                });
                
                return false;
            }, true);
        }
        
        console.log('✅ Все фильтры инициализированы успешно!');
    }
    
    // Запуск
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMSTFilters);
    } else {
        initMSTFilters();
    }
})();