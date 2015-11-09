<?php
    class ClassMission extends ClassModel{
        protected $use_prefix = true;
        
        // id
        public $id = false;
        
        // Rodzaj misji
        public $id_mission_type;
        
        // Kryptonim Misji
        public $name;
        
        // Lokalizacja Misji
        public $location;
        
        // Użytkownik
        public $id_user;
        
        // Data aktualizacji
        public $date_update;
        
        // Data rozpoczęcia
        public $date_start;
        
        // Data zakończenia
        public $date_end;
        
        // Aktywny
        public $active;
        
        // walidacja, primary id, tabela i kolumny
        public $definition = array(
            'table' => 'missions',
            'primary' => 'id_mission',
            'fields' => array(
                'id_mission_type' =>    array('required' => true, 'validate' => array('isInt'), 'name' => 'Rodzaj misji'),
                'name' =>               array('required' => true, 'name' => 'Kryptonim Misji'),
                'location' =>           array('required' => true, 'name' => 'Lokalizacja Misji'),
                'id_user' =>            array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update' =>        array('required' => true, 'validate' => array('isDate'), 'name' => 'Data aktualizacji'),
                'date_start' =>         array('required' => true, 'validate' => array('isDate'), 'name' => 'Data rozpoczęcia'),
                'date_end' =>           array('required' => true, 'validate' => array('isDate'), 'name' => 'Data zakończenia'),
                'active' =>             array('required' => true, 'validate' => array('isBool'), 'name' => 'Aktywny'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            if($values = $this->getItem($this->id)){
                $this->setProperties($values);
            }
            return;
        }
        
        // pobieranie misji
        public function getItem($id){
            if(!$values = $this->sqlGetItem($id)){
                $this->errors[] = "Brak misji w bazie.";
                return false;
            }
            return $values;
        }
        
        // przypisanie cech
        protected function setProperties($values){
            if(!isset($definition['fields'])){
                return;
            }
            
            foreach($definition['fields'] as $key => $val){
                $this->$key = $values[$key];
            }
            $this->load_class = true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        protected function sqlGetItem($id){
            global $DB;
            $table_name = ($this->use_prefix : $this->prefix ? '').$definition['table'];
            
            $zapytanie = "SELECT * FROM {$table_name} WHERE {$definition['primary']} = {$id}";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie zolnierzy
        // public static function sqlGetAllSoldiers(){
            // global $DB;
            // $zapytanie = "SELECT * FROM soldiers";
            // $sql = $DB->pdo_fetch_all($zapytanie);
            
            // if(!$sql || !is_array($sql) || count($sql) < 1){
                // return false;
            // }
            
            // return $sql;
        // }
    }
?>