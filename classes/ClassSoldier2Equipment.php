<?php
    class ClassSoldier2Equipment extends ClassModel
    {
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // id zolnierza
        public $id_soldier;
        
        // id wyposazenia
        public $id_equipment;
        
        // id wyposazenia
        public $id_equipment_tmp;
        
        // data przyznania wyposazenia
        public $date_equipment_add;
        
        // ilosc
        public $equipment_count;
        
        // opis
        public $description = '';
        
        // opic podczas zwrotu
        public $description_return = '';
        
        // data zwrotu
        public $date_return = NULL;
        
        // Użytkownik
        public $id_user;
        
        // Zolnierz - zmienna trzymajaca zolnierza poprawnego, potrzebne do sprawdzania podczas usuwania
        public $id_soldier_tmp;
        
        // Data aktualizacji
        public $date_update;
        
        // zwrocony
        public $returned = '0';
        
        // Usunięty
        public $deleted = '0';
        
        // nazwa ekwipunku
        public $equipment_name;
        
        // nazwa statusu
        public $status_name;
        
        // misja start
        public $date_start;
        
        // misja end
        public $date_end;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldier2equipments',
            'primary' => 'id_soldier2equipments',
            'fields' => array(
                'id_soldier'            => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołnierza'),
                'id_equipment'          => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id wyposażenia'),
                'date_equipment_add'    => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data przyznania'),
                'date_return'           => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data zwrotu'),
                'equipment_count'       => array('required' => true, 'validate' => array('isInt'), 'name' => 'Ilość'),
                'description'           => array('name' => 'Opis'),
                'description_return'    => array('name' => 'Opis zwrotu'),
                'id_user'               => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'           => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'returned'              => array('validate' => array('isBool'), 'name' => 'Zwrócony'),
                'deleted'               => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class)
            {
                // przypisanie poprawnego zolnierza do tymczasowej zmiennej
                $this->id_soldier_tmp = $this->id_soldier;
                
                // przypisanie poprawnej misji do tymczasowej zmiennej
                $this->id_equipment_tmp = $this->id_equipment;
                
                // nazwa wyposazenia
                $item = new ClassEquipment($this->id_equipment);
                $this->equipment_name = $item->name;
                
                // Zmiana daty na polski format
                $this->date_equipment_add = date('d.m.Y H:i', strtotime($this->date_equipment_add));
                $this->date_return = $this->date_return  === NULL ? $this->date_return : date('d.m.Y H:i', strtotime($this->date_return));
                
                // nazwa statusu wyposazenia
                $this->status_name = self::getStatusEquipment($this->returned);
            }
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            // sprawdzanie czy item istnieje i jest aktywna
            $item = new ClassEquipment($this->id_equipment);
            
            if(!$item->load_class){
                $this->errors = "Wyposażenie nie istnieje.";
                return false;
            }
            
            if($item->active != '1'){
                $this->errors = "Wyposażenie jest wyłączone.";
                return false;
            }
            
            // sprawdzenie czy zolnierz posiada juz to wyposażenie
            if(self::sqlCheckSoldierHasEquipment($this->id_equipment)){
                $this->errors = "Żołnierz posiada już to wyposażenie.";
                return false;
            }
            
            $this->date_equipment_add = date('Y-m-d H:i:s', strtotime($this->date_equipment_add));
            $this->equipment_name = $item->name;
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas aktualizowania
        public function updateCustomValidate()
        {
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            if($this->id_equipment_tmp != $this->id_equipment){
                $this->errors = "Niepoprawne wyposażenie.";
                return false;
            }
            
            // sprawdzenie czy data zwrotu nie jest mniejsza niz data przyznania
            if($this->returned == '1' && strtotime($this->date_return) <= strtotime($this->date_equipment_add)){
                $this->errors = "Data zwrotu jest mniejsza lub równa dacie przyznania.";
                return false;
            }
            
            $this->date_equipment_add = date('Y-m-d H:i:s', strtotime($this->date_equipment_add));
            
            return true;
        }
        
        // oddelegowanie
        public function equipmentReturn($auto_date = true){
            if(!isset($this->id)){
                $this->errors[] = "Brak podanego id.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_update')) {
                $this->date_update = date('Y-m-d H:i:s');
            }
            
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            if($this->id_equipment_tmp != $this->id_equipment){
                $this->errors = "Niepoprawne wyposażenie.";
                return false;
            }
            
            if($this->returned == '1'){
                $this->errors = "Żołnierz już zwrócił to wyposażenie.";
                return false;
            }
            
            // sprawdzenie czy data zwrotu nie jest mniejsza niz data przyznania
            if(strtotime($this->date_return) <= strtotime($this->date_equipment_add)){
                $this->errors = "Data zwrotu jest mniejsza lub równa dacie przyznania.";
                return false;
            }
            
            $this->date_return = date('Y-m-d H:i:s', strtotime($this->date_return));
            
            if (!$this->sqlReturn(static::$definition['table'], $this->id)){
                $this->errors[] = "Zwrot: Błąd aktualizacji rekordu w bazie.";
                return false;
            }
            
            unset($this->id);
            if($this->load_class){
                $this->load_class = false;
            }
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            global $login;
            
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            if($this->id_equipment_tmp != $this->id_equipment){
                $this->errors = "Niepoprawne wyposażenie.";
                return false;
            }
            
            if($this->returned == '1' && $login->auth_user['id_permission'] != '1'){
                $this->errors = "Żołnierz zwrócił to wyposażenie.<br />Tylko <b>Administrator</b> ma możliwość usunięcia zwróconych wyposażeń żołnierza.";
                return false;
            }
            
            return true;
        }
        
        // pobieranie nazwy statusu odznaczenia
        public static function getStatusEquipment($returned, $color = true){
            if($returned == '1'){
                return $color ? '<span class="sew_orange">Zwrócone</span>' : 'Zwrócone';
            }else{
                return $color ? '<span class="sew_green">Posiadane</span>' : 'Posiadane';
            }
            
            return false;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // sprawdzanie czy zolnierz posiada odznaczenie
        public static function sqlCheckSoldierHasEquipment($id_equipment){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'soldier2equipments';
            
            $zapytanie = "SELECT *
                FROM `{$table_name}`
                WHERE `id_equipment` = '{$id_equipment}'
                    AND `returned` = '0'
                    AND `deleted` = '0'
            ;";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
            
        }
        
        // pobieranie wszystkich rekordow z wyposazeniem
        public static function sqlGetSoldierEquipments($id_soldier, $using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
            global $DB;
            $where = '';
            $limit = '';
            
            if(static::$is_search && $controller_search != '' && $where_search = self::generateWhereList($controller_search))
            {
                $where .= "AND ";
                $where .= $where_search;
            }
            
            if($using_pages){
                $limit_start = ($current_page-1)*$items_on_page;
                $limit = " LIMIT {$limit_start}, {$items_on_page}";
            }
            
            $zapytanie = "SELECT se.`id_soldier2equipments`, se.`id_soldier`, se.`id_equipment`, se.`date_equipment_add`, se.`equipment_count`, se.`description`, se.`description_return`, se.`date_return`, se.`returned`, e.`name` as equipment_name, et.`name` as equipment_name_type
                FROM `sew_soldier2equipments` as se, `sew_equipments` as e, `sew_equipment_types` as et
                WHERE se.`deleted` = '0'
                    AND e.`deleted` = '0'
                    AND se.`id_equipment` = e.`id_equipment`
                    AND e.`id_equipment_type` = et.`id_equipment_type`
                    AND se.`id_soldier` = '{$id_soldier}'
                    {$where}
                ORDER BY `".static::$definition['primary']."`
                {$limit}
            ;";
            
            // print_r($zapytanie);
            $sql = $DB->pdo_fetch_all($zapytanie, true);
            
            if(($sql === false || !is_array($sql)) && (static::$is_search && $controller_search != '' && isset($_SESSION['search'][$controller_search]))){
                if(static::$is_search && isset($_SESSION['search'][$controller_search])){
                    $_SESSION['search'][$controller_search] = array();
                }
            }
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            
            foreach($sql as $key => $val)
            {
                // nazwa statusu wyposazenia
                $sql[$key]['status_name'] = self::getStatusEquipment($val['returned']);
                
                // Zmiana daty na polski format
                $sql[$key]['date_equipment_add'] = date('d.m.Y H:i', strtotime($sql[$key]['date_equipment_add']));
                $sql[$key]['date_return'] = $sql[$key]['date_return'] !== NULL ? date('d.m.Y H:i', strtotime($sql[$key]['date_return'])) : $sql[$key]['date_return'];
            }
            
            return $sql;
        }
        
        // oddelegowanie
        protected function sqlReturn($table, $id_item){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').$table;
            $where = static::$definition['primary'].' = '.$id_item;
            
            if(static::$is_log){
                $table_name_log = static::$prefix_log.$table;
                
                if(!$item_to_log = self::sqlGetItem($id_item)){
                    $this->errors[] = "LOG: Błąd podczas pobierania rekordu z bazy.";
                    return false;
                }
                
                if(!$DB->insert($table_name_log, $item_to_log)){
                    $this->errors[] = "LOG: Błąd podczas zapisywania rekordu w tabeli z logami.";
                    return false;
                }
            }
            
            $data = array(
                'description_return'    => $this->description_return,
                'id_user'               => $this->id_user,
                'date_return'           => $this->date_return,
                'returned'              => '1',
            );
            
            if (property_exists($this, 'date_update')) {
                $data['date_update'] = $this->date_update;
            }
            
            return $DB->update($table_name, $data, $where);
        }
        
        /* *************** AJAX ************** */
        /* *********************************** */
        
        // wyszukiwanie misji dla zolnierzy
        public static function sqlSearchEquipmentsForSoldier($ajax_get){
            if(!isset($ajax_get['id_soldier']) || $ajax_get['id_soldier'] == ''){
                return array('error' => 'Nie podano identyfikatora żołnierza.');
            }
            
            global $DB;
            $array['items'] = array();
            
            // sprawdzanie czy zolnierz istnieje
            $soldier = new ClassSoldier($ajax_get['id_soldier']);
            
            if(!$soldier->load_class){
                return array('error' => 'Żołnierz nie istnieje.');
            }
            
            // wyszukiwanie wyposazenia
            $table_equipment = 'sew_equipments';
            $sql_search = $DB->search($table_equipment, array('name' => "%{$ajax_get['search']}%"), '`id_equipment`, `name`, `id_equipment_type`', "`deleted` = '0' AND `active` = '1'");
            // print_r($sql_search);
            
            // gdy nie ma misji wyswietli brak
            if(!$sql_search){
                return $array;
            }
            
            // pobieranie misji z ktorymi zolnierz juz jest powiazany
            $soldier_equipments = self::sqlGetAllItems(false, '', '', '', array('id_soldier' => $ajax_get['id_soldier']));
            
            // print_r($soldier_equipments);
            $array_tmp = array();
            
            if(!$soldier_equipments){
                $array['items'] = array();
                $i = -1;
            
                foreach($sql_search as $equipment){
                    if(!in_array($equipment['id_equipment_type'], $array_tmp)){
                        $i++;
                        $array_tmp[] = $equipment['id_equipment_type'];
                        $equipment_type = new ClassEquipmentType($equipment['id_equipment_type']);
                        $array['items'][$i]['text'] = $equipment_type->name;
                    }
                    
                    $array['items'][$i]['children'][] = array('id' => $equipment['id_equipment'], 'text' => $equipment['name']);
                }
            }else{
                $soldier_equipments_mod = array();
                
                foreach($soldier_equipments as $soldier_equipment)
                {
                    $soldier_equipments_mod[$soldier_equipment['id_equipment']] = $soldier_equipment;
                }
            
                $i = -1;
                
                foreach($sql_search as $equipment)
                {
                    if(!in_array($equipment['id_equipment_type'], $array_tmp))
                    {
                        $i++;
                        $array_tmp[] = $equipment['id_equipment_type'];
                        $equipment_type = new ClassEquipmentType($equipment['id_equipment_type']);
                        $array['items'][$i]['text'] = $equipment_type->name;
                    }
                    
                    
                    if(isset($soldier_equipments_mod[$equipment['id_equipment']]) && $soldier_equipments_mod[$equipment['id_equipment']]['returned'] == '0')
                    {
                        $array['items'][$i]['children'][] = array('id' => $equipment['id_equipment'], 'text' => $equipment['name'], 'disabled' => true);
                    }else
                    {
                        $array['items'][$i]['children'][] = array('id' => $equipment['id_equipment'], 'text' => $equipment['name']);
                    }
                }
            }
            
            return $array;
        }
    }
?>
