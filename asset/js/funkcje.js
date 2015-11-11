// sprawdzanie czy jQuery jest zaladowane
if (typeof jQuery === 'undefined') {
    throw new Error('Prosze najpierw zaladowac jQuery.');
}

(function($){
    $.fn.sewJsDate = function() {
        $(this).datetimepicker({
            // format: 'YYYY-MM-DD',
            format: 'DD.MM.YYYY',
            sideBySide: true,
            useCurrent: false,
            locale: 'pl'
        });
        return;
    };
    
    $.fn.sewJsDateTime = function() {
        $(this).datetimepicker({
            format: 'DD.MM.YYYY HH:mm',
            sideBySide: true,
            useCurrent: false,
            locale: 'pl'
        });
        return;
    };
    
    $.fn.sewSelect = function() {
        $(this).select2();
        return;
    };
})(jQuery);

jQuery(document).ready(function($){
    // DatePicker
    $('.jsdate').sewJsDate();
    
    // DateTimePicker
    $('.jsdatetime').sewJsDateTime();
    
    // Select
    $('.jsselect').sewSelect();
});