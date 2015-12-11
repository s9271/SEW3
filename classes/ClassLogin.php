ClassLogin
<?php
    class ClassLogin{
        public $login;
        public $password;
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        // dodawanie
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
            
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie usera
        protected function sqlGetUserAndPassword($login){
            global $DB;
            
            $zapytanie = "SELECT `id_user`, `login`, `password`, `active`, `guard`
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
    }
?>
