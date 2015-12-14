<?php
    class ClassLogin{
        // bedzie przechowywac dane na temat usera z sesji
        public $auth_user = array();
        
        // zmienna bedzie trzymac czy jest sesja
        public $is_session_auth = false;
        
        public $login;
        public $password;
        
        public function __construct(){
            // sprawdzanie czy istnieje sesja uzytkownika
            $this->is_session_auth = (isset($_SESSION['user']) && isset($_SESSION['user']['auth_key'])) ? $_SESSION['user']['auth_key'] : false;
        }
        
        /* ************** REDIRECT ************* */
        /* ************************************* */
        
        // kierowanie na strone logowania
        protected function goToLoginPage(){
            if($this->is_session_auth){
                $this->is_session_auth = false;
                $this->auth_user = array();
                unset($_SESSION['user']['auth_key']);
            }
            ClassTools::redirect('login');
            exit;
        }
        
        // kierowanie na strone glowna
        protected function goToHomePage(){
            ClassTools::redirect('');
            exit;
        }
        
        /* *************** OGOLNE ************** */
        /* ************************************* */
        
        // pobieranie info o zalogowanym uzytkowniku
        public function getUserByAuthKey($go_home = false){
            // sprawdzanie czy istnieje wgl sesja
            if(!$this->is_session_auth){
                $this->goToLoginPage();
                return;
            }
            
            // pobieranie uzytkownika
            if(!$user = ClassUser::getUserByAuthKey($this->is_session_auth)){
                $this->goToLoginPage();
                return;
            }
            
            // sprawdzanie czy user loguje sie z poprawnego ip
            if($user['guard_ip'] != $_SERVER['REMOTE_ADDR']){
                $this->goToLoginPage();
                return;
            }
            
            // sprawdzanie czy user jest zalogowany
            if($user['is_logged'] != '1'){
                $this->goToLoginPage();
                return;
            }
            
            $this->auth_user = $user;
            
            // sprawdzanie czy czas nieaktywnosci juz minal
            $date_refresh = new DateTime($user['date_refresh']);
            $date_now = new DateTime("now");
            $date_now->sub(new DateInterval('PT'.ClassAuth::$session_time));
            
            if($date_refresh < $date_now){
                $this->logout();
                return;
            }
            
            // sprawdzanie czy ip jest zweryfikowany
            if($go_home && $user['verified'] == '1'){
                $this->goToHomePage();
                return;
            }
            
            return;
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        // wylogowanie
        public function logout(){
            $this->sqlLogout();
            $this->goToLoginPage();
        }
        
        // logowanie
        public function login(){
            // sprawdzanie czy uzytkownik juz nie jest zalogowany
            // if(Auth::isUserLogged()){
                // $this->errors[] = "Jesteś już zalogowany.";
                // return false;
            // }
            
            $empty = false;
            $this->login = trim($this->login);
            $this->password = trim($this->password);
            
            // sprawdzanie czy login jest pusty
            if($this->login == '' || empty($this->login)){
                $this->errors[] = "<b>Login</b>: Pole jest puste.";
                $empty = true;
            }
            
            // sprawdzanie czy haslo jest puste
            if($this->password == '' || empty($this->password)){
                $this->errors[] = "<b>Hasło</b>: Pole jest puste.";
                $empty = true;
            }
            
            if($empty){
                return false;
            }
            
            // sprawdzanie czy login sie waliduje
            if(!ClassModel::validIsNormalChars($this->login)){
                $this->errors = "<b>Login</b>: Niepoprawny format.";
                return false;
            }
            
            // sprawdzanie czy uzytkownik istnieje
            if(!$user = $this->sqlGetUserAndPassword($this->login)){
                $this->errors = "Użytkownik nie istnieje.";
                return false;
            }
            
            // sprawdzanie czy uzytkownik jest aktywny
            if($user['active'] != '1'){
                $this->errors = "Użytkownik nie jest aktywny.";
                return false;
            }
            
            // sprawdzanie czy haslo sie zgadza
            if(!ClassAuth::checkPassword($this->password, $user['password'])){
                $this->errors = "Niepoprawne hasło.";
                return false;
            }
            
            return $user;
        }
        
        // sprawdzanie klucza
        public function insertGuard($key){
            $key = trim($key);
            
            // sprawdzanie czy klucz jest pusty
            if($key == '' || empty($key)){
                $this->errors = "<b>Klucz</b>: Pole jest puste.";
                return false;
            }
            
            if($key != $this->auth_user['guard_key']){
                $this->errors = "Klucz jest niepoprawny.";
                return false;
            }
            
            $this->sqlIpVerified();
            $this->goToHomePage();
            
            return true;
        }
        
        // generowanie nowego klucza
        public function guardResend(){
            // sprawdzanie kiedy ostatnio zostal wyslany klucz
            $date_guard_send = new DateTime($this->auth_user['date_guard_send']);
            $date_now = new DateTime("now");
            $date_now->sub(new DateInterval('PT'.ClassAuth::$guard_mail_time.'M'));
            
            if($date_guard_send > $date_now){
                $this->errors = "Nowy klucz można wysyłać raz na ".ClassAuth::$guard_mail_time." minut.";
                return false;
            }
            
            $new_key = ClassTools::generateRandomPasswd(6, array('1', '2', '3'));
            $this->sqlGuardResend($new_key);
            
            return $new_key;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie usera
        protected function sqlGetUserAndPassword($login){
            global $DB;
            
            $zapytanie = "SELECT `id_user`, `login`, `mail`, `password`, `active`, `guard`
                FROM `sew_users`
                WHERE `login` = '{$login}'
                    AND `deleted` = '0'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // wylogowanie usera
        protected function sqlLogout(){
            global $DB;
            $where = "`id_users_login` = '{$this->auth_user['id_users_login']}'";
            $DB->update('sew_user_login', array('is_logged' => '0'), $where);
            return;
        }
        
        // zmiana ip na zweryfikowany
        protected function sqlIpVerified(){
            global $DB;
            $where = "`id_user_guard` = '{$this->auth_user['id_user_guard']}'";
            $DB->update('sew_user_guard', array('verified' => '1'), $where);
            return;
        }
        
        // generowanie nowego klucza
        protected function sqlGuardResend($new_key){
            global $DB;
            
            $where = "`id_user_guard` = '{$this->auth_user['id_user_guard']}'";
            $DB->update('sew_user_guard', array('guard_key' => $new_key, 'date_guard_send' => date('Y-m-d H:i:s')), $where);
            return;
        }
    }
?>
