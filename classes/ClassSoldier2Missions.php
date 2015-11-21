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
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie misji z ktorymi zolnierz juz jest powiazany
        public static function sqlGetSoldier2Missions($id_soldier){
            global $DB;
            $zapytanie = "SELECT `id_mission`, `id_soldier2missions`, `id_soldier` FROM `sew_soldier2missions` WHERE `id_soldier` = '{$id_soldier}' AND `deleted` = '0'";
            $sql = $DB->pdo_fetch_all_group($zapytanie);
            
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
            $sql_search = $DB->search($table_mission, array('name' => "%{$ajax_get['search']}%"), '`id_mission`, `name`', "`deleted` = '0'");
            
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
