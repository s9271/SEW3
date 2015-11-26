<?php
    class ClassUser extends ClassModel{
        
        // login
        public $login;
        
        // mail
        public $mail;
        
        // haslo
        public $password;
        
        // id prawa uzytkownika
        public $id_permission;
        
        // id jednostki
        public $id_military;
        
        // aktywny
        public $active;
        
        // imie
        public $name;
        
        // nazwisko
        public $surname;
        
        // avatar
        public $avatar;
        
        // telefon
        public $phone;
        
        // data dodania
        public $date_add;
        
        // uzytkownik dodania
        public $id_user_add;
        
        // data dodania
        public $date_delete;
        
        // uzytkownik usuniecia
        public $id_user_delete;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'users',
            'primary' => 'id_user',
            'fields' => array(
                'name' =>              array('required' => true, 'validate' => array('isName'), 'name' => 'Imię'),
                'surname' =>           array('required' => true, 'validate' => array('isName'), 'name' => 'Nazwisko'),
                'login' =>             array('required' => true, 'name' => 'Login'),
                'mail' =>              array('required' => true, 'validate' => array('isEmail'), 'name' => 'Adres e-mail'),
                'password' =>          array('required' => true, 'validate' => array('isPhone'), 'name' => 'Hasło'),
                'id_permission' =>     array('required' => true, 'validate' => array('isInt'), 'name' => 'Profil uprawnień'),
                'id_military' =>       array('required' => true, 'validate' => array('isInt'), 'name' => 'Jednostka wojskowa'),
                'active' =>            array('required' => true, 'validate' => array('isBool'), 'name' => 'Aktywny'),
                'avatar' =>            array('name' => 'Avatar'),
                'phone' =>             array('required' => true, 'validate' => array('isPhone'), 'name' => 'Telefon'),
                'date_add' =>          array('required' => true, 'validate' => array('isDate'), 'name' => 'Data dodania'),
                'user_add' =>          array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik dodania'),
                'date_delete' =>       array('validate' => array('isDate'), 'name' => 'Data usunięcia'),
                'user_delete' =>       array('validate' => array('isInt'), 'name' => 'Użytkownik usunięcia'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            // $this->getSoldier($this->id);
            return;
        }
        
        // pobieranie zolnierza
        public function getSoldier($id_soldier){
            if(!$soldier = $this->sqlGetSoldier($id_soldier)){
                $this->errors[] = "Brak żołnierza w bazie.";
                return false;
            }
            
            $this->soldierName = $soldier['soldierName'];
            $this->soldierSurname = $soldier['soldierSurname'];
            $this->birthday = $soldier['birthday'];
            $this->sex = $soldier['sex'];
            $this->phone = $soldier['phone'];
            $this->email = $soldier['email'];
            $this->code = $soldier['code'];
            $this->city = $soldier['city'];
            
            $this->load_class = true;
            return true;
        }
        
        // sprawdzanie czy zolnierz istnieje
        public static function isSoldier($id_soldier){
            return self::sqlSoldierExist($id_soldier);
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        protected function sqlGetSoldier($id_soldier){
            global $DB;
            $zapytanie = "SELECT * FROM soldiers WHERE id = {$id_soldier}";
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie zolnierzy
        public static function sqlGetAllSoldiers(){
            global $DB;
            $zapytanie = "SELECT * FROM soldiers";
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // sprawdzanie czy zolnierz istnieje
        public static function sqlSoldierExist($id_soldier){
            global $DB;
            $zapytanie = "SELECT id FROM soldiers WHERE `id` = {$id_soldier}";
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
    }
?>
