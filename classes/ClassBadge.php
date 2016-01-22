<?php
    // http://medalewojskowe.pl/odznaczenia-wojskowe/

    class ClassBadge extends ClassModel{
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        public static $is_search = true;
        
        // id
        public $id = false;
        
        // Ilosc stopni
        public $id_badge_type;
        
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
                'id_badge_type' =>        array('required' => true, 'validate' => array('isInt'), 'name' => 'Rodzaj odznaczenia'),
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
                $this->badge_rank_name = ClassBadgeType::sqlGetItemNameByIdParent($this->id_badge_type);
                
                // Nazwa statusu
                $this->active_name = ClassUser::getNameStatus($this->active);
            }
        }
        
        // pobieranie stopni
        public static function getRanks(){
            return self::sqlGetRanks();
        }
        
        // pobieranie odznaczen wraz ze stopniami
        public static function sqlGetBadgesWithRanks()
        {
            if(!$ranks = self::sqlGetRanks()){
                return false;
            }
            
            $array = array();
            
            foreach($ranks as $rank){
                $array[$rank['id_badge_rank']]['name'] = $rank['name'];
                
                if($types = self::sqlGetBadgesByRankId($rank['id_badge_rank']))
                {
                    foreach($types as $type){
                        $array[$rank['id_badge_rank']]['childs'][$type['id_badge']] = $type['name'];
                    }
                }
            }
            
            return $array;
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            // sprawdzanie czy item istnieje i jest aktywna
            $item = new ClassBadgeType($this->id_badge_type);
            
            if(!$item->load_class){
                $this->errors = "Rodzaj odznaczenia nie istnieje.";
                return false;
            }
            
            if($item->active != '1'){
                $this->errors = "Rodzaj odznaczenia jest wyłączony.";
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
            // sprawdzanie czy item jest powiazany z jakims zolnierzem
            if(self::checkSoldierHasItem($this->id)){
                $this->errors = "Do tego odznaczenia powiązani są żołnierze.";
                return false;
            }
            
            return true;
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
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page, $controller_search))
            {
                foreach($sql as $key => $val)
                {
                    // Rodzaj jednostki nazwa
                    $sql[$key]['badge_rank_name'] = ClassBadgeType::sqlGetItemNameByIdParent($val['id_badge_type']);
                    
                    // Nazwa statusu
                    $sql[$key]['active_name'] = ClassUser::getNameStatus($val['active']);
                }
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich odznak danej grupy
        public static function sqlGetBadgesByRankId($id_badge_rank){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'badges';
            
            $zapytanie = "SELECT *
                FROM `{$table_name}`
                WHERE `id_badge_rank` = '{$id_badge_rank}'
                    AND `active` = '1'
                    AND `deleted` = '0'
            ;";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // sprawdzanie czy item jest powiazany z jakims zolnierzem
        public static function checkSoldierHasItem($id_badge){
            global $DB;
            
            $zapytanie = "SELECT COUNT(*) as count_item
                FROM `sew_soldier2badges`
                WHERE `deleted` = '0'
                    AND `id_badge` = '{$id_badge}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1 || $sql['count_item'] < 1){
                return false;
            }
            
            return true;
        }
    }
?>
