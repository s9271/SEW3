<?php
    class ClassCategoryModel extends ClassModel{
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // Rodzic podkategorii
        public $id_parent = NULL;
        
        // Nazwa
        public $name;
        
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
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class){
                // Nazwa statusu
                $this->active_name = ClassUser::getNameStatus($this->active);
            }
        }
        
        // sprawdzanie czy wartosc sklada sie tylko z liczb
        public static function validIsInt($value){
            // 23424
            if (preg_match('/^\d+$/', $value) || $value === NULL) {
                return true;
            }
            
            return false;
        }
        
        // pobieranie listy z dziecmi
        public static function getAllItemsNameWhithChild()
        {
            // pobieranie glownych kategorii
            if(!$parents = self::sqlGetAllItemsById(NULL, false, true)){
                return false;
            }
            
            $array = array();
            $id = static::$definition['primary'];
            
            foreach($parents as $parent){
                $array[$parent[$id]]['name'] = $parent['name'];
                
                if($childs = self::sqlGetAllItemsById($parent[$id], false, true))
                {
                    foreach($childs as $child){
                        $array[$parent[$id]]['childs'][$child[$id]] = $child['name'];
                    }
                }
            }
            
            return $array;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie liczby wszystkich rekordow
        public static function sqlGetCountItemsById($id_page, $controller_search = ''){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$definition['table'];
            $id = static::$definition['primary'];
            $where = '';
            
            if($id_page === NULL){
                $id_page = 'IS NULL';
            }else{
                $id_page = "= '{$id_page}'";
            }
            
            if(static::$has_deleted_column){
                $where = " AND `deleted` = '0'";
            }
            
            if(static::$is_search && $controller_search != '' && $where_search = self::generateWhereList($controller_search)){
                $where .= " AND ".$where_search;
            }
            
            $zapytanie = "SELECT COUNT(*) as count_items
                FROM `{$table_name}`
                WHERE `id_parent` {$id_page}
                    {$where}
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie, true);
            
            if(($sql === false || !is_array($sql)) && (static::$is_search && $controller_search != '' && isset($_SESSION['search'][$controller_search]))){
                if(static::$is_search && isset($_SESSION['search'][$controller_search])){
                    $_SESSION['search'][$controller_search] = array();
                }
            }
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql['count_items'];
        }
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItemsById($id_page, $without_id = false, $active = false, $using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$definition['table'];
            $id = static::$definition['primary'];
            
            $where = '';
            $limit = '';
            
            if($id_page === NULL){
                $id_page = 'IS NULL';
            }else{
                $id_page = "= '{$id_page}'";
            }
            
            if(static::$has_deleted_column){
                $where = " AND `deleted` = '0'";
            }
            
            if($active){
                $where .= " AND `active` = '1'";
            }
            
            if($without_id){
                $where .= " AND `{$id}` != '{$without_id}'";
            }
            
            if(static::$is_search && $controller_search != '' && $where_search = self::generateWhereList($controller_search)){
                $where .= " AND ".$where_search;
            }
            
            if($using_pages){
                $limit_start = ($current_page-1)*$items_on_page;
                $limit = " LIMIT {$limit_start}, {$items_on_page}";
            }
            
            $zapytanie = "SELECT *
                FROM `{$table_name}`
                WHERE `id_parent` {$id_page}
                    {$where}
                ORDER BY `{$id}`
                {$limit}
            ;";
            
            $sql = $DB->pdo_fetch_all($zapytanie, true);
            
            if(($sql === false || !is_array($sql)) && (static::$is_search && $controller_search != '' && isset($_SESSION['search'][$controller_search]))){
                if(static::$is_search && isset($_SESSION['search'][$controller_search])){
                    $_SESSION['search'][$controller_search] = array();
                }
            }
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            if(!$active){
                foreach($sql as $key => $val)
                {
                    // Nazwa statusu
                    $sql[$key]['active_name'] = ClassUser::getNameStatus($val['active']);
                }
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich nazw rekordow
        public static function sqlGetAllItemsNameById($id_page, $without_id = false, $active = false){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$definition['table'];
            $id = static::$definition['primary'];
            
            $where = '';
            
            if($id_page === NULL){
                $id_page = 'IS NULL';
            }else{
                $id_page = "= '{$id_page}'";
            }
            
            if(static::$has_deleted_column){
                $where = " AND `deleted` = '0'";
            }
            
            if($active){
                $where .= " AND `active` = '1'";
            }
            
            if($without_id){
                $where .= " AND `{$id}` != '{$without_id}'";
            }
            
            $zapytanie = "SELECT `{$id}`, `name`
                FROM `{$table_name}`
                WHERE `id_parent` {$id_page}
                    {$where}
                ORDER BY `{$id}`
            ;";
            
            $sql = $DB->pdo_fetch_all_group_column($zapytanie, true);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie nazwy glownej kategorii
        public static function sqlGetItemNameByIdParent($id_page){
            if(!is_numeric($id_page) || $id_page < 1){
                return '';
            }
            
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$definition['table'];
            $id = static::$definition['primary'];
            
            $where = '';
            $limit = '';
            
            if(static::$has_deleted_column){
                $where = " AND `deleted` = '0'";
            }
            
            $zapytanie = "SELECT `name`
                FROM `{$table_name}`
                WHERE `{$id}` = {$id_page}
                    {$where}
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie, true);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return '';
            }
            
            return $sql['name'];
        }
        
        // sprawdzanie czy parent istnieje
        public static function sqlCategoryItemExists($id_item, $parent = false){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$definition['table'];
            $id = static::$definition['primary'];
            
            $where = '';
            
            if($parent){
                $where = " AND `id_parent` IS NULL";
            }
            
            $zapytanie = "SELECT COUNT(*) as count_item
                FROM `{$table_name}`
                WHERE `deleted` = '0'
                    AND `{$id}` = '{$id_item}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1 || $sql['count_item'] < 1){
                return false;
            }
            
            return true;
        }
        
        // sprawdzanie czy parent ma dzieci
        public static function checkParentHasChilds($id_parent){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$definition['table'];
            $id = static::$definition['primary'];
            
            $zapytanie = "SELECT COUNT(*) as count_item
                FROM `{$table_name}`
                WHERE `deleted` = '0'
                    AND `id_parent` = '{$id_parent}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1 || $sql['count_item'] < 1){
                return false;
            }
            
            return true;
        }
    }
?>
