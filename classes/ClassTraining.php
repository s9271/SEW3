<?php
    class ClassTraining extends ClassModel{
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // osrodek szkolenia
        public $id_traning_centre;
        
        // Nazwa szkolenia
        public $name;
        
        // Opis
        public $description;
        
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
        
        // Usunięty
        public $deleted = '0';
        
        // osrodek szkolenia nazwa
        public $traning_centre_name;
        
        // Data zakończenia nazwa
        public $date_end_name;
        
        // Status
        public $status;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'trainings',
            'primary' => 'id_training',
            'fields' => array(
                'id_traning_centre' =>    array('required' => true, 'validate' => array('isInt'), 'name' => 'Ośrodek szkolenia'),
                'name' =>               array('required' => true, 'name' => 'Nazwa szkolenia'),
                'description' =>        array('name' => 'Opis'),
                'id_user' =>            array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update' =>        array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'date_start' =>         array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data rozpoczęcia'),
                'date_end' =>           array('validate' => array('isDateTime'), 'name' => 'Data zakończenia'),
                'active' =>             array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted' =>             array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class){
                // $this->traning_centre_name = self::sqlGetTypeNameId($this->id_traning_centre);
                $this->date_end_name = self::getDateEndNameByDateEnd($this->date_end);
                $this->status = self::getStatusName($this->date_end, $this->active);
            }
        }
        
        // pobieranie rodzajow misji
        /* public static function getTypes($id_current_type = false){
            if(!$groups = self::sqlGetGroups()){
                return false;
            }
            
            $array = array();
            
            foreach($groups as $group){
                $array[$group['id_mission_group']]['name'] = $group['name'];
                $array[$group['id_mission_group']]['childs'] = array();
                
                if($types = self::sqlGetTypesByGroupId($group['id_mission_group'])){
                    foreach($types as $type){
                        $array[$group['id_mission_group']]['childs'][$type['id_mission_type']]['name'] = $type['name'];
                        
                        if($id_current_type && $id_current_type == $type['id_mission_type']){
                            $array[$group['id_mission_group']]['childs'][$type['id_mission_type']]['current'] = true;
                        }
                    }
                }
            }
            
            return $array;
        } */
        
        /* **************** SQL *************** */
        /* ************************************ */
        /* 
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
        public static function sqlGetTypesByGroupId($id_mission_group){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'mission_types';
            
            $zapytanie = "SELECT * FROM {$table_name} WHERE id_mission_group = '{$id_mission_group}'";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich rodzajow danej grupy
        public static function sqlGetTypeNameId($id_mission_type){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'mission_types';
            
            $zapytanie = "SELECT `name` FROM {$table_name} WHERE id_mission_type = '{$id_mission_type}'";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql['name'];
        } */
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5'){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page)){
                foreach($sql as $key => $val){
                    // $sql[$key]['mission_type_name'] = self::sqlGetTypeNameId($val['id_mission_type']);
                    $sql[$key]['date_end_name'] = self::getDateEndNameByDateEnd($val['date_end']);
                    $sql[$key]['status'] = self::getStatusName($val['date_end'], $val['active']);
                }
            }
            
            return $sql;
        }
    }
?>