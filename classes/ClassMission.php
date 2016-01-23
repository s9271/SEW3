<?php
    class ClassMission extends ClassModel{
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        public static $is_search = true;
        
        // id
        public $id = false;
        
        // Rodzaj misji
        public $id_mission_type;
        
        // Kryptonim Misji
        public $name;
        
        // Lokalizacja Misji
        public $location;
        
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
        
        // Rodzaj misji nazwa
        public $mission_type_name;
        
        // Data zakończenia nazwa
        public $date_end_name;
        
        // Data zakończenia nazwa
        public $date_start_pl;
        
        // Data zakończenia nazwa
        public $date_end_name_pl;
        
        // nazwa aktywacji
        public $active_name;
        
        // Status
        public $status;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'missions',
            'primary' => 'id_mission',
            'fields' => array(
                'id_mission_type'   => array('required' => true, 'validate' => array('isInt'), 'name' => 'Rodzaj misji'),
                'name'              => array('required' => true, 'name' => 'Kryptonim Misji'),
                'location'          => array('required' => true, 'name' => 'Lokalizacja Misji'),
                'description'       => array('name' => 'Opis'),
                'id_user'           => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'       => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'date_start'        => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data rozpoczęcia'),
                'date_end'          => array('validate' => array('isDateTime'), 'name' => 'Data zakończenia'),
                'active'            => array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted'           => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class){
                // $this->mission_type_name = self::sqlGetTypeNameId($this->id_mission_type);
                $this->mission_type_name = ClassMissionType::sqlGetItemNameByIdParent($this->id_mission_type);
                
                $this->date_end_name = self::getDateEndNameByDateEnd($this->date_end);
                // $this->status = self::getStatusName($this->date_end, $this->active);
                $this->status = self::getStatusMission($this->date_start, $this->date_end);
                
                $this->date_end_name_pl = self::getDateEndNameByDateEnd($this->date_end, true);
                $this->date_start_pl = date('d.m.Y H:i', strtotime($this->date_start));
                
                $this->active_name = ClassUser::getNameStatus($this->active);
            }
        }
        
        // pobieranie rodzajow misji
        // public static function getTypes(){
            // if(!$groups = self::sqlGetGroups()){
                // return false;
            // }
            
            // $array = array();
            
            // foreach($groups as $group){
                // $array[$group['id_mission_group']]['name'] = $group['name'];
                // $array[$group['id_mission_group']]['childs'] = array();
                
                // if($types = self::sqlGetTypesByGroupId($group['id_mission_group'])){
                    // foreach($types as $type){
                        // $array[$group['id_mission_group']]['childs'][$type['id_mission_type']] = $type['name'];
                    // }
                // }
            // }
            
            // return $array;
        // }
        
        // sprawdzanie czy misja istnieje
        public static function isMission($id_mission){
            return self::sqlMissionExist($id_mission);
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            // sprawdza, czy data rozpoczecia nie jest mniejsza od daty zakonczenia
            if($this->date_end !== NULL && self::validIsDateTime($this->date_start) && self::validIsDateTime($this->date_end)){
                $date_start = date('Y-m-d H:i:s', strtotime($this->date_start));
                $date_end = date('Y-m-d H:i:s', strtotime($this->date_end));
                
                if($date_start > $date_end){
                    $this->errors[] = "Data rozpoczęcia jest większa od daty zakończenia.";
                    return false;
                }
            }
            
            
            // sprawdzanie czy kategoria ma jakies dziecii i czy glownej kategorii nie chce sie powiazac z inna kategorie glowna
            $item = new ClassMissionType($this->id_mission_type);
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->errors[] = "Rodzaj misji nie istnieje.";
                return false;
            }
            
            // sprawdza czy typ wyposazenia jest aktywny
            if($item->active != '1'){
                $this->errors[] = "Rodzaj misji nie jest aktywny.";
                return false;
            }
            
            // sprawdza wyposazenie chce sie powiazac z kategoria glowna
            if($item->id_parent === NULL){
                $this->errors[] = "Misji nie można powiązać z kategorią główna rodzaju misji.";
                return false;
            }
            
            // sprawdzenie czy rodzic podkategorii nie jest wylaczony
            $item_parent = new ClassMissionType($item->id_parent);
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item_parent->load_class){
                $this->errors[] = "Kategoria główna rodzaju misji nie istnieje.";
                return false;
            }
            
            // sprawdza czy typ wyposazenia jest aktywny
            if($item_parent->active != '1'){
                $this->errors[] = "Kategoria główna rodzaju misji nie jest aktywna.";
                return false;
            }
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas aktualizowania
        public function updateCustomValidate(){
            return $this->addCustomValidate();
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy misja jest powiazana z jakims zolnierzem
            if(self::sqlCheckSoldiersHasMissionById($this->id)){
                $this->errors = "Do misji przypisani są żołnierze.";
                return false;
            }
            
            return true;
        }
        
        // pobieranie nazwy statusu misji
        public static function getStatusMission($date_start, $date_end, $color = true){
            if(strtotime($date_start) < strtotime("now") && (($date_end === NULL || $date_end == '0000-00-00 00:00:00') || strtotime($date_end) > strtotime("now"))){
                return $color ? '<span class="sew_green">Aktywna</span>' : 'Aktywna';
            }
            
            if($date_end !== NULL && $date_end != '0000-00-00 00:00:00' && strtotime($date_end) < strtotime("now")){
                return $color ? '<span class="sew_purple">Zakończona</span>' : 'Zakończona';
            }
            
            if(strtotime($date_start) > strtotime("now")){
                return $color ? '<span class="sew_blue">Nierozpoczęta</span>' : 'Nierozpoczęta';
            }
            
            return false;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
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
        }
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page, $controller_search)){
                foreach($sql as $key => $val){
                    // $sql[$key]['mission_type_name'] = self::sqlGetTypeNameId($val['id_mission_type']);
                    $sql[$key]['mission_type_name'] = ClassMissionType::sqlGetItemNameByIdParent($val['id_mission_type']);
                    $sql[$key]['date_end_name'] = self::getDateEndNameByDateEnd($val['date_end']);
                    // $sql[$key]['status'] = self::getStatusName($val['date_end'], $val['active']);
                    $sql[$key]['status'] = self::getStatusMission($val['date_start'], $val['date_end']);
                }
            }
            
            return $sql;
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
        
        // sprawdzanie czy misja jest powiazana z jakims zolnierzem
        public static function sqlCheckSoldiersHasMissionById($id_mission){
            global $DB;
            
            $zapytanie = "SELECT COUNT(*) as count_soldiers
                FROM `sew_soldier2missions`
                WHERE `deleted` = '0'
                    AND `id_mission` = '{$id_mission}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1 || $sql['count_soldiers'] < 1){
                return false;
            }
            
            return true;
        }
    }
?>
