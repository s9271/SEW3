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
    
    $.fn.sewJsSelect = function() {
        $(this).select2();
        return;
    };
    
    $.fn.sewJsConfirm = function(){
        $(this).on( "click", function(){
            var show_text = 'Czy na pewno chcesz usunąć rekord?';
            
            if ($(this).attr('data-confirm')) {
                show_text = $(this).data('confirm');
            }
            
            var odpowiedz = confirm(show_text);
            if(odpowiedz){
                return true;
            }else{
                return false;
            }
        });
    };
})(jQuery);

jQuery(document).ready(function($){
    // DatePicker
    if ($('.jsdate').length > 0){
        $('.jsdate').sewJsDate();
    }
    
    // DateTimePicker
    if ($('.jsdatetime').length > 0){
        $('.jsdatetime').sewJsDateTime();
    }
    
    // Select
    if ($('.jsselect').length > 0){
        $('.jsselect').sewJsSelect();
    }
    
    // Potwierdzenie (wyskakujace okienko)
    if ($('.jsconfirm').length > 0){
        $('.jsconfirm').sewJsConfirm();
    }
});