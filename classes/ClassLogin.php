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
            
            if($this->is_session_auth){
                $this->getUserByAuthKey(false, true);
            
                $sew_action = ClassTools::getValue('sew_action');
                
                if($sew_action && $sew_action == 'logout'){
                    $this->logout();
                }
            }
        }
        
        /* ************** REDIRECT ************* */
        /* ************************************* */
        
        // kierowanie na strone logowania
        protected function goToLoginPage(){
            if($this->is_session_auth){
                $this->is_session_auth = false;
                $this->auth_user = array();
                unset($_SESSION['user']['auth_key']);
                unset($_SESSION['user']);
            }
            // ClassTools::redirect('login');
            ClassTools::redirect('');
            exit;
        }
        
        // kierowanie na strone glowna
        protected function goToHomePage(){
            ClassTools::redirect('');
            exit;
        }
        
        // kierowanie na strone guarda
        protected function goToGuardPage(){
            ClassTools::redirect('guard');
            exit;
        }
        
        /* *************** OGOLNE ************** */
        /* ************************************* */
        
        // wylogowanie ze sprawdzeniem czy zalogowany
        public function prepareLogout(){
            $this->getUserByAuthKey();
            $this->logout();
        }
        
        // pobieranie info o zalogowanym uzytkowniku
        public function getUserByAuthKey($go_home = false, $go_guard = false){
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
            
            $page_action = ClassTools::getValue('page_action');
            
            // sprawdzanie czy ip jest zweryfikowany
            if(($go_home || $page_action == 'guard') && $user['verified'] == '1'){
                $this->goToHomePage();
                return;
            }
            
            // sprawdzanie czy ip jest zweryfikowany
            if($go_guard && $user['verified'] == '0' && $page_action != 'guard'){
                $this->goToGuardPage();
                return;
            }
            
            // sprawdzanie czy jest sie na stronie dla logowanych
            if($page_action == 'login' || $page_action == 'haslo'){
                $this->goToHomePage();
                return;
            }
            
            // przy kazdym wejsciu na strone aktualizuje date ostatniwgo wejscia uzytkonika
            $this->sqlUpdateLoginDateRefresh();
            
            return;
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        // wylogowanie
        public function logout(){
            $this->sqlLogout();
            $this->goToLoginPage();
        }
        
        // wylogowanie po id usera
        public function logoutById($id_user){
            $this->sqlLogoutById($id_user);
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
            
            // generowanie nowego klucza guard
            // $new_key = ClassTools::generateRandomPasswd(6, array('1', '2', '3'));
            $new_key = ClassAuth::generateRandomGuardKey();
            $this->sqlGuardResend($new_key);
            
            return $new_key;
        }
        
        // generowanie linka do zmiany hasla
        public function sendNewPasswordLink(){
            $empty = false;
            $this->login = trim($this->login);
            
            // sprawdzanie czy login jest pusty
            if($this->login == '' || empty($this->login)){
                $this->errors[] = "<b>Użytkownik</b>: Pole jest puste.";
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
            
            // sprawdzanie kiedy ostatnio zostal wyslany link do zmiany hasla
            if($last_send = $this->sqlGetWhenPasswordSend($user['id_user'])){
                $date_password_send = new DateTime($last_send['date_send']);
                $date_now = new DateTime("now");
                $date_now->sub(new DateInterval('PT'.ClassAuth::$password_mail_time.'M'));
                
                if($date_password_send > $date_now){
                    $this->errors = "Nowe hasło można wysyłać raz na ".ClassAuth::$password_mail_time." minut.";
                    return false;
                }
            }
            
            // generowanie klucza linka
            // $password_key = ClassTools::generateRandomPasswd(60, array('1', '2', '3'));
            $password_key = ClassAuth::generateRandomPasswordLinkKey();
            
            if($last_send){
                $this->sqlUpdateNewPasswordRequest($password_key, $last_send['id_user_new_password']);
            }else{
                $this->sqlAddNewPasswordRequest($password_key, $user['id_user']);
            }
            
            $this->auth_user = $user;
            return $password_key;
        }
        
        // generowanie nowego hasla
        public function sendNewPassword($password_key){
            
            // sprawdzanie kiedy ostatnio zostal wyslany link do zmiany hasla
            if(!$last_send = $this->sqlGetWhenPasswordSendByPasswordKey($password_key)){
                $this->errors = "Niepoprawny link.";
                return false;
            }
            
            $date_password_send = new DateTime($last_send['date_send']);
            $date_now = new DateTime("now");
            $date_now->sub(new DateInterval('PT'.ClassAuth::$password_link_time.'H'));
            // print_r($date_now);
            if($date_password_send < $date_now){
                $this->errors = "Link jest nieaktywny.";
                return false;
            }
            
            // generowanie nowego hasla
            $new_password = ClassTools::generateRandomPasswd();
            
            if(!$user = new ClassUser($last_send['id_user'])){
                $this->errors = $user->errors;
                return false;
            }
            
            if(!$user->sqlUpdatePassword($new_password, $last_send['id_user_new_password'])){
                $this->errors = $user->errors;
                return false;
            }
            
            $this->auth_user = $user;
            return $new_password;
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
        
        // pobieranie daty ostatniego wyslania maila z linkiem
        protected function sqlGetWhenPasswordSend($id_user){
            global $DB;
            
            $zapytanie = "SELECT `id_user_new_password`, `date_send`
                FROM `sew_user_new_password`
                WHERE `id_user` = '{$id_user}'
                    AND `generated` = '0'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie daty ostatniego wyslania maila z linkiem
        protected function sqlGetWhenPasswordSendByPasswordKey($password_key){
            global $DB;
            
            $zapytanie = "SELECT `id_user_new_password`, `date_send`, `id_user`
                FROM `sew_user_new_password`
                WHERE `password_key` = '".ClassTools::pSQL($password_key)."'
                    AND `generated` = '0'
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
        
        // wylogowanie usera
        protected function sqlLogoutById($id_user){
            global $DB;
            
            $date_now = new DateTime("now");
            $date_now->sub(new DateInterval('PT'.ClassAuth::$session_time));
            
            $where = "`id_user` = '{$id_user}' AND `date_refresh` > '{$date_now->format('Y-m-d H:i:s')}' AND `is_logged` = '1'";
            $DB->update('sew_user_login', array('is_logged' => '0'), $where, false);
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
        
        // generowanie nowego linka do nowego hasla
        protected function sqlAddNewPasswordRequest($password_key, $id_user){
            global $DB;
            
            $data = array(
                'id_user' => $id_user,
                'password_key' => $password_key,
                'date_send' => date('Y-m-d H:i:s')
            );
            
            $DB->insert('sew_user_new_password', $data);
            return;
        }
        
        // generowanie nowego linka do nowego hasla
        protected function sqlUpdateNewPasswordRequest($password_key, $id_user_new_password){
            global $DB;
            
            $where = "`id_user_new_password` = '{$id_user_new_password}'";
            $DB->update('sew_user_new_password', array('password_key' => $password_key, 'date_send' => date('Y-m-d H:i:s')), $where);
            return;
        }
        
        // przy kazdym wejsciu na strone aktualizuje date ostatniwgo wejscia uzytkonika
        protected function sqlUpdateLoginDateRefresh(){
            global $DB;
            
            $where = "`id_users_login` = '{$this->auth_user['id_users_login']}'";
            $DB->update('sew_user_login', array('date_refresh' => date('Y-m-d H:i:s')), $where);
            return;
        }
    }
?>
