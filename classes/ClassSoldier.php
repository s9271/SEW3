<?php
    class ClassSoldier extends ClassModel{
        // id zolnierza
        public $id = false;
        
        // imie
        public $soldierName;
        
        // nazwisko
        public $soldierSurname;
        
        // urodziny
        public $birthday;
        
        // plec
        public $sex;
        
        // telefon
        public $phone;
        
        // mail
        public $email;
        
        // kod pocztowy
        public $code;
        
        // miasto
        public $city;
        
        // data dodania
        public $date_add;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldiers',
            'primary' => 'id',
            'fields' => array(
                'soldierName' =>      array('required' => true, 'validate' => array('isName'), 'name' => 'Imię'),
                'soldierSurname' =>   array('required' => true, 'validate' => array('isName'), 'name' => 'Nazwisko'),
                'birthday' =>         array('required' => true, 'validate' => array('isDate'), 'name' => 'Urodziny'),
                'sex' =>              array('required' => true, 'validate' => array('isInt'), 'name' => 'Płeć'),
                'phone' =>            array('required' => true, 'validate' => array('isPhone'), 'name' => 'Telefon'),
                'email' =>            array('required' => true, 'validate' => array('isEmail'), 'name' => 'E-mail'),
                'code' =>             array('required' => true, 'name' => 'Kod pocztowy'),
                'city' =>             array('required' => true, 'validate' => array('isName'), 'name' => 'Miasto'),
                'date_add' =>         array('required' => true, 'validate' => array('isDate'), 'name' => 'Data dodania'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            $this->getSoldier($this->id);
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
