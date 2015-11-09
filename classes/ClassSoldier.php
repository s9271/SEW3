<?php
    class ClassSoldier{
        public $errors = array();
        
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
        public $definition = array(
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
        
        public function __construct($id = false){
            if ($id){
                $this->id = (int)$id;
                $this->getSoldier($this->id);
            }
        }
        
        // pobieranie zolnierza
        public function getSoldier($id_soldier){
            if(!$soldier = $this->sqlGetSoldier($id_soldier)){
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
            
            return true;
        }
        
        // zapis
        public function save($auto_date = true){
            return ($this->id && (int)$this->id > 0) ? $this->update() : $this->add($auto_date);
        }
        
        // dodawanie
        public function add($auto_date = true){
            if (isset($this->id)) {
                unset($this->id);
            }
            
            // automatyczne przypisywanie dat
            if ($auto_date && property_exists($this, 'date_add')) {
                $this->date_add = date('Y-m-d H:i:s');
            }
            
            if ($auto_date && property_exists($this, 'date_update')) {
                $this->date_update = date('Y-m-d H:i:s');
            }
            
            $values = getFieldsValidate();
            
            if($this->errors && count($this->errors) > 0){
                return false;
            }
            
            if (!$id = $this->sqlAdd($this->definition['table'], $values)){
                $this->errors[] = "Błąd zapisu do bazy.";
                return false;
            }
            
            $this->id = $id;
            return true;
        }
        
        // pobranie danych
        protected function getFieldsValidate(){
            $values = array();
            
            foreach($this->definition['fields'] as $key => $valid){
                if (!property_exists($this, $key)){
                    $this->errors[] = "</b>{$valid['name']}<b>: Brak wlaściwości w klasie.";
                    continue;
                }
                
                $values[$key] = trim($this->$key);
                
                if((isset($valid['required']) && $valid['required']) && $values[$key] == ''){
                    $this->errors[] = "</b>{$valid['name']}<b>: Proszę uzupełnić pole.";
                    continue;
                }
                
                if(isset($valid['validate']) && is_array($valid['validate']) && count($valid['validate']) > 0){
                    foreach($valid['validate'] as $valid_key){
                        $this->validByMethod($valid_key, $values[$key], $valid['name']);
                    }
                }
            }
            
            return $values;
        }
        
        /* ************* WALIDACJA ************ */
        /* ************************************ */
        
        // walidacja wartosci
        public function validByMethod($method, $value, $name){
            switch($method){
                case 'isName':
                    if(!self::validIsName($value)){
                        $this->errors[] = "<b>{$name}</b>: Pole nie jest tekstem.";
                    }
                break;
                case 'isDate':
                    if(!self::validIsDate($value)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format daty.";
                    }
                break;
                case 'isDateTime':
                    if(!self::validIsDateTime($value)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format daty.";
                    }
                break;
                case 'isInt':
                    if(!self::validIsInt($value)){
                        $this->errors[] = "<b>{$name}</b>: Pole nie jest liczbą.";
                    }
                break;
                case 'isPhone':
                    if(!self::validIsPhone($value)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format telefonu.";
                    }
                break;
                case 'isEmail':
                    if(!self::validIsEmail($value)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format maila.";
                    }
                break;
                case 'isBool':
                    if(!self::validIsBool($value)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format.";
                    }
                break;
            }
            
            return;
        }
        
        // sprawdzanie czy wartosc sklada sie tylko z liter
        public static function validIsName($value){
            // spółka
            if (preg_match('/^[a-zA-ZążśźęćńółĄŻŚŹĘĆŃÓŁ]+$/', $value)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc jest data
        public static function validIsDate($value){
            // 2012-09-12
            if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $value)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc jest datetime
        public static function validIsDateTime($value){
            // 2012-09-12 12:35:45
            // if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})$/', $value)) {
            if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) ([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $value)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc sklada sie tylko z liczb
        public static function validIsInt($value){
            // 23424
            if (preg_match('/^\d+$/', $value)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc sklada sie tylko z liczb
        public static function validIsPhone($value){
            // +000000000
            // 0000000000
            // 000000000
            // 0000000
            // +00 000 00 00
            // (+00) 000 00 00
            // (00) 000 00 00
            // +00-000-00-00
            // 000 000 00 00
            // 000-000-00-00
            // 000 00 00
            // 000-00-00
            // 000 000 000
            // 000-000-000
            if (preg_match('/^(\(([0-9]{3}|[+]?[0-9]{2})\)|[0-9]{3}|[+]?[0-9]{2})?([ -]?)([0-9]{3})([ -]?)([0-9]{2,3})([ -]?)([0-9]{2,3})$/', $value)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc jest mailem
        public static function validIsEmail($value){
            if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc jest true/false
        public static function validIsBool($value){
            if($value === false || $value === true || $value == '1' || $value == '0' || $value == 'true' || $value == 'false'){
                return true;
            }
            
            return false;
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
        
        protected function sqlAdd($table, $data){
            global $DB;
            return $Db->insert($table, $data);
        }
    }
?>