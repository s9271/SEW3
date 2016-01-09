<?php
    class ClassSoldierLanguage extends ClassModel
    {
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // id zolnierza
        public $id_soldier;
        
        // nazwa
        public $name;
        
        // id stopnia zaawansowania jezyka
        public $id_language_level;
        
        // Użytkownik
        public $id_user;
        
        // Użytkownik - zmienna trzymajaca zolnierza poprawnego, potrzebne do sprawdzania podczas usuwania
        public $id_user_tmp;
        
        // Data aktualizacji
        public $date_update;
        
        // Usunięty
        public $deleted = '0';
        
        // nazwa stopnia zaawansowania jezyka
        public $language_level_name;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldier_languages',
            'primary' => 'id_language',
            'fields' => array(
                'id_soldier'        => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołnierza'),
                'name'              => array('required' => true, 'validate' => array('isName'), 'name' => 'Nazwa'),
                'id_language_level' => array('required' => true, 'validate' => array('isInt'), 'name' => 'Stopień zaawansowania'),
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
                
                // nazwa stopnia zaawansowania jezyka
                $this->language_level_name = ClassLanguageLevel::sqlGetItemNameByIdParent($this->id_language_level);
            }
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            // sprawdzanie czy stopien zaawansowania istnieje i jest aktywny
            $item = new ClassLanguageLevel($this->id_language_level);
            
            if(!$item->load_class){
                $this->errors = "Stopień zaawansowania nie istnieje.";
                return false;
            }
            
            if($item->active != '1'){
                $this->errors = "Stopień zaawansowania jest wyłączony.";
                return false;
            }
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy id_zolnierza do ktorego jest przypisane dziecko zgadza sie z tym do usuwania
            if($this->id_user_tmp != $this->id_user){
                $this->errors = "Żołnierz nie posiada tego języka.";
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
                    // nazwa stopnia zaawansowania jezyka
                    $sql[$key]['language_level_name'] = ClassLanguageLevel::sqlGetItemNameByIdParent($val['id_language_level']);
                }
            }
            
            return $sql;
        }
    }
?>
