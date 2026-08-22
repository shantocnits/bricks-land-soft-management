// Flatpickr Bengali Locale
window.fpLocale = {
    firstDayOfWeek: 6,
    weekdays: {
        shorthand: ['রবি','সোম','মঙ্গল','বুধ','বৃহ','শুক্র','শনি'],
        longhand: ['রবিবার','সোমবার','মঙ্গলবার','বুধবার','বৃহস্পতিবার','শুক্রবার','শনিবার']
    },
    months: {
        shorthand: ['জান','ফেব','মার্চ','এপ্রি','মে','জুন','জুলাই','আগ','সেপ','অক্টো','নভে','ডিসে'],
        longhand: ['জানুয়ারি','ফেব্রুয়ারি','মার্চ','এপ্রিল','মে','জুন','জুলাই','আগস্ট','সেপ্টেম্বর','অক্টোবর','নভেম্বর','ডিসেম্বর']
    }
};

window.initFlatpickrs = function() {
    if (typeof flatpickr === 'undefined') return;

    document.querySelectorAll('[data-flatpickr]').forEach(function(el) {
        if (el._flatpickr) {
            var expectedVal = el.hasAttribute('data-default') ? el.getAttribute('data-default') : el.value;
            if (!expectedVal) {
                if (el._flatpickr.selectedDates.length > 0 || (el._flatpickr.altInput && el._flatpickr.altInput.value !== '')) {
                    el._flatpickr.clear();
                }
            } else {
                var currentFormatted = el._flatpickr.input ? el._flatpickr.input.value : '';
                if (expectedVal !== currentFormatted || el._flatpickr.selectedDates.length === 0) {
                    el._flatpickr.setDate(expectedVal, false);
                }
            }
            return;
        }

        // Clean up stale flatpickr-input sibling if exists
        var next = el.nextElementSibling;
        if (next && next.classList.contains('flatpickr-input') && !next.hasAttribute('data-flatpickr')) {
            next.remove();
        }

        var wireProp = el.getAttribute('data-wire-prop');
        var options = {
            locale: window.fpLocale,
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd-m-Y',
            altInputClass: el.className,
            allowInput: false,
            disableMobile: true,
            position: 'auto',
            onOpen: function(selectedDates, dateStr, instance) {
                if (instance && typeof instance._positionCalendar === 'function') {
                    setTimeout(function() { instance._positionCalendar(); }, 10);
                }
            },
            onChange: function(selectedDates, dateStr, instance) {
                if (!wireProp) return;
                try {
                    var closestWire = el.closest('[wire\\:id]');
                    if (closestWire && window.Livewire) {
                        var wireId = closestWire.getAttribute('wire:id');
                        var comp = window.Livewire.find(wireId);
                        if (comp) { comp.set(wireProp, dateStr); return; }
                    }
                } catch(e) { console.warn('Flatpickr Livewire set error:', e); }
            }
        };
        var defaultDate = el.hasAttribute('data-default') ? el.getAttribute('data-default') : el.value;
        if (defaultDate) options.defaultDate = defaultDate;
        flatpickr(el, options);
    });

    document.querySelectorAll('[data-flatpickr-month]').forEach(function(el) {
        if (el._flatpickr) {
            var expectedVal = el.hasAttribute('data-default') ? el.getAttribute('data-default') : el.value;
            if (!expectedVal) {
                if (el._flatpickr.selectedDates.length > 0 || (el._flatpickr.altInput && el._flatpickr.altInput.value !== '')) {
                    el._flatpickr.clear();
                }
            } else {
                if (el._flatpickr.selectedDates.length === 0) {
                    el._flatpickr.setDate(expectedVal, false);
                }
            }
            return;
        }

        var wireProp = el.getAttribute('data-wire-prop') || 'filterMonth';
        var pluginsList = [];
        if (typeof monthSelectPlugin !== 'undefined') {
            pluginsList.push(new monthSelectPlugin({
                shorthand: true,
                dateFormat: 'Y-m',
                altFormat: 'F Y'
            }));
        }

        var options = {
            locale: window.fpLocale,
            plugins: pluginsList,
            allowInput: false,
            disableMobile: true,
            onChange: function(selectedDates, dateStr, instance) {
                if (!wireProp) return;
                try {
                    var closestWire = el.closest('[wire\\:id]');
                    if (closestWire && window.Livewire) {
                        var wireId = closestWire.getAttribute('wire:id');
                        var comp = window.Livewire.find(wireId);
                        if (comp) { comp.set(wireProp, dateStr); return; }
                    }
                } catch(e) { console.warn('Flatpickr Livewire set error:', e); }
            }
        };
        var defaultDate = el.hasAttribute('data-default') ? el.getAttribute('data-default') : el.value;
        if (defaultDate) options.defaultDate = defaultDate;
        flatpickr(el, options);
    });
};

// 🖨️ Universal Print System Engine
window.printChallanArea = function(printAreaId) {
    var el = document.getElementById(printAreaId);
    if (!el) { window.print(); return; }
    
    var clone = el.cloneNode(true);
    clone.id = '__print_clone__';
    clone.style.cssText = '';
    clone.removeAttribute('class');
    
    var pageSize = 'A4 portrait';
    var marginSize = '5mm';
    var cloneHeight = 'auto';
    if (printAreaId === 'print-a4-dual' || (printAreaId && printAreaId.indexOf('unload') !== -1)) {
        pageSize = 'A4 landscape';
        marginSize = '5mm';
    } else if (printAreaId === 'print-pos-customer') {
        pageSize = '80mm auto';
        marginSize = '2mm';
        cloneHeight = '100vh';
    } else if (printAreaId === 'print-pos-dual') {
        pageSize = 'A4 portrait';
        marginSize = '5mm';
    }

    var style = document.createElement('style');
    style.textContent = [
        '@media print {',
        '  body * { visibility: hidden !important; }',
        '  #__print_clone__, #__print_clone__ * { visibility: visible !important; }',
        '  #__print_clone__ { position:fixed!important;left:0!important;top:0!important;width:100%!important;height:' + cloneHeight + '!important;min-height:' + cloneHeight + '!important;background:#ffffff!important;padding:2mm!important;margin:0!important;z-index:999999!important; }',
        '  @page { size: ' + pageSize + ' !important; margin: ' + marginSize + ' !important; }',
        '}'
    ].join('\n');
    
    document.head.appendChild(style);
    document.body.appendChild(clone);
    
    setTimeout(function() {
        window.print();
        setTimeout(function() {
            if (document.body.contains(clone)) document.body.removeChild(clone);
            if (document.head.contains(style)) document.head.removeChild(style);
        }, 500);
    }, 100);
};

// Safe Navigation Event Listeners
document.addEventListener('DOMContentLoaded', function() {
    window.initFlatpickrs();
});
document.addEventListener('livewire:navigated', function() {
    window.initFlatpickrs();
});
document.addEventListener('livewire:update', function() {
    window.initFlatpickrs();
});
