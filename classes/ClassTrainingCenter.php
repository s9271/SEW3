<?php
    // http://www.dnisw.mon.gov.pl/pl/92.html

    class ClassTrainingCenter extends ClassModel{
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        public static $is_search = true;
        
        // id
        public $id = false;
        
        // Nazwa
        public $name;
        
        // Lokalizacja
        public $location;
        
        // Użytkownik
        public $id_user;
        
        // Data aktualizacji
        public $date_update;
        
        // Aktywny
        public $active;
        
        // Usunięty
        public $deleted = '0';
        
        // Nazwa statusu
        public $active_name;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'training_centres',
            'primary' => 'id_training_centre',
            'fields' => array(
                'name' =>                 array('required' => true, 'name' => 'Nazwa odznaczenia'),
                'location' =>             array('required' => true, 'name' => 'Lokalizacja'),
                'id_user' =>              array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update' =>          array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'active' =>               array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted' =>              array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class)
            {
                // Nazwa statusu
                $this->active_name = ClassUser::getNameStatus($this->active);
            }
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy centrum szkolenia jest juz powiazane z jakims szkoleniem
            if(self::sqlCheckTrainingsHasCenterById($this->id)){
                $this->errors = "Do Centrum szkolenia są przypisane szkolenia.";
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
                    // Nazwa statusu
                    $sql[$key]['active_name'] = ClassUser::getNameStatus($val['active']);
                }
            }
            
            return $sql;
        }
        
        // sprawdzanie czy centrum szkolenia jest przypisane do szkolenia
        public static function sqlCheckTrainingsHasCenterById($id_training_centre){
            global $DB;
            
            $zapytanie = "SELECT COUNT(*) as count_trainings
                FROM `sew_trainings`
                WHERE `deleted` = '0'
                    AND `id_training_centre` = '{$id_training_centre}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1 || $sql['count_trainings'] < 1){
                return false;
            }
            
            return true;
        }
    }
?>
