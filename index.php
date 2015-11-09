<?php
    /* wyswietlanie bledow */
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    /* ******************* */
    
    // start sesji
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        if(session_id() == '') {session_start();}
    } else  {
       if (session_status() == PHP_SESSION_NONE) {session_start();}
    }
    
    // automatyczne ladowanie plikow
    function __autoload($class){
        if( file_exists( $_SERVER['DOCUMENT_ROOT']."/classes/{$class}.php" ) ){ // ladowanie klas
            require_once("classes/{$class}.php");
        }elseif( file_exists( $_SERVER['DOCUMENT_ROOT']."/controllers/{$class}.php" ) ){ // ladowanie kontrollerow
            require_once("controllers/{$class}.php");
        }
    }
    
    
    print_r($_GET);

    // if (version_compare(PHP_VERSION, '5.3.7', '<')) {
        // exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
    // } else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
        // require_once("libraries/password_compatibility_library.php");
    // }

    // pobieranie danych do bazy
    require_once("config/db.php");

    // globalna zmienna do operacji na bazie danych
    $DB = new ClassSQL(DB_HOST, DB_USER, DB_PASS, DB_NAME);


$login = new Login();

if ($login->isUserLoggedIn() == true) {

    include("views/logged_in.php");

} else {

    include("views/not_logged_in.php");
}
