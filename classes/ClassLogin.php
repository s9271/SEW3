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
                $this->errors[] = "<b>Login</b>: Niepoprawny format.";
                return false;
            }
            
            // sprawdzanie czy uzytkownik istnieje
            // if(Auth::checkUserExists($this->login)){
                // $this->errors[] = "Użytkownik nie istnieje.";
                // return false;
            // }
            return true;
        }
    }
?>
