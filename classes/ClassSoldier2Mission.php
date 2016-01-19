<?php
    class ClassSoldier2Mission extends ClassModel
    {
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // id zolnierza
        public $id_soldier;
        
        // id misji
        public $id_mission;
        
        // id misji
        public $id_mission_tmp;
        
        // opis
        public $description = '';
        
        // opic podczas oddelegowania
        public $description_detach = '';
        
        // Użytkownik
        public $id_user;
        
        // Zolnierz - zmienna trzymajaca zolnierza poprawnego, potrzebne do sprawdzania podczas usuwania
        public $id_soldier_tmp;
        
        // Data aktualizacji
        public $date_update;
        
        // Oddelegowany
        public $detached = '0';
        
        // Usunięty
        public $deleted = '0';
        
        // nazwa misji
        public $mission_name;
        
        // nazwa statusu
        public $status_name;
        
        // misja start
        public $date_start;
        
        // misja end
        public $date_end;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldier2missions',
            'primary' => 'id_soldier2missions',
            'fields' => array(
                'id_soldier'            => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołnierza'),
                'id_mission'            => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id misji'),
                'description'           => array('name' => 'Opis'),
                'description_detach'    => array('name' => 'Opis oddelegowania'),
                'id_user'               => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'           => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'detached'              => array('validate' => array('isBool'), 'name' => 'Oddelegowany'),
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
                $this->id_mission_tmp = $this->id_mission;
                
                // nazwa statusu misji
                $item = new ClassMission($this->id_mission);
                $this->status_name = self::getStatusMission($item->date_start, $item->date_end, $this->detached);
                
                // data rozpoczęcia
                $this->date_start = date('d.m.Y H:i', strtotime($item->date_start));
                
                // data zakonczenia
                $this->date_end  = $this->date_end  === NULL || $this->date_end  == '0000-00-00 00:00:00' ? 'Niezdefiniowano' : date('d.m.Y H:i', strtotime($item->date_end));
            }
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            // sprawdzanie czy misja istnieje i jest aktywna
            $item = new ClassMission($this->id_mission);
            
            if(!$item->load_class){
                $this->errors = "Misja nie istnieje.";
                return false;
            }
            
            if($item->active != '1'){
                $this->errors = "Misja jest wyłączona.";
                return false;
            }
            
            // sprawdzenie czy zolnierz posiada misje
            $soldier_missions = self::sqlGetSoldierMissions($this->id_soldier);
            
            if($soldier_missions)
            {
                foreach($soldier_missions as $soldier_mission)
                {
                    // sprawdzenie czy zolnierz chce 2x do tej samej misji zostac przypisany
                    if($soldier_mission['id_mission'] == $this->id_mission && $soldier_mission['detached'] == '0'){
                        $this->errors = "Żołnierz posiada już tą misję.";
                        return false;
                    }
                    
                    // sprawdzanie czy nowa misja koliduje z jakas inna misja, na ktorej zolnierz jest
                    if($soldier_mission['detached'] == '0' && self::checkInterferingDates($soldier_mission['date_start'], $item->date_start, $soldier_mission['date_end'], $item->date_end)){
                        $this->errors = "Misja <b>{$item->name}</b> koliduje czasowo z misją <b>{$soldier_mission['name']}</b>.";
                        return false;
                    }
                }
            }
            
            // sprawdzenie czy zolnierz posiada szkolenia
            $soldier_trainings = ClassSoldier2Training::sqlGetSoldierTrainings($this->id_soldier);
            
            if($soldier_trainings)
            {
                foreach($soldier_trainings as $soldier_training)
                {
                    // sprawdzanie czy nowa misja koliduje z jakas inna misja, na ktorej zolnierz jest
                    if($soldier_training['returned'] == '0' && self::checkInterferingDates($soldier_training['date_start'], $item->date_start, $soldier_training['date_end'], $item->date_end)){
                        $this->errors = "Misja <b>{$item->name}</b> koliduje czasowo ze szkoleniem <b>{$soldier_training['name']}</b>.";
                        return false;
                    }
                }
            }
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas aktualizowania
        public function updateCustomValidate()
        {
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            if($this->id_mission_tmp != $this->id_mission){
                $this->errors = "Niepoprawna misja.";
                return false;
            }
            
            return true;
        }
        
        public static function checkInterferingDates($date_start1, $date_start2, $date_end1, $date_end2)
        {
            $date_start1 = strtotime($date_start1);
            $date_start2 = strtotime($date_start2);
            $date_end1 = $date_end1 === NULL || $date_end1 == '0000-00-00 00:00:00' ? NULL : strtotime($date_end1);
            $date_end2 = $date_end2 === NULL || $date_end2 == '0000-00-00 00:00:00' ? NULL : strtotime($date_end2);
            
            if($date_end1 === NULL && $date_end2 === NULL){
                return true;
            }
            
            if($date_end2 === NULL && $date_end1 !== NULL)
            {
                if ($date_start2 < $date_end1){
                    return true;
                }
            }
            
            if($date_end2 !== NULL && $date_end1 === NULL)
            {
                if ($date_start1 < $date_end2){
                    return true;
                }
            }
            
            if($date_end1 !== NULL && $date_end2 !== NULL)
            {
                if (($date_start2 > $date_start1 && $date_start2 < $date_end1) || ($date_end2 > $date_start1 && $date_end2 < $date_end1)){
                    return true;
                }
            }
            
            return false;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            global $login;
            
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            if($this->id_mission_tmp != $this->id_mission){
                $this->errors = "Niepoprawna misja.";
                return false;
            }
            
            if($this->detached == '1' && $login->auth_user['id_permission'] != '1'){
                $this->errors = "Żołniez jest oddelegowany od tej misji.<br />Tylko <b>Administrator</b> ma możliwość usunięcia oddelogowanych żołnierzy.";
                return false;
            }
            
            return true;
        }
        
        // dodawanie
        public function add($auto_date = true){
            if(!parent::add($auto_date)){
                return false;
            }
            
            $mission = new ClassMission($this->id_mission);
            $this->mission_name = $mission->name;
            
            return true;
        }
        
        // pobieranie nazwy statusu misji
        public static function getStatusMission($date_start, $date_end, $detached, $color = true){
            if($detached == '1'){
                return $color ? '<span class="sew_orange">Oddelegowany</span>' : 'Oddelegowany';
            }
            
            if(strtotime($date_start) < strtotime("now") && ($date_end === NULL || $date_end == '0000-00-00 00:00:00' || strtotime($date_end) > strtotime("now"))){
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
        
        // oddelegowanie
        public function detach($auto_date = true){
            if(!isset($this->id)){
                $this->errors[] = "Brak podanego id.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_update')) {
                $this->date_update = date('Y-m-d H:i:s');
            }
            
            // dodatkowe wlasne walidacje podczas usuwania
            // if(!$this->deleteCustomValidate()){
                // return false;
            // }
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            if($this->id_mission_tmp != $this->id_mission){
                $this->errors = "Niepoprawna misja.";
                return false;
            }
            
            if($this->detached == '1'){
                $this->errors = "Żołnierz już jest oddelegowany od tej misji.";
                return false;
            }
            
            if (!$this->sqlDetach(static::$definition['table'], $this->id)){
                $this->errors[] = "Oddelegowanie: Błąd aktualizacji rekordu w bazie.";
                return false;
            }
            
            unset($this->id);
            if($this->load_class){
                $this->load_class = false;
            }
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = '', array $custom_where = array()){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page, $controller_search, $custom_where))
            {
                foreach($sql as $key => $val)
                {
                    // nazwa stopnia zaawansowania jezyka
                    // $sql[$key]['language_level_name'] = ClassLanguageLevel::sqlGetItemNameByIdParent($val['id_language_level']);
                }
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich rekordow z misjami
        public static function sqlGetSoldierMissions($id_soldier, $using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
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
            
            $zapytanie = "SELECT sm.`id_soldier2missions`, sm.`id_soldier`, sm.`id_mission`, sm.`description`, sm.`description_detach`, sm.`detached`, s.`name`, s.`date_start`, s.`date_end`
                FROM `sew_soldier2missions` as sm, `sew_missions` as s
                WHERE sm.`deleted` = '0'
                    AND s.`deleted` = '0'
                    AND sm.`id_mission` = s.`id_mission`
                    AND sm.`id_soldier` = '{$id_soldier}'
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
                // nazwa statusu misji
                $sql[$key]['status_name'] = self::getStatusMission($val['date_start'], $val['date_end'], $val['detached']);
                
                // data rozpoczęcia
                $sql[$key]['date_start'] = date('d.m.Y H:i', strtotime($sql[$key]['date_start']));
                
                // data zakonczenia
                $sql[$key]['date_end'] = $sql[$key]['date_end'] === NULL || $sql[$key]['date_end'] == '0000-00-00 00:00:00' ? 'Niezdefiniowano' : date('d.m.Y H:i', strtotime($sql[$key]['date_end']));
            }
            
            return $sql;
        }
        
        // oddelegowanie
        protected function sqlDetach($table, $id_item){
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
                'description_detach'    => $this->description_detach,
                'id_user'               => $this->id_user,
                'detached'              => '1',
            );
            
            if (property_exists($this, 'date_update')) {
                $data['date_update'] = $this->date_update;
            }
            
            return $DB->update($table_name, $data, $where);
        }
        
        /* *************** AJAX ************** */
        /* *********************************** */
        
        // wyszukiwanie misji dla zolnierzy
        public static function sqlSearchMissionForSoldier($ajax_get){
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
            
            // wyszukiwanie misji
            $table_mission = 'sew_missions';
            $sql_search = $DB->search($table_mission, array('name' => "%{$ajax_get['search']}%"), '`id_mission`, `name`', "`deleted` = '0' AND `active` = '1'");
            
            // gdy nie ma misji wyswietli brak
            if(!$sql_search){
                return $array;
            }
            
            // pobieranie misji z ktorymi zolnierz juz jest powiazany
            $soldier_missions = self::sqlGetAllItems(false, '', '', '', array('id_soldier' => $ajax_get['id_soldier']));
            
            if(!$soldier_missions){
                foreach($sql_search as $mission){
                    $array['items'][] = array('id' => $mission['id_mission'], 'text' => $mission['name']);
                }
            }else{
                $soldier_missions_mod = array();
                
                foreach($soldier_missions as $soldier_mission)
                {
                    $soldier_missions_mod[$soldier_mission['id_mission']] = $soldier_mission;
                }
            
                $i = 0;
                
                foreach($sql_search as $mission){
                    $array['items'][$i] = array('id' => $mission['id_mission'], 'text' => $mission['name']);
                    
                    if(isset($soldier_missions_mod[$mission['id_mission']]) && $soldier_missions_mod[$mission['id_mission']]['detached'] == '0'){
                        $array['items'][$i]['disabled'] = true;
                    }
                    $i++;
                }
            }
            
            return $array;
        }
    }
?>
