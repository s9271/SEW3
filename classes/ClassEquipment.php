<?php
    class ClassEquipment extends ClassModel{
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        public static $is_search = true;
        
        // id
        public $id = false;
        
        // Typ wyposazenia
        public $id_equipment_type;
        
        // Nazwa
        public $name;
        
        // Uwagi
        public $description;
        
        // Użytkownik
        public $id_user;
        
        // Data aktualizacji
        public $date_update;
        
        // Aktywny
        public $active;
        
        // Usunięty
        public $deleted = '0';
        
        // Typ wyposazenia nazwa
        public $equipment_type_name;
        
        // Nazwa statusu
        public $active_name;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'equipments',
            'primary' => 'id_equipment',
            'fields' => array(
                'id_equipment_type'     => array('required' => true, 'validate' => array('isInt'), 'name' => 'Typ wyposazenia'),
                'name'                  => array('required' => true, 'name' => 'Nazwa'),
                'description'           => array('name' => 'Uwagi'),
                'id_user'               => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'           => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'active'                => array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted'               => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class)
            {
                // Ilosc stopni nazwa
                $this->equipment_type_name = ClassEquipmentType::sqlGetItemNameByIdParent($this->id_equipment_type);
                
                // Nazwa statusu
                $this->active_name = ClassUser::getNameStatus($this->active);
            }
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            // sprawdzanie czy kategoria ma jakies dziecii i czy glownej kategorii nie chce sie powiazac z inna kategorie glowna
            $item = new ClassEquipmentType($this->id_equipment_type);
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->errors[] = "Typ wyposażenia nie istnieje.";
                return false;
            }
            
            // sprawdza czy typ wyposazenia jest aktywny
            if($item->active != '1'){
                $this->errors[] = "Typ wyposażenia nie jest aktywny.";
                return false;
            }
            
            // sprawdza wyposazenie chce sie powiazac z kategoria glowna
            if($item->id_parent === NULL){
                $this->errors[] = "Wyposażenia nie można powiązać z kategorią główna typu wyposażenia.";
                return false;
            }
            
            // sprawdzenie czy rodzic podkategorii nie jest wylaczony
            $item_parent = new ClassEquipmentType($item->id_parent);
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item_parent->load_class){
                $this->errors[] = "Kategoria główna typu wyposażenia nie istnieje.";
                return false;
            }
            
            // sprawdza czy typ wyposazenia jest aktywny
            if($item_parent->active != '1'){
                $this->errors[] = "Kategoria główna typu wyposażenia nie jest aktywna.";
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
            // sprawdzanie czy jakis zolnierz ma ten ekwipunek
            if(self::checkSoldierHasEquipment($this->id)){
                $this->errors = "Do wyposażenia powiązani są żołnierze.";
                return false;
            }
            
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page, $controller_search))
            {
                foreach($sql as $key => $val)
                {
                    // Rodzaj jednostki nazwa
                    $sql[$key]['equipment_type_name'] = ClassEquipmentType::sqlGetItemNameByIdParent($val['id_equipment_type']);
                    
                    // Nazwa statusu
                    $sql[$key]['active_name'] = ClassUser::getNameStatus($val['active']);
                }
            }
            
            return $sql;
        }
        
        // sprawdzanie czy jakis zolnierz ma ten ekwipunek
        public static function checkSoldierHasEquipment($id_equipment){
            global $DB;
            
            $zapytanie = "SELECT COUNT(*) as count_item
                FROM `sew_soldier2equipments`
                WHERE `deleted` = '0'
                    AND `id_equipment` = '{$id_equipment}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1 || $sql['count_item'] < 1){
                return false;
            }
            
            return true;
        }
    }
?>
