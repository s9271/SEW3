<?php
    class ClassSoldier{
        public $errors = array();
        
        // id zolnierza
        public $id = false;
        
        // nazwa tabeli
        public $table;
        
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
                'code' =>             array('required' => true, 'validate' => array('isPostCode'), 'name' => 'Kod pocztowy'),
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
            
            if (!$id = $this->sqlAdd($this->definition['table'], $values){
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
                        switch($valid_key){
                            
                        }
                    }
                }
            }
            
            return $values;
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