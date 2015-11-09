<?php
    class ClassModel{
        public $errors = array();
        
        // id
        public $id = false;
        
        // pokazuje, czy dany id zostal poprawnie zaladowany
        public $load_class = false;
        
        // walidacja, primary id, tabela i kolumny
        public $definition = array();
        
        public function __construct($id = false){
            if ($id){
                $this->id = (int)$id;
                $this->load();
            }
        }
        
        // pobieranie danych gdy jest podane id
        public function load(){
            return;
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
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        // zapis
        public function save($auto_date = true){
            return ($this->id && (int)$this->id > 0) ? $this->update($auto_date) : $this->add($auto_date);
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
        
        // aktualizacja
        public function update($auto_date = true){
            if(!isset($this->id)){
                $this->errors[] = "Brak podanego id.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_update')) {
                $this->date_update = date('Y-m-d H:i:s');
            }
            
            $values = getFieldsValidate();
            
            if($this->errors && count($this->errors) > 0){
                return false;
            }
            
            if ($this->sqlUpdate($this->definition['table'], $values, $this->definition['primary'].' = '.$this->id)){
                $this->errors[] = "Błąd aktualizacji rekordu w bazie.";
                return false;
            }
            
            return true;
        }
        
        // aktualizacja
        public function delete(){
            if(!isset($this->id)){
                $this->errors[] = "Brak podanego id.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_update')) {
                $this->date_update = date('Y-m-d H:i:s');
            }
            
            if ($this->sqlDelete($this->definition['table'], $this->definition['primary'].' = '.$this->id)){
                $this->errors[] = "Błąd usuwania rekordu z bazy.";
                return false;
            }
            
            unset($this->id);
            if($this->load_class){
                $this->load_class = false;
            }
            return true;
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
        
        protected function sqlAdd($table, $data){
            global $DB;
            return $Db->insert($table, $data);
        }
        
        protected function sqlUpdate($table, $data, $where){
            global $DB;
            return $Db->update($table, $data, $where);
        }
        
    }
?>