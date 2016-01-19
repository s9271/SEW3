<?php
    class ClassSoldier2Missions extends ClassSoldierModel{
        
        // nazwa tabeli 1
        protected static $table1 = 'soldier2missions';
        
        // nazwa tabeli 2
        protected static $table2 = 'soldier2missions_options';
        
        // primary id
        protected static $primary = 'id_soldier2missions';
        
        // primary id powiazany z id tabeli 1
        protected static $foreign_key = 'id_soldier2missions';
        
        // imie i nazwisko zolnierza
        public $soldier_name;
        
        // nazwa misji
        public $mission_name;
        
        // id misji
        public $id_mission;
        
        // walidacja, kolumny
        public static $definition = array(
            'fields' => array(
                'id_soldier' =>                 array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołonierza'),
                'id_mission' =>                 array('required' => true, 'validate' => array('isInt'), 'name' => 'Id misji'),
                'deleted' =>                    array('validate' => array('isBool'), 'name' => 'Usunięty'),
                'deleted_pernament' =>           array('validate' => array('isBool'), 'name' => 'Pernamentne usunięcie'),
                'id_user_add' =>                array('required' => true, 'validate' => array('isInt'), 'name' => 'Id użytkownika dodawania'),
                'id_user_delete' =>             array('required' => true, 'validate' => array('isInt'), 'name' => 'Id użytkownika usuwania'),
                'date_add' =>                   array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data dodania'),
                'date_delete' =>                array('validate' => array('isDateTime'), 'name' => 'Data usunięcia'),
                'description_add' =>            array('name' => 'Opis'),
                'description_delete' =>         array('name' => 'Opis')
            ),
        );
        
        /* ************** FUNKCJE ************* */
        /* ************************************ */
        
        // sprawdzanie czy zolnierz posiada dana misje
        public static function soldierHasMission($id_soldier, $id_mission){
            return self::sqlSoldierHasMission($id_soldier, $id_mission);
        }
        
        // sprawdzenie czy misja nie koliduje ze szkoleniem
        public static function checkSoldierMissionConflictWithTraining($id_soldier, $id_mission){
            // pobieranie szkolen zolnierza
            if(!$trainings = ClassSoldier2Trainings::sqlGetSoldier2Trainings($id_soldier)){
                return false;
            }
            
            
            // return self::sqlGetSoldier2Trainings($id_soldier);
        }
        // dodawanie
        public function add(){
            if(parent::add()){
                $soldier = ClassSoldier::sqlGetItem($this->id_soldier);
                $this->soldier_name = $soldier['soldierName'].' '.$soldier['soldierSurname'];
                
                $mission = ClassMission::sqlGetItem($this->id_mission);
                $this->mission_name = $mission['name'];
                return true;
            }
            return false;
        }
        
        // usuwanie
        public function delete($auto_date = true){
            if(!isset($this->id)){
                $this->errors[] = "Brak podanego id.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_delete')) {
                $this->date_delete = date('Y-m-d H:i:s');
            }
            
            if (!$this->sqlDeleteMissionFromSoldier()){
                $this->errors[] = "Błąd usuwania rekordu z bazy.";
                return false;
            }
            
            unset($this->id);
            if($this->load_class){
                $this->load_class = false;
            }
            return true;
        }
        
        // usuwanie
        public function seconded($auto_date = true){
            if(!isset($this->id)){
                $this->errors[] = "Brak podanego id.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_delete')) {
                $this->date_delete = date('Y-m-d H:i:s');
            }
            
            if (!$this->sqlSecondedMissionFromSoldier()){
                $this->errors[] = "Błąd usuwania rekordu z bazy.";
                return false;
            }
            
            unset($this->id);
            if($this->load_class){
                $this->load_class = false;
            }
            return true;
        }
        
        // pobieranie listy misji zolnierza
        public static function getSoldierMissions($id_soldier, $using_pages = false, $current_page = '1', $items_on_page = '5'){
            if(!$soldier_missions = self::sqlGetSoldierMissionsToList($id_soldier, $using_pages, $current_page, $items_on_page)){
                return false;
            }
            
            foreach($soldier_missions as $key => $soldier_mission){
                $soldier_missions[$key]['date_add'] = self::getPlDate($soldier_mission['date_add']);
                $soldier_missions[$key]['date_start'] = self::getPlDate($soldier_mission['date_start']);
                $soldier_missions[$key]['date_end'] = self::getPlDate($soldier_mission['date_end']);
                $soldier_missions[$key]['user_name'] = $soldier_mission['first_name'].' '.$soldier_mission['second_name'];
                $soldier_missions[$key]['status'] = self::getStatusMissionList($soldier_mission['deleted'], $soldier_mission['date_end'], $soldier_mission['mission_deleted'], $soldier_mission['mission_active']);
            }
            
            return $soldier_missions;
        }
        
        // pobieranie misji zolnierza
        public static function getSoldierMission($id_soldier2missions, $id_soldier, $id_mission, $date_end, $mission_active){
            if(!$soldier_mission = self::sqlGetSoldier2Mission($id_soldier2missions, $id_soldier, $id_mission)){
                return false;
            }
            
            $soldier_mission['date_add'] = self::getPlDate($soldier_mission['date_add']);
            $soldier_mission['user_name'] = $soldier_mission['first_name'].' '.$soldier_mission['second_name'];
            $soldier_mission['status'] = self::getStatusMissionList($soldier_mission['deleted'], $date_end, '0', $mission_active);
            
            if($soldier_mission['deleted'] == '1'){
                $soldier_mission_deleted = self::sqlGetSoldier2MissionDeleted($id_soldier2missions);
                $soldier_mission['date_delete'] = self::getPlDate($soldier_mission_deleted['date_delete']);
                $soldier_mission['user_name_delete'] = $soldier_mission_deleted['first_name'].' '.$soldier_mission_deleted['second_name'];
                $soldier_mission['description_delete'] = $soldier_mission_deleted['description_delete'];
            }
            
            return $soldier_mission;
        }
        
        // pobieranie status misji z listy
        public static function getStatusMissionList($seconded, $mission_date_end, $mission_deleted, $mission_active, $name = true, $color = true){
            $status = false;
            
            if($seconded == '1'){
                $status = '0'; // oddelegowany
            }elseif($mission_deleted == '1'){
                $status = '1'; // misja usunieta
            }elseif($mission_active == '1' && (($mission_date_end == NULL || $mission_date_end == '0000-00-00 00:00:00') || (strtotime($mission_date_end) > strtotime("now")))){
                $status = '2';
            }elseif($mission_date_end != NULL && $mission_date_end != '0000-00-00 00:00:00' && (strtotime($mission_date_end) <= strtotime("now"))){
                $status = '3';
            }elseif($mission_active == '0'){
                $status = '4';
            }
            
            if($name){
                switch($status){
                    case '0':
                        return $color ? '<span class="sew_orange">Oddelegowany</span>' : 'Oddelegowany';
                    break;
                    case '1':
                        return $color ? '<span class="sew_red">Misja usunięta</span>' : 'Misja usunięta';
                    break;
                    case '2':
                        return $color ? '<span class="sew_green">Aktywny</span>' : 'Aktywny';
                    break;
                    case '3':
                        return $color ? '<span class="sew_purple">Zakończony</span>' : 'Zakończony';
                    break;
                    case '4':
                        return $color ? '<span class="sew_orange">Nieaktywne</span>' : 'Nieaktywne';
                    break;
                }
            }
            
            return $status;
        }
        
        // sprawdzanie czy misja istnieje w zolnierzu
        public static function checkMissionInSoldier($id_soldier2missions){
            return self::sqlCheckMissionInSoldier($id_soldier2missions);
        }
        
        // sprawdzanie czy misja istnieje w zolnierzu i nie jest oddelegowany
        public static function checkMissionInSoldier2($id_soldier2missions){
            return self::sqlCheckMissionInSoldier2($id_soldier2missions);
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie misji z ktorymi zolnierz juz jest powiazany
        protected function sqlDeleteMissionFromSoldier(){
            global $DB;
            
            $table_name1 = (self::$use_prefix ? self::$prefix : '').self::$table1;
            $table_name2 = (self::$use_prefix ? self::$prefix : '').self::$table2;
            $where = '`id_soldier2missions` = '.$this->id;
            
            $data1 = array();
            $data2 = array();
            $data1['deleted_pernament'] = '1';
            $data2['id_user_delete'] = $this->id_user_delete;
            $data2['date_delete'] = $this->date_delete;
            
            if(!$DB->update($table_name1, $data1, $where) || !$DB->update($table_name2, $data2, $where)){
                return false;
            }
            
            return true;
        }
        
        // oddelegowywanie zolnierza z misji
        protected function sqlSecondedMissionFromSoldier(){
            global $DB;
            
            $table_name1 = (self::$use_prefix ? self::$prefix : '').self::$table1;
            $table_name2 = (self::$use_prefix ? self::$prefix : '').self::$table2;
            $where = '`id_soldier2missions` = '.$this->id;
            
            $data1 = array();
            $data2 = array();
            $data1['deleted'] = '1';
            $data2['id_user_delete'] = $this->id_user_delete;
            $data2['date_delete'] = $this->date_delete;
            $data2['description_delete'] = $this->description_delete;
            
            if(!$DB->update($table_name1, $data1, $where) || !$DB->update($table_name2, $data2, $where)){
                return false;
            }
            
            return true;
        }
        
        // pobieranie misji z ktorymi zolnierz juz jest powiazany
        public static function sqlGetSoldier2Missions($id_soldier){
            global $DB;
            $zapytanie = "SELECT `id_mission`, `id_soldier2missions`, `id_soldier` FROM `sew_soldier2missions` WHERE `id_soldier` = '{$id_soldier}' AND `deleted` = '0' AND `deleted_pernament` = '0'";
            $sql = $DB->pdo_fetch_all_group($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie misji zolnierza
        public static function sqlGetSoldier2Mission($id_soldier2missions, $id_soldier, $id_mission){
            global $DB;
            
            $table_name1 = (self::$use_prefix ? self::$prefix : '').self::$table1;
            $table_name2 = (self::$use_prefix ? self::$prefix : '').self::$table2;
            $table_mission = (ClassMission::$use_prefix ? ClassMission::$prefix : '').ClassMission::$definition['table'];
            // $table_user = (ClassUser::$use_prefix ? ClassUser::$prefix : '').ClassUser::$definition['table'];
            $table_user = 'users';
            
            $zapytanie = "SELECT t1.`id_soldier2missions`, t1.`id_soldier`, t1.`id_mission`, t1.`deleted`, t2.`date_add`, t2.`date_delete`, t2.`description_add`, u.`first_name`, u.`second_name`
                FROM `{$table_name1}` as t1, `{$table_name2}` as t2, `{$table_user}` as u
                WHERE t1.`id_soldier` = '{$id_soldier}'
                    AND t1.`id_mission` = '{$id_mission}'
                    AND t1.`deleted_pernament` = '0'
                    AND t1.`id_soldier2missions` = t2.`id_soldier2missions`
                    AND t1.`id_soldier2missions` = {$id_soldier2missions}
                    AND u.`user_id` = t2.`id_user_add`
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie misji zolnierza
        public static function sqlGetSoldier2MissionDeleted($id_soldier2missions){
            global $DB;
            
            $table_name2 = (self::$use_prefix ? self::$prefix : '').self::$table2;
            // $table_user = (ClassUser::$use_prefix ? ClassUser::$prefix : '').ClassUser::$definition['table'];
            $table_user = 'users';
            
            $zapytanie = "SELECT t2.`id_soldier2missions`, t2.`date_delete`, t2.`description_delete`, u.`first_name`, u.`second_name`
                FROM `{$table_name2}` as t2, `{$table_user}` as u
                WHERE u.`user_id` = t2.`id_user_add`
                    AND t2.`id_soldier2missions` = {$id_soldier2missions}
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // sprawdzanie czy zolnierz posiada dana misje
        public static function sqlSoldierHasMission($id_soldier, $id_mission){
            global $DB;
            $zapytanie = "SELECT `id_soldier`
                FROM `sew_soldier2missions`
                WHERE `id_soldier` = '{$id_soldier}'
                    AND `id_mission` = '{$id_mission}'
                    AND `deleted` = '0'
                    AND `deleted_pernament` = '0'
            ;";
                
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
        
        // pobieranie listy misji zolnierzy
        public static function sqlGetSoldierMissionsToList($id_soldier, $using_pages = false, $current_page = '1', $items_on_page = '5'){
            global $DB;
            $limit = '';
            
            if($using_pages){
                $limit_start = ($current_page-1)*$items_on_page;
                $limit = " LIMIT {$limit_start}, {$items_on_page}";
            }
            
            $table_name1 = (self::$use_prefix ? self::$prefix : '').self::$table1;
            $table_name2 = (self::$use_prefix ? self::$prefix : '').self::$table2;
            $table_mission = (ClassMission::$use_prefix ? ClassMission::$prefix : '').ClassMission::$definition['table'];
            // $table_user = (ClassUser::$use_prefix ? ClassUser::$prefix : '').ClassUser::$definition['table'];
            $table_user = 'users';
            
            $zapytanie = "SELECT t1.`id_soldier2missions`, t1.`id_soldier`, t1.`id_mission`, t1.`deleted`, t2.`date_add`, t2.`date_delete`, m.`name`, m.`date_start`, m.`date_end`, m.`deleted` as mission_deleted, m.`active` as mission_active, u.`first_name`, u.`second_name`
                FROM `{$table_name1}` as t1, `{$table_name2}` as t2, `{$table_mission}` as m, `{$table_user}` as u
                WHERE t1.`id_soldier` = '{$id_soldier}'
                    AND t1.`deleted_pernament` = '0'
                    AND t1.`id_soldier2missions` = t2.`id_soldier2missions`
                    AND t1.`id_mission` = m.`id_mission`
                    AND u.`user_id` = t2.`id_user_add`
                    ORDER BY t2.`date_add` DESC
                    {$limit}
            ;";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // sprawdzanie czy misja jest powiazana z zolnierzem
        public static function sqlCheckSoldierToMission($id_soldier, $id_mission, $page_action_option_id){
            global $DB;
            
            $table_name1 = (self::$use_prefix ? self::$prefix : '').self::$table1;
            
            $zapytanie = "SELECT *
                FROM `{$table_name1}`
                WHERE `id_soldier` = '{$id_soldier}'
                    AND `id_mission` = '{$id_mission}'
                    AND `id_soldier2missions` = '{$page_action_option_id}'
                    AND `deleted_pernament` = '0'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
        
        // sprawdzanie czy misja istnieje w zolnierzu
        public static function sqlCheckMissionInSoldier($id_soldier2missions){
            global $DB;
            
            $table_name1 = (self::$use_prefix ? self::$prefix : '').self::$table1;
            
            $zapytanie = "SELECT *
                FROM `{$table_name1}`
                WHERE `id_soldier2missions` = '{$id_soldier2missions}'
                    AND `deleted_pernament` = '0'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
        
        // sprawdzanie czy misja istnieje w zolnierzu
        public static function sqlCheckMissionInSoldier2($id_soldier2missions){
            global $DB;
            
            $table_name1 = (self::$use_prefix ? self::$prefix : '').self::$table1;
            
            $zapytanie = "SELECT *
                FROM `{$table_name1}`
                WHERE `id_soldier2missions` = '{$id_soldier2missions}'
                    AND `deleted_pernament` = '0'
                    AND `deleted` = '0'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
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
            if(!ClassSoldier::isSoldier($ajax_get['id_soldier'])){
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
            $soldier_missions = self::sqlGetSoldier2Missions($ajax_get['id_soldier']);
            
            if(!$soldier_missions){
                foreach($sql_search as $mission){
                    $array['items'][] = array('id' => $mission['id_mission'], 'text' => $mission['name']);
                }
            }else{
                $i = 0;
                
                foreach($sql_search as $mission){
                    $array['items'][$i] = array('id' => $mission['id_mission'], 'text' => $mission['name']);
                    
                    if(isset($soldier_missions[$mission['id_mission']])){
                        $array['items'][$i]['disabled'] = true;
                    }
                    $i++;
                }
            }
            // $array['items'] = array(
                // array('id' => '1', 'text' => 'teeeest'),
                // array('id' => '2', 'text' => 'teeeest2'),
                // array('id' => '3', 'text' => 'teeeest3'),
                // array('id' => '4', 'text' => 'teeeest4', 'disabled' => true),
            // );
            
            return $array;
        }
    }
?>
