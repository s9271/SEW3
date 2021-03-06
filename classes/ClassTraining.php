<?php
    class ClassTraining extends ClassModel{
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        public static $is_search = true;
        
        // id
        public $id = false;
        
        // osrodek szkolenia
        public $id_training_centre;
        
        // Kod
        public $code = NULL;
        
        // Nazwa szkolenia
        public $name;
        
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
        
        // osrodek szkolenia nazwa
        public $training_center_name;
        
        // Data rozpoczecia nazwa
        public $date_start_name;
        
        // Data zakończenia nazwa
        public $date_end_name;
        
        // nazwa aktywacji
        public $active_name;
        
        // Status
        public $status;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'trainings',
            'primary' => 'id_training',
            'fields' => array(
                'id_training_centre'    => array('required' => true, 'validate' => array('isInt'), 'name' => 'Ośrodek szkolenia'),
                'code'                  => array('validate' => array('isInt'), 'name' => 'Kod'),
                'name'                  => array('required' => true, 'name' => 'Nazwa szkolenia'),
                'description'           => array('name' => 'Opis'),
                'id_user'               => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'           => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'date_start'            => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data rozpoczęcia'),
                'date_end'              => array('validate' => array('isDateTime'), 'name' => 'Data zakończenia'),
                'active'                => array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted'               => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class){
                $item = new ClassTrainingCenter($this->id_training_centre);
                
                if(!$item->load_class){
                    $this->training_center_name = 'Centrum szkolenia nie istnieje';
                }else{
                    $this->training_center_name = $item->name.', '.$item->location;
                }
                
                // data rozpoczecia misji
                $this->date_start_name = self::getPlDate($this->date_start);
                
                // nazwa lub data zakonczenia misji
                $this->date_end_name = self::getDateEndNameByDateEnd($this->date_end, true);
                
                // nazwa statusu
                // $this->status = self::getStatusName($this->date_end, $this->active);
                $this->status = self::getStatusTraining($this->date_start, $this->date_end);
                
                
                $this->active_name = ClassUser::getNameStatus($this->active);
            }
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
            
            // maksymalna dlugosc kodu
            if($this->code !== NULL && strlen($this->code) > 10){
                $this->errors = "<b>Kod szkolenia</b>: Kod szkolenia nie może posiadać więcej niż 10 znaków.";
                return false;
            }
            
            // ladowanie klasy
            $training_centre = new ClassTrainingCenter($this->id_training_centre);
            
            // sprawdza, czy centrum szkolenia istnieje
            if(!$training_centre->load_class){
                $this->errors[] = "Centrum szkolenia nie istnieje.";
                return false;
            }
            
            // sprawdza, czy centrum szkolenia jest aktywne
            if($training_centre->active != '1'){
                $this->errors[] = "Centrum szkolenia jest nieaktywne.";
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
            // sprawdzanie czy szkolenie jest powiazany z jakims zolnierzem
            if(self::sqlCheckSoldiersHasTrainingById($this->id)){
                $this->errors = "Do szkolenia przypisani są żołnierze.";
                return false;
            }
            
            return true;
        }
        
        // sprawdzanie czy wartosc sklada sie tylko z liczb
        public static function validIsInt($value){
            // 23424
            if (preg_match('/^\d+$/', $value) || $value === NULL) {
                return true;
            }
            
            return false;
        }
        
        // pobieranie nazwy statusu szkolenia
        public static function getStatusTraining($date_start, $date_end, $color = true){
            // print_r($date_start);
            // print_r($date_end);
            if(strtotime($date_start) < strtotime("now") && (($date_end === NULL || $date_end == '0000-00-00 00:00:00') || strtotime($date_end) > strtotime("now"))){
                return $color ? '<span class="sew_green">Aktywne</span>' : 'Aktywne';
            }
            
            if($date_end !== NULL && $date_end != '0000-00-00 00:00:00' && strtotime($date_end) < strtotime("now")){
                return $color ? '<span class="sew_purple">Zakończone</span>' : 'Zakończone';
            }
            
            if(strtotime($date_start) > strtotime("now")){
                return $color ? '<span class="sew_blue">Nierozpoczęte</span>' : 'Nierozpoczęte';
            }
            
            return false;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page, $controller_search))
            {
                // ladowanie centrow szkolen
                $training_centers = ClassTrainingCenter::sqlGetAllActiveItems(false);
                
                foreach($sql as $key => $val)
                {
                    // nazwa centrum szkolenia
                    $sql[$key]['training_center_name'] = $training_centers[$val['id_training_centre']]['name'].', '.$training_centers[$val['id_training_centre']]['location'];
                    
                    // nazwa lub data zakonczenia misji
                    $sql[$key]['date_end_name'] = self::getDateEndNameByDateEnd($val['date_end'], true);
                    
                    // data rozpoczecia misji
                    $sql[$key]['date_start_name'] = self::getPlDate($val['date_start']);
                    
                    // nazwa statusu
                    // $sql[$key]['status'] = self::getStatusName($val['date_end'], $val['active']);
                    $sql[$key]['status'] = self::getStatusTraining($val['date_start'], $val['date_end']);
                }
            }
            
            return $sql;
        }
        
        // sprawdzanie czy misja jest powiazana z jakims zolnierzem
        public static function sqlCheckSoldiersHasTrainingById($id_training){
            global $DB;
            
            $zapytanie = "SELECT COUNT(*) as count_soldiers
                FROM `sew_soldier2trainings`
                WHERE `deleted` = '0'
                    AND `deleted_pernament` = '0'
                    AND `id_training` = '{$id_training}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1 || $sql['count_soldiers'] < 1){
                return false;
            }
            
            return true;
        }
    }
?>