<?php
    class ClassSoldierChildren extends ClassModel
    {
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // id zolnierza
        public $id_soldier;
        
        // imie
        public $name;
        
        // nazwisko
        public $surname;
        
        // Data urodzenia
        public $date_birthday;
        
        // Użytkownik
        public $id_user;
        
        // Użytkownik - zmienna trzymajaca zolnierza poprawnego, potrzebne do sprawdzania podczas usuwania
        public $id_user_tmp;
        
        // Data aktualizacji
        public $date_update;
        
        // Usunięty
        public $deleted = '0';
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldier_childrens',
            'primary' => 'id_children',
            'fields' => array(
                'id_soldier'        => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołnierza'),
                'name'              => array('required' => true, 'validate' => array('isName'), 'name' => 'Imię'),
                'surname'           => array('required' => true, 'validate' => array('isName'), 'name' => 'Nazwisko'),
                'date_birthday'     => array('required' => true, 'validate' => array('isDate'), 'name' => 'Data urodzenia'),
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
                // Zmiana daty na polski format
                $this->date_birthday = date('d.m.Y', strtotime($this->date_birthday));
                
                // przypisanie poprawnego zolnierza do tymczasowej zmiennej
                $this->id_user_tmp = $this->id_user;
            }
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            // sprawdzenie czy data narodzin dziecka jest wyzdza niz data dzisiejsza
            if(strtotime($this->date_birthday) > strtotime(date('Y-m-d'))){
                $this->errors[] = "Data urodzenia jest wieksza niż aktualna data.";
                return false;
            }
            
            // konwersja danty na cele zapisu w bazie
            $this->date_birthday = date('Y-m-d', strtotime($this->date_birthday));
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy id_zolnierza do ktorego jest przypisane dziecko zgadza sie z tym do usuwania
            if($this->id_user_tmp != $this->id_user){
                $this->errors = "Żołnierz nie posiada tego dziecka.";
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
                    // Zmiana daty na polski format
                    $sql[$key]['date_birthday'] = date('d.m.Y', strtotime($val['date_birthday']));
                }
            }
            
            return $sql;
        }
    }
?>
