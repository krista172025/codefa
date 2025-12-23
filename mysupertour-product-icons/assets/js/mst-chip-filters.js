/**
 * MySuperTour Chip Filters
 * Author: Telegram @l1ghtsun
 * Author URI: https://t.me/l1ghtsun
 */

(function() {
    'use strict';

    function initChipFilters() {
        var chips = document.querySelectorAll('.mst-chip');
        
        chips.forEach(function(chip) {
            chip.addEventListener('click', function(e) {
                e.preventDefault();
                
                var checkbox = this.querySelector('input[type="checkbox"]');
                if (checkbox) {
                    checkbox.checked = !checkbox.checked;
                    if (checkbox.checked) {
                        this.classList.add('active');
                    } else {
                        this.classList.remove('active');
                    }
                }
            });
        });

        // Кнопка применить
        var applyBtn = document.querySelector('.mst-btn-apply');
        if (applyBtn) {
            applyBtn.addEventListener('click', function() {
                var form = this.closest('form');
                if (form) form.submit();
            });
        }

        // Кнопка сброса
        var resetBtn = document.querySelector('.mst-btn-reset');
        if (resetBtn) {
            resetBtn.addEventListener('click', function() {
                chips.forEach(function(chip) {
                    var checkbox = chip.querySelector('input[type="checkbox"]');
                    if (checkbox) {
                        checkbox.checked = false;
                        chip.classList.remove('active');
                    }
                });
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initChipFilters);
    } else {
        initChipFilters();
    }
})();