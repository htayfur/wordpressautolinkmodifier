jQuery(document).ready(function($) {
    'use strict';

    // Domain listesi için özel işleyici
    const domainTextarea = $('textarea[name="aelm_custom_domains"]');
    
    // Her satırın domain formatını kontrol et
    domainTextarea.on('change', function() {
        const domains = $(this).val().split('\n');
        const validDomains = [];
        const invalidDomains = [];
        
        domains.forEach(domain => {
            domain = domain.trim().toLowerCase();
            if (domain === '') return;

            // Domain formatı kontrolü
            if (/^(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z]{2,}$/.test(domain)) {
                validDomains.push(domain);
            } else {
                invalidDomains.push(domain);
            }
        });

        // Geçersiz domainler varsa uyarı göster
        if (invalidDomains.length > 0) {
            const errorMessage = 'Invalid domain format: ' + invalidDomains.join(', ');
            
            // Mevcut hata mesajlarını temizle
            $('.domain-error').remove();
            
            // Yeni hata mesajı ekle
            $('<div class="notice notice-error domain-error"><p>' + errorMessage + '</p></div>')
                .insertBefore(domainTextarea);
        } else {
            // Hata mesajlarını temizle
            $('.domain-error').remove();
        }

        // Geçerli domainleri textarea'ya geri yaz
        $(this).val(validDomains.join('\n'));
    });

    // REL özelliklerinin seçimini yönet
    const relCheckboxes = $('input[name="aelm_rel_attributes[]"]');
    
    // En az bir REL özelliği seçili olmalı
    relCheckboxes.on('change', function() {
        const checkedCount = relCheckboxes.filter(':checked').length;
        
        if (checkedCount === 0) {
            // Varsayılan olarak noopener ve noreferrer seç
            relCheckboxes.filter('[value="noopener"], [value="noreferrer"]')
                .prop('checked', true);
            
            // Uyarı göster
            alert('At least one REL attribute must be selected. Default attributes have been selected.');
        }
    });
});