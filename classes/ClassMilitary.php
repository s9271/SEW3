<?php
    class ClassMilitary extends ClassModel{
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // Rodzaj jednostki
        public $id_military_group;
        
        // Numer jednostki
        public $number;
        
        // Nazwa jednostki
        public $name;
        
        // Lokalizacja jednostki
        public $location;
        
        // Użytkownik
        public $id_user;
        
        // Data aktualizacji
        public $date_update;
        
        // Aktywny
        public $active;
        
        // Usunięty
        public $deleted = '0';
        
        // Rodzaj jednostki nazwa
        public $military_group_name;
        
        // Nazwa statusu
        public $active_name;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'militaries',
            'primary' => 'id_military',
            'fields' => array(
                'id_military_group' =>    array('required' => true, 'validate' => array('isInt'), 'name' => 'Rodzaj jednostki'),
                'number' =>               array('required' => true, 'validate' => array('isInt'), 'name' => 'Numer jednostki'),
                'name' =>                 array('required' => true, 'name' => 'Nazwa jednostki'),
                'location' =>             array('required' => true, 'name' => 'Lokalizacja jednostki'),
                'id_user' =>              array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update' =>          array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'active' =>               array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted' =>              array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class)
            {
                // Rodzaj jednostki nazwa
                $this->military_group_name = self::sqlGetGroupNameById($this->id_military_group);
                
                // Nazwa statusu
                $this->active_name = ClassUser::getNameStatus($this->active);
            }
        }
        
        // pobieranie rodzajow jednostki
        public static function getGroups(){
            return self::sqlGetGroups();
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate(){
            if(strlen($this->number) > 4){
                $this->errors = "<b>Numer jednostki</b>: Numer nie może posiadać więcej niż 4 znaki.";
                return false;
            }
            
            if(self::sqlCheckExistsNumber($this->number)){
                $this->errors = "Jednostka o numerze <b>{$this->number}</b> już istnieje.";
                return false;
            }
            
            return true;
        }
        
        // sprawdzanie czy misja istnieje
        public static function isMission($id_mission){
            return self::sqlMissionExist($id_mission);
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie wszystkich grup
        public static function sqlGetGroups(){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'military_groups';
            
            $zapytanie = "SELECT * FROM {$table_name}";
            
            $sql = $DB->pdo_fetch_all_group_column($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie nazwy rodzaju jednostki
        public static function sqlGetGroupNameById($id_military_group){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'military_groups';
            
            $zapytanie = "SELECT `name` FROM {$table_name} WHERE id_military_group = '{$id_military_group}'";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql['name'];
        }
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5'){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page))
            {
                foreach($sql as $key => $val)
                {
                    // Rodzaj jednostki nazwa
                    $sql[$key]['military_group_name'] = self::sqlGetGroupNameById($val['id_military_group']);
                    
                    // Nazwa statusu
                    $sql[$key]['active_name'] = ClassUser::getNameStatus($val['active']);
                }
            }
            
            return $sql;
        }
        
        // sprawdzanie czy numer jednostki istnieje
        public static function sqlCheckExistsNumber($number){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').self::$definition['table'];
            
            $zapytanie = "SELECT `number` FROM `{$table_name}` WHERE `number` = '{$number}' AND `deleted` = '0'";
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
        
        // sprawdzanie czy misja istnieje
        public static function sqlMissionExist($id_mission){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').self::$definition['table'];
            
            $zapytanie = "SELECT ".self::$definition['primary']." FROM `{$table_name}` WHERE `".self::$definition['primary']."` = '{$id_mission}' AND `deleted` = '0'";
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
    }
?>
