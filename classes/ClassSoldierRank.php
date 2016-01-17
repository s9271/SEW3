<?php
    class ClassSoldierRank extends ClassModel
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
        
        // data nadania
        public $date_add_rank;
        
        // Użytkownik
        public $id_user;
        
        // Użytkownik - zmienna trzymajaca zolnierza poprawnego, potrzebne do sprawdzania podczas usuwania
        public $id_soldier_tmp;
        
        // Data aktualizacji
        public $date_update;
        
        // Usunięty
        public $deleted = '0';
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldier_ranks',
            'primary' => 'id_rank',
            'fields' => array(
                'id_soldier'        => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołnierza'),
                'name'              => array('required' => true, 'name' => 'Nazwa'),
                'date_add_rank'     => array('required' => true, 'validate' => array('isDate'), 'name' => 'Data nadania'),
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
                $this->id_soldier_tmp = $this->id_soldier;
                
                // Zmiana daty na polski format
                $this->date_add_rank = date('d.m.Y', strtotime($this->date_add_rank));
            }
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            // konwersja danty na cele zapisu w bazie
            $this->date_add_rank = date('Y-m-d', strtotime($this->date_add_rank));
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy id_zolnierza do ktorego jest przypisane prawo jazdy zgadza sie z tym do usuwania
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Żołnierz nie posiada tego stopnia wojskowego.";
                return false;
            }
            
            return true;
        }
        
        public static function getActuallyRank($id_soldier)
        {
            if(!$sql = self::sqlGetActuallyRank($id_soldier)){
                return false;
            }
            
            $sql['date_add_rank'] = date('d.m.Y', strtotime($sql['date_add_rank']));
            
            return $sql;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = '', array $custom_where = array()){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page, $controller_search, $custom_where))
            {
                foreach($sql as $key => $val)
                {
                    // Zmiana daty na polski format
                    $sql[$key]['date_add_rank'] = date('d.m.Y', strtotime($val['date_add_rank']));
                }
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich rekordow
        public static function sqlGetActuallyRank($id_soldier)
        {
            global $DB;
            
            $zapytanie = "SELECT *
                FROM `sew_soldier_ranks`
                WHERE `deleted` = '0'
                    AND `id_soldier` = '{$id_soldier}'
                    AND DATE(`date_add_rank`) < DATE(CURDATE())
                ORDER BY `date_add_rank` DESC
                LIMIT 1
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
    }
?>
