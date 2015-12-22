<?php
    // http://medalewojskowe.pl/odznaczenia-wojskowe/

    class ClassBadge extends ClassModel{
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // Ilosc stopni
        public $id_badge_rank;
        
        // Nazwa jednostki
        public $name;
        
        // Użytkownik
        public $id_user;
        
        // Data aktualizacji
        public $date_update;
        
        // Aktywny
        public $active;
        
        // Usunięty
        public $deleted = '0';
        
        // Ilosc stopni nazwa
        public $badge_rank_name;
        
        // Nazwa statusu
        public $active_name;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'badges',
            'primary' => 'id_badge',
            'fields' => array(
                'id_badge_rank' =>        array('required' => true, 'validate' => array('isInt'), 'name' => 'Ilość stopni'),
                'name' =>                 array('required' => true, 'name' => 'Nazwa odznaczenia'),
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
                // Ilosc stopni nazwa
                $this->badge_rank_name = self::sqlGetRankNameById($this->id_badge_rank);
                
                // Nazwa statusu
                $this->active_name = ClassUser::getNameStatus($this->active);
            }
        }
        
        // pobieranie stopni
        public static function getRanks(){
            return self::sqlGetRanks();
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie wszystkich stopni
        public static function sqlGetRanks(){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'badge_ranks';
            
            $zapytanie = "SELECT * FROM {$table_name}";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie nazw ilosci stopni
        public static function sqlGetRankNameById($id_badge_rank){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'badge_ranks';
            
            $zapytanie = "SELECT `name`, `name_select` FROM {$table_name} WHERE `id_badge_rank` = '{$id_badge_rank}'";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5'){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page))
            {
                foreach($sql as $key => $val)
                {
                    // Rodzaj jednostki nazwa
                    $sql[$key]['badge_rank_name'] = self::sqlGetRankNameById($val['id_badge_rank']);
                    
                    // Nazwa statusu
                    $sql[$key]['active_name'] = ClassUser::getNameStatus($val['active']);
                }
            }
            
            return $sql;
        }
    }
?>
