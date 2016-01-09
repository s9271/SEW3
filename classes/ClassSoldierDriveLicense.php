<?php
    class ClassSoldierDriveLicense extends ClassModel
    {
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // id zolnierza
        public $id_soldier;
        
        // id kategorii prawa jazdy
        public $id_drive_category;
        
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
        
        // nazwa kategorii prawa jazdy
        public $drive_category_name;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldier_driver_licenses',
            'primary' => 'id_driver_license',
            'fields' => array(
                'id_soldier'        => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołnierza'),
                'id_drive_category' => array('required' => true, 'validate' => array('isInt'), 'name' => 'Kategoria prawa jazdy'),
                'date_start'        => array('required' => true, 'validate' => array('isDate'), 'name' => 'Data ważności od'),
                'date_end'          => array('required' => true, 'validate' => array('isDate'), 'name' => 'Data ważności do'),
                'id_user'           => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'       => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'deleted'           => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class)
            {
                // przypisanie poprawnego zolnierza do tymczasowej zmiennej
                $this->id_user_tmp = $this->id_user;
                
                // nazwa kategorii prawa jazdy
                $this->drive_category_name = ClassDriveCategories::sqlGetItemNameByIdParent($this->id_drive_category);
                
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
                $this->errors[] = "Data rozpoczecia ważności jest wieksza niż aktualna data.";
                return false;
            }
            
            // sprawdzanie czy data zakonczenia jest mniejsza od daty rozpoczecia
            if(strtotime($this->date_start) > strtotime($this->date_end)){
                $this->errors[] = "Data rozpoczecia ważności jest wieksza niż data zakończenia.";
                return false;
            }
            
            // sprawdzanie czy kategoria jazdy istnieje i jest aktywna
            $item = new ClassDriveCategories($this->id_drive_category);
            
            if(!$item->load_class){
                $this->errors = "Kategoria prawa jazdy nie istnieje.";
                return false;
            }
            
            if($item->active != '1'){
                $this->errors = "Kategoria prawa jazdy jest wyłączona.";
                return false;
            }
            
            // konwersja danty na cele zapisu w bazie
            $this->date_start = date('Y-m-d', strtotime($this->date_start));
            $this->date_end = date('Y-m-d', strtotime($this->date_end));
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy id_zolnierza do ktorego jest przypisane prawo jazdy zgadza sie z tym do usuwania
            if($this->id_user_tmp != $this->id_user){
                $this->errors = "Żołnierz nie posiada tego prawa jazdy.";
                return false;
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
                    // nazwa kategorii prawa jazdy
                    $sql[$key]['drive_category_name'] = ClassDriveCategories::sqlGetItemNameByIdParent($val['id_drive_category']);
                    
                    // Zmiana daty na polski format
                    $sql[$key]['date_start'] = date('d.m.Y', strtotime($val['date_start']));
                    $sql[$key]['date_end'] = date('d.m.Y', strtotime($val['date_end']));
                }
            }
            
            return $sql;
        }
    }
?>
