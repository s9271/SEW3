<?php
    class ClassSoldier2Training extends ClassModel
    {
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // id zolnierza
        public $id_soldier;
        
        // Zolnierz - zmienna trzymajaca zolnierza poprawnego, potrzebne do sprawdzania podczas usuwania
        public $id_soldier_tmp;
        
        // id szkolenia
        public $id_training;
        
        // id szkolenia - tymczasowy
        public $id_training_tmp;
        
        // opis
        public $description = '';
        
        // opic podczas odsylania
        public $description_return = '';
        
        // Użytkownik
        public $id_user;
        
        // Data aktualizacji
        public $date_update;
        
        // odeslany
        public $returned = '0';
        
        // Usunięty
        public $deleted = '0';
        
        // nazwa szkolenia
        public $training_name;
        
        // nazwa statusu
        public $status_name;
        
        // misja start
        public $date_start;
        
        // misja end
        public $date_end;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldier2trainings',
            'primary' => 'id_soldier2trainings',
            'fields' => array(
                'id_soldier'            => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołnierza'),
                'id_training'           => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id szkolenia'),
                'description'           => array('name' => 'Opis'),
                'description_return'    => array('name' => 'Opis odesłania'),
                'id_user'               => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'           => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'returned'              => array('validate' => array('isBool'), 'name' => 'Odesłany'),
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
                
                // przypisanie poprawnego szkolenia do tymczasowej zmiennej
                $this->id_training_tmp = $this->id_training;
                
                // nazwa statusu szkolenia
                $item = new ClassTraining($this->id_training);
                // $sql[$key]['status_name'] = self::getStatusTraining($val['date_start'], $val['date_end'], $val['returned']);
                // $this->status_name = self::getStatusName($item->date_end, $this->returned);
                $this->status_name = self::getStatusTraining($item->date_start, $item->date_end, $this->returned);
                
                // data rozpoczęcia
                $this->date_start = date('d.m.Y H:i', strtotime($item->date_start));
                
                // data zakonczenia
                $this->date_end  = $item->date_end  === NULL || $item->date_end  == '0000-00-00 00:00:00' ? 'Niezdefiniowano' : date('d.m.Y H:i', strtotime($item->date_end));
            }
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            // sprawdzanie czy szkolenie istnieje i jest aktywna
            $item = new ClassTraining($this->id_training);
            
            if(!$item->load_class){
                $this->errors = "Szkolenie nie istnieje.";
                return false;
            }
            
            if($item->active != '1'){
                $this->errors = "Szkolenie jest wyłączone.";
                return false;
            }
            
            // sprawdzenie czy zolnierz posiada szkolenia
            $soldier_trainings = ClassSoldier2Training::sqlGetSoldierTrainings($this->id_soldier);
            
            if($soldier_trainings)
            {
                foreach($soldier_trainings as $soldier_training)
                {
                    // sprawdzenie czy zolnierz chce 2x do tej samej misji zostac przypisany
                    if($soldier_training['id_training'] == $this->id_training && $soldier_training['returned'] == '0'){
                        $this->errors = "Żołnierz posiada już te szkolenie.";
                        return false;
                    }
                    
                    // sprawdzanie czy nowa misja koliduje z jakas inna misja, na ktorej zolnierz jest
                    if($soldier_training['returned'] == '0' && self::checkInterferingDates($soldier_training['date_start'], $item->date_start, $soldier_training['date_end'], $item->date_end)){
                        $this->errors = "Szkolenie <b>{$item->name}</b> koliduje czasowo ze szkoleniem <b>{$soldier_training['name']}</b>.";
                        return false;
                    }
                }
            }
            
            // sprawdzenie czy zolnierz posiada misje
            $soldier_missions = ClassSoldier2Mission::sqlGetSoldierMissions($this->id_soldier);
            
            if($soldier_missions)
            {
                foreach($soldier_missions as $soldier_mission)
                {
                    // sprawdzanie czy nowe szkolenie koliduje z jakas inna misja, na ktorej zolnierz jest
                    if($soldier_mission['detached'] == '0' && self::checkInterferingDates($soldier_mission['date_start'], $item->date_start, $soldier_mission['date_end'], $item->date_end)){
                        $this->errors = "Szkolenie <b>{$item->name}</b> koliduje czasowo z misją <b>{$soldier_mission['name']}</b>.";
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
            
            if($this->id_training_tmp != $this->id_training){
                $this->errors = "Niepoprawne szkolenie.";
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
            
            if($this->id_training_tmp != $this->id_training){
                $this->errors = "Niepoprawne szkolenie.";
                return false;
            }
            
            if($this->returned == '1' && $login->auth_user['id_permission'] != '1'){
                $this->errors = "Żołniez jest odesłany z tego szkolenia.<br />Tylko <b>Administrator</b> ma możliwość usunięcia odesłanych żołnierzy.";
                return false;
            }
            
            return true;
        }
        
        // dodawanie
        public function add($auto_date = true){
            if(!parent::add($auto_date)){
                return false;
            }
            
            $item = new ClassTraining($this->id_training);
            $this->training_name = $item->name;
            
            return true;
        }
        
        // pobieranie nazwy statusu szkolenia
        public static function getStatusTraining($date_start, $date_end, $returned, $color = true){
            if($returned == '1'){
                return $color ? '<span class="sew_orange">Odesłany</span>' : 'Odesłany';
            }
            
            if($date_end === NULL || $date_end == '0000-00-00 00:00:00'){
                return $color ? '<span class="sew_green">Aktywne</span>' : 'Aktywne';
            }
            
            if(strtotime($date_end) < strtotime("now")){
                return $color ? '<span class="sew_purple">Zakończone</span>' : 'Zakończone';
            }
            
            if(strtotime($date_end) > strtotime("now") && strtotime($date_start) > strtotime("now")){
                return $color ? '<span class="sew_blue">Nierozpoczęte</span>' : 'Nierozpoczęte';
            }
            
            return false;
        }
        
        // oddelegowanie
        public function trainingReturn($auto_date = true){
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
            
            if($this->id_training_tmp != $this->id_training){
                $this->errors = "Niepoprawne szkolenie.";
                return false;
            }
            
            if($this->returned == '1'){
                $this->errors = "Żołnierz już jest odesłany z tego szkolenia.";
                return false;
            }
            
            if (!$this->sqlReturn(static::$definition['table'], $this->id)){
                $this->errors[] = "Odesłanie: Błąd aktualizacji rekordu w bazie.";
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
        public static function sqlGetSoldierTrainings($id_soldier, $using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
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
            
            $zapytanie = "SELECT sm.`id_soldier2trainings`, sm.`id_soldier`, sm.`id_training`, sm.`description`, sm.`description_return`, sm.`returned`, s.`name`, s.`date_start`, s.`date_end`
                FROM `sew_soldier2trainings` as sm, `sew_trainings` as s
                WHERE sm.`deleted` = '0'
                    AND s.`deleted` = '0'
                    AND sm.`id_training` = s.`id_training`
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
                // nazwa statusu szkolenia
                $sql[$key]['status_name'] = self::getStatusTraining($val['date_start'], $val['date_end'], $val['returned']);
                
                // data rozpoczęcia
                $sql[$key]['date_start'] = date('d.m.Y H:i', strtotime($sql[$key]['date_start']));
                
                // data zakonczenia
                $sql[$key]['date_end'] = $sql[$key]['date_end'] === NULL || $sql[$key]['date_end'] == '0000-00-00 00:00:00' ? 'Niezdefiniowano' : date('d.m.Y H:i', strtotime($sql[$key]['date_end']));
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
                'returned'              => '1',
            );
            
            if (property_exists($this, 'date_update')) {
                $data['date_update'] = $this->date_update;
            }
            
            return $DB->update($table_name, $data, $where);
        }
        
        /* *************** AJAX ************** */
        /* *********************************** */
        
        // wyszukiwanie szkolen dla zolnierzy
        public static function sqlSearchTrainingsForSoldier($ajax_get){
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
            
            // wyszukiwanie szkolen
            $table_training = 'sew_trainings';
            $sql_search = $DB->search($table_training, array('name' => "%{$ajax_get['search']}%"), '`id_training`, `name`', "`deleted` = '0' AND `active` = '1'");
            
            // gdy nie ma szkolen wyswietli brak
            if(!$sql_search){
                return $array;
            }
            
            // pobieranie szkolen z ktorymi zolnierz juz jest powiazany
            $soldier_trainigs = self::sqlGetAllItems(false, '', '', '', array('id_soldier' => $ajax_get['id_soldier']));
            
            if(!$soldier_trainigs){
                foreach($sql_search as $training){
                    $array['items'][] = array('id' => $training['id_training'], 'text' => $training['name']);
                }
            }else{
                $soldier_trainigs_mod = array();
                
                foreach($soldier_trainigs as $soldier_training)
                {
                    $soldier_trainigs_mod[$soldier_training['id_training']] = $soldier_training;
                }
            
                $i = 0;
                
                foreach($sql_search as $training){
                    $array['items'][$i] = array('id' => $training['id_training'], 'text' => $training['name']);
                    
                    if(isset($soldier_trainigs_mod[$training['id_training']]) && $soldier_trainigs_mod[$training['id_training']]['returned'] == '0'){
                        $array['items'][$i]['disabled'] = true;
                    }
                    $i++;
                }
            }
            
            return $array;
        }
    }
?>
