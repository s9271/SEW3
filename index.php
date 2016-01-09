<?php
    /* wyswietlanie bledow */
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
    /* ******************* */
    
    // start sesji
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        if(session_id() == '') {session_start();}
    } else {
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
    
    
    // print_r($_GET);

    // if (version_compare(PHP_VERSION, '5.3.7', '<')) {
        // exit("Sorry, Simple PHP Login does not run on a PHP version smaller than 5.3.7 !");
    // } else if (version_compare(PHP_VERSION, '5.5.0', '<')) {
        // require_once("libraries/password_compatibility_library.php");
    // }

    // pobieranie danych do bazy
    require_once("config/db.php");
    
    // pobieranie zarejestrowanych controllerow
    require_once("config/controllers.php");

    // globalna zmienna do operacji na bazie danych
    $DB = new ClassSQL(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    
    // globalna zmienna do logowania
    $login = new ClassLogin();
    
    $current_link = $_SERVER['REQUEST_URI'];
    
    // nazwa kontrolera
    $controller = ClassTools::getValue('controller');
    
    // strony wyswietlane bez logowania
    if(!$login->is_session_auth)
    {
        // sprawdzenie czy jest zdefiniowany controller
        if($controller == 'login' || (!$controller && $current_link == '/'))
        {
            // ladowanie strony z logowaniem
            $loadController = new ControllerLogin();
            print $loadController->getContent();
        }
        elseif($controller != '404')
        {
            // jezeli nie jest zdefiniowany to zaladuje 404
            ClassTools::redirect('404');
        }
        elseif($controller == '404')
        {
            // ladowanie strony z logowaniem
            $loadController = new Controller404();
            print $loadController->getContent(false);
        }
    }
    else
    {
        // sprawdzenie czy w linku jest controller
        if($controller){
            // sprawdzenie czy jest zdefiniowany controller
            if(isset($controllers[$controller])){
                $class_controller = $controllers[$controller];
                
                // sprawdzanie czy kontroller ma dzieci i czy w linku jest podane dziecko
                $child_controller = ClassTools::getValue('child_controller');
                
                if($child_controller){
                    if(isset($class_controller['childrens']) && isset($class_controller['childrens'][$child_controller])){
                        $class_controller = $class_controller['childrens'][$child_controller];
                    }else{
                        ClassTools::redirect('404');
                        exit;
                    }
                }
                
                if(isset($class_controller['permissions']))
                {
                    if (in_array($login->auth_user['id_permission'], $class_controller['permissions']))
                    {
                        $loadController = new $class_controller['controller'];
                        print $loadController->getContent();
                    }else{
                        $loadController = new ControllerModel();
                        print $loadController->getPageNoPermissions();
                    }
                }else{
                    $loadController = new $class_controller['controller'];
                    print $loadController->getContent();
                }
            }else{ // jezeli nie jest zdefiniowany to zaladuje 404
                ClassTools::redirect('404');
                exit;
            }
        }
        elseif(!$controller && $current_link == '/')
        {
            // ladowanie strony start
            $loadController = new ControllerIndex();
            print $loadController->getContent();
        }
        else
        {
            // jezeli nie jest zdefiniowany to zaladuje 404
            ClassTools::redirect('404');
        }
    }
?>
