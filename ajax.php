<?php
    
    // start sesji
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        if(session_id() == '') {session_start();}
    } else  {
       if (session_status() == PHP_SESSION_NONE) {session_start();}
    }
    
    // #1 warunek sprawdza czy istnieje sesja uzytkownika
    // if(
        // !$_SESSION ||
        // !isset($_SESSION['user_name']) ||
        // !isset($_SESSION['user_email']) ||
        // !isset($_SESSION['user_login_status']) ||
        // $_SESSION['user_name'] == '' ||
        // $_SESSION['user_email'] == '' ||
        // $_SESSION['user_login_status'] == ''
    // ){
        // $array['error'] = 'Użytkownik nie jest zalogowany.';
        // echo json_encode($array);
        // exit;
    // }
    
    // #2 warunek sprawdza czy wszystkie zmienne w linku sa
    if(
        !$_GET ||
        !isset($_GET['ajax_function']) ||
        !isset($_GET['ajax_module']) ||
        $_GET['ajax_function'] == '' ||
        $_GET['ajax_module'] == ''
    ){
        $array['error'] = 'Błąd polecenia.';
        echo json_encode($array);
        exit;
    }
    
    // automatyczne ladowanie plikow
    function __autoload($class){
        if( file_exists( $_SERVER['DOCUMENT_ROOT']."/classes/{$class}.php" ) ){ // ladowanie klas
            require_once("classes/{$class}.php");
        }elseif( file_exists( $_SERVER['DOCUMENT_ROOT']."/controllers/{$class}.php" ) ){ // ladowanie kontrollerow
            require_once("controllers/{$class}.php");
        }
    }
    
    // #3 warunek sprawdza czy uzytkownik jest poprawny (DO ZROBIENIA!!!!)
    // if(!ClassAuth::isLogged()){
        // $array['error'] = 'Niepoprawny login lub haslo.';
        // echo json_encode($array);
        // exit;
    // }
    
    // #4 warunek sprawdza czy klasa istnieje
    $class_name = 'Class'.ucfirst($_GET['ajax_module']);
    
    if (!class_exists($class_name)){
        $array['error'] = 'Klasa nie istnieje';
        echo json_encode($array);
        exit;
    }
    
    // #5 warunek sprawdza czy funkcja istnieje
    $function_name = $_GET['ajax_function'];
    if (!method_exists($class_name, $_GET['ajax_function'])){
        $array['error'] = 'Funkcja nie istnieje';
        echo json_encode($array);
        exit;
    }

    // pobieranie danych do bazy
    require_once("config/db.php");

    // globalna zmienna do operacji na bazie danych
    $DB = new ClassSQL(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // wykonywanie funkcji i przeslanie jej do ajaxa
    echo json_encode($class_name::$function_name($_GET));
    exit;
    
?>