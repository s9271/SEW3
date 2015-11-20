<?php
    class ClassSoldier extends ClassModel{
        // id zolnierza
        public $id = false;
        
        // imie
        public $soldierName;
        
        // nazwisko
        public $soldierSurname;
        
        // urodziny
        public $birthday;
        
        // plec
        public $sex;
        
        // telefon
        public $phone;
        
        // mail
        public $email;
        
        // kod pocztowy
        public $code;
        
        // miasto
        public $city;
        
        // data dodania
        public $date_add;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldiers',
            'primary' => 'id',
            'fields' => array(
                'soldierName' =>      array('required' => true, 'validate' => array('isName'), 'name' => 'Imię'),
                'soldierSurname' =>   array('required' => true, 'validate' => array('isName'), 'name' => 'Nazwisko'),
                'birthday' =>         array('required' => true, 'validate' => array('isDate'), 'name' => 'Urodziny'),
                'sex' =>              array('required' => true, 'validate' => array('isInt'), 'name' => 'Płeć'),
                'phone' =>            array('required' => true, 'validate' => array('isPhone'), 'name' => 'Telefon'),
                'email' =>            array('required' => true, 'validate' => array('isEmail'), 'name' => 'E-mail'),
                'code' =>             array('required' => true, 'name' => 'Kod pocztowy'),
                'city' =>             array('required' => true, 'validate' => array('isName'), 'name' => 'Miasto'),
                'date_add' =>         array('required' => true, 'validate' => array('isDate'), 'name' => 'Data dodania'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            $this->getSoldier($this->id);
            return;
        }
        
        // pobieranie zolnierza
        public function getSoldier($id_soldier){
            if(!$soldier = $this->sqlGetSoldier($id_soldier)){
                $this->errors[] = "Brak żołnierza w bazie.";
                return false;
            }
            
            $this->soldierName = $soldier['soldierName'];
            $this->soldierSurname = $soldier['soldierSurname'];
            $this->birthday = $soldier['birthday'];
            $this->sex = $soldier['sex'];
            $this->phone = $soldier['phone'];
            $this->email = $soldier['email'];
            $this->code = $soldier['code'];
            $this->city = $soldier['city'];
            
            $this->load_class = true;
            return true;
        }
        
        // sprawdzanie czy zolnierz istnieje
        public static function isSoldier($id_soldier){
            return self::sqlSoldierExist($id_soldier);
        }
        
        // sprawdzanie czy zolnierz posiada dana misje
        public static function soldierHasMission($id_soldier, $id_mission){
            return self::sqlSoldierHasMission($id_soldier, $id_mission);
        }
        
        // sprawdzenie czy misja nie koliduje ze szkoleniem
        public static function checkSoldierMissionConflictWithTraining($id_soldier, $id_mission){
            // pobieranie szkolen zolnierza
            if(!self::sqlGetSoldier2Trainings($id_soldier)){
                return false;
            }
            
            
            // return self::sqlGetSoldier2Trainings($id_soldier);
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        protected function sqlGetSoldier($id_soldier){
            global $DB;
            $zapytanie = "SELECT * FROM soldiers WHERE id = {$id_soldier}";
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie zolnierzy
        public static function sqlGetAllSoldiers(){
            global $DB;
            $zapytanie = "SELECT * FROM soldiers";
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // sprawdzanie czy zolnierz istnieje
        public static function sqlSoldierExist($id_soldier){
            global $DB;
            $zapytanie = "SELECT id FROM soldiers WHERE `id` = {$id_soldier}";
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
        
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
            if(!self::isSoldier($ajax_get['id_soldier'])){
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
