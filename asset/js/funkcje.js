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
    
    $.fn.sewJsSelectAjax = function() {
        var sew_error = '';
        var data_id_soldier = '';
        
        if (!$(this).attr('data-ajax-class')) {
            sew_error = 'Nie podano w selekcie atrybutu "data-ajax-class".';
            console.error(sew_error);
            // throw new Error('Nie podano w selekcie atrybutu "data-ajax-class"');
            return;
        }
        
        if (!$(this).attr('data-ajax-function')) {
            sew_error = 'Nie podano w selekcie atrybutu "data-ajax-function".';
            console.error(sew_error);
            // throw new Error('Nie podano w selekcie atrybutu "data-ajax-function"');
            return;
        }
        
        if ($(this).attr('data-id-soldier')) {
            data_id_soldier = $(this).data('id-soldier');
        }
        
        $(this).select2({
            ajax: {
                url: '/ajax',
                delay: 250,
                dataType: "json",
                data: function (params) {
                    var query = {
                        search: params.term,
                        ajax_module: $(this).data('ajax-class'),
                        ajax_function: $(this).data('ajax-function'),
                        id_soldier: data_id_soldier,
                    }

                    return query;
                },
                processResults: function (data, params){
                    if(!data){
                        console.error('Błąd pobierania danych.');
                        data = ['items'];
                        data.items = [];
                    }else if(typeof data.error != 'undefined'){
                        console.error(data.error);
                        data.items = [];
                    }

                    // return {
                    // results: data.items,
                    // pagination: {
                    // more: (params.page * 30) < data.total_count
                    // }
                    // };

                    return {
                        results: data.items,
                    };
                },
            },
            placeholder: 'Wybierz',
            minimumInputLength: 3, // minimalna ilosc znakow od ktorych zacznie wyszukiwanie
            language: 'pl', // jezyk
        });
        return;
    };
    
    $.fn.sewJsPasswordAjax = function() {
        $(this).on( "click", function(){
            if (!$(this).attr('data-input')) {
                sew_error = 'Nie podano atrybutu "data-input".';
                console.error(sew_error);
                return;
            }
        
            var this_click = $(this);
            var id_input = $(this).data('input');
            
            $.ajax({
                // method: "POST",
                url: '/ajax',
                delay: 250,
                dataType: "json",
                data: { ajax_module: "user", ajax_function: "generateNewPassword" }
            }).done(function( data_ajax ) {
                if(!data_ajax){
                    console.error('Błąd pobierania danych.');
                }else if(typeof data_ajax.error != 'undefined'){
                    console.error(data_ajax.error);
                }
                
                $('input#' + id_input).val(data_ajax);
                this_click.parent().parent().parent().find('.sew_hint').text('Wygenerowane hasło: ' + data_ajax);
            });
        });
    };
    
    $.fn.sewJsPasswordAjaxWithRepeat = function() {
        $(this).on( "click", function(){
            if (!$(this).attr('data-input-class')) {
                sew_error = 'Nie podano atrybutu "data-input-class".';
                console.error(sew_error);
                return;
            }
            if (!$(this).attr('data-hint-class')) {
                sew_error = 'Nie podano atrybutu "data-hint-class".';
                console.error(sew_error);
                return;
            }
        
            var class_hint = $(this).data('hint-class');
            var class_input = $(this).data('input-class');
            
            $.ajax({
                // method: "POST",
                url: '/ajax',
                delay: 250,
                dataType: "json",
                data: { ajax_module: "user", ajax_function: "generateNewPassword" }
            }).done(function( data_ajax ) {
                if(!data_ajax){
                    console.error('Błąd pobierania danych.');
                }else if(typeof data_ajax.error != 'undefined'){
                    console.error(data_ajax.error);
                }
                
                $('input.' + class_input).val(data_ajax);
                $('.' + class_hint).text('Wygenerowane hasło: ' + data_ajax);
            });
        });
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
    
    $.fn.sewJsTextareaAutoHeight = function(){
        // $(this).height( 0 );
        // $(this).height( this.scrollHeight );
        
        $(this).on('click keyup cut cut change drop keydown', function(e){
            $(this).height( 0 );
            $(this).height( this.scrollHeight );
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
    
    // Select ajax
    if ($('.jsselectajax').length > 0){
        $('.jsselectajax').sewJsSelectAjax();
    }
    
    // Password Ajax (uzytkownicy form)
    if ($('.jspasswordajax').length > 0){
        $('.jspasswordajax').sewJsPasswordAjax();
    }
    
    // Password Ajax (uzytkownik form)
    if ($('.jspasswordajaxwithrepeat').length > 0){
        $('.jspasswordajaxwithrepeat').sewJsPasswordAjaxWithRepeat();
    }
    
    // Potwierdzenie (wyskakujace okienko)
    if ($('.jsconfirm').length > 0){
        $('.jsconfirm').sewJsConfirm();
    }
    
    // Auto-wysokosc textarea
    if ($('textarea.jstextareaautoheight').length > 0){
        $('textarea.jstextareaautoheight').sewJsTextareaAutoHeight();
    }
});