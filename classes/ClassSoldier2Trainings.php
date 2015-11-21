<?php
    class ClassSoldier2Trainings extends ClassSoldierModel{
        
        // nazwa tabeli 1
        protected static $table1 = 'soldier2trainings';
        
        // nazwa tabeli 2
        protected static $table2 = 'soldier2trainings_options';
        
        // primary id
        protected static $primary = 'id_soldier2trainings';
        
        // primary id powiazany z id tabeli 1
        protected static $foreign_key = 'id_soldier2trainings';
        
        /* ************** FUNKCJE ************* */
        /* ************************************ */
        
        // sprawdzanie czy zolnierz posiada dana misje
        public static function soldierHasMission($id_soldier, $id_training){
            return self::sqlSoldierHasTraining($id_soldier, $id_training);
        }
        
        // sprawdzenie czy misja nie koliduje ze szkoleniem
        public static function checkSoldierTrainingConflictWithMission($id_soldier, $id_training){
            // pobieranie szkolen zolnierza
            if(!$missions = ClassSoldier2Missions::sqlGetSoldier2Missions($id_soldier)){
                return false;
            }
            
            
            // return self::sqlGetSoldier2Trainings($id_soldier);
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie szkolen z ktorymi zolnierz juz jest powiazany
        public static function sqlGetSoldier2Trainings($id_soldier){
            global $DB;
            $zapytanie = "SELECT `id_training`, `id_soldier2trainings`, `id_soldier` FROM `sew_soldier2trainings` WHERE `id_soldier` = '{$id_soldier}' AND `deleted` = '0'";
            $sql = $DB->pdo_fetch_all_group($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // sprawdzanie czy zolnierz posiada dana misje
        public static function sqlSoldierHasTraining($id_soldier, $id_training){
            global $DB;
            $zapytanie = "SELECT `id_soldier`
                FROM `sew_soldier2trainings`
                WHERE `id_soldier` = '{$id_soldier}'
                    AND `id_training` = '{$id_training}'
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
