<?php
    class ClassSoldierSchool extends ClassModel
    {
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // id zolnierza
        public $id_soldier;
        
        // nazwa
        public $name = '';
        
        // adres
        public $address = '';
        
        // specjalizacja
        public $specialization = '';
        
        // id tytulu zawodowego
        public $id_academic_degree;
        
        // data waznosci od
        public $date_start;
        
        // data waznosci do
        public $date_end;
        
        // Użytkownik
        public $id_user;
        
        // Użytkownik - zmienna trzymajaca zolnierza poprawnego, potrzebne do sprawdzania podczas usuwania
        public $id_user_tmp;
        
        // Data aktualizacji
        public $date_update;
        
        // Usunięty
        public $deleted = '0';
        
        // nazwa tytulu zawodowego
        public $academic_degree_name;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldier_schools',
            'primary' => 'id_school',
            'fields' => array(
                'id_soldier'            => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołnierza'),
                'name'                  => array('required' => true, 'name' => 'Nazwa szkoły'),
                'address'               => array('required' => true, 'name' => 'Adres szkoły'),
                'specialization'        => array('required' => true, 'validate' => array('isNameSpaces'), 'name' => 'Specjalizacja'),
                'id_academic_degree'    => array('required' => true, 'validate' => array('isInt'), 'name' => 'Tytuł zawodowy'),
                'date_start'            => array('required' => true, 'validate' => array('isDate'), 'name' => 'Data rozpoczęcia'),
                'date_end'              => array('required' => true, 'validate' => array('isDate'), 'name' => 'Data zakończenia'),
                'id_user'               => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'           => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'deleted'               => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class)
            {
                // przypisanie poprawnego zolnierza do tymczasowej zmiennej
                $this->id_user_tmp = $this->id_user;
                
                // nazwa tytulu zawodowego
                $this->academic_degree_name = ClassAcademicDegree::sqlGetItemNameByIdParent($this->id_academic_degree);
                
                // Zmiana daty na polski format
                $this->date_start = date('d.m.Y', strtotime($this->date_start));
                $this->date_end = date('d.m.Y', strtotime($this->date_end));
            }
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            // sprawdzanie czy data rozpoczecia waznosci prawa jazdy jest wieksza niz dzisiejsza data
            if(strtotime($this->date_start) > strtotime(date('Y-m-d'))){
                $this->errors = "Data rozpoczecia jest wieksza niż aktualna data.";
                return false;
            }
            
            // sprawdzanie czy data zakonczenia jest mniejsza od daty rozpoczecia
            if(strtotime($this->date_start) > strtotime($this->date_end)){
                $this->errors = "Data rozpoczecia jest wieksza niż data zakończenia.";
                return false;
            }
            
            // konwersja danty na cele zapisu w bazie
            $this->date_start = date('Y-m-d', strtotime($this->date_start));
            $this->date_end = date('Y-m-d', strtotime($this->date_end));
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas aktualizowania
        public function updateCustomValidate(){
            if(!$this->addCustomValidate()){
                return false;
            }
            
            // sprawdzanie czy id_zolnierza do ktorego jest przypisana szkola zgadza sie z tym do edycji
            if($this->id_user_tmp != $this->id_user){
                $this->errors = "Żołnierz nie posiada tej szkoły.";
                return false;
            }
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy id_zolnierza do ktorego jest przypisane szkola zgadza sie z tym do usuwania
            if($this->id_user_tmp != $this->id_user){
                $this->errors = "Żołnierz nie posiada tej szkoły.";
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
                    // nazwa tytulu zawodowego
                    $sql[$key]['academic_degree_name'] = ClassAcademicDegree::sqlGetItemNameByIdParent($val['id_academic_degree']);
                    
                    // Zmiana daty na polski format
                    $sql[$key]['date_start'] = date('d.m.Y', strtotime($val['date_start']));
                    $sql[$key]['date_end'] = date('d.m.Y', strtotime($val['date_end']));
                }
            }
            
            return $sql;
        }
    }
?>
