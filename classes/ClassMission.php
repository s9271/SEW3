<?php
    class ClassMission extends ClassModel{
        protected static $use_prefix = true;
        
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
        public static $definition = array(
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
            if(!isset(self::$definition['fields'])){
                return;
            }
            
            foreach(self::$definition['fields'] as $key => $val){
                $this->$key = $values[$key];
            }
            $this->load_class = true;
        }
        
        // pobieranie rodzajow misji
        public static function getTypes($id_current_type = false){
            if(!$groups = self::sqlGetGroups()){
                return false;
            }
            
            $array = array();
            
            foreach($groups as $group){
                $array[$group['id_mission_group']]['name'] = $group['name'];
                $array[$group['id_mission_group']]['childs'] = array();
                
                if($types = self::sqlGetTupesByGroupId($group['id_mission_group'])){
                    foreach($types as $type){
                        $array[$group['id_mission_group']]['childs'][$type['id_mission_type']]['name'] = $type['name'];
                        
                        if($id_current_type && $id_current_type == $type['id_mission_type']){
                            $array[$group['id_mission_group']]['childs'][$type['id_mission_type']]['current'] = true;
                        }
                    }
                }
            }
            
            return $array;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        protected function sqlGetItem($id){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').self::$definition['table'];
            
            $zapytanie = "SELECT * FROM {$table_name} WHERE ".self::$definition['primary']." = {$id}";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems(){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').self::$definition['table'];
            
            $zapytanie = "SELECT * FROM {$table_name}";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich grup
        public static function sqlGetGroups(){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'mission_groups';
            
            $zapytanie = "SELECT * FROM {$table_name}";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich rodzajow
        public static function sqlGetTupes(){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'mission_types';
            
            $zapytanie = "SELECT * FROM {$table_name}";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich rodzajow danej grupy
        public static function sqlGetTupesByGroupId($id_mission_group){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'mission_types';
            
            $zapytanie = "SELECT * FROM {$table_name} WHERE id_mission_group = '{$id_mission_group}'";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
    }
?>