<?php
    class ClassSoldier2Badge extends ClassModel
    {
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // id zolnierza
        public $id_soldier;
        
        // Zolnierz - zmienna trzymajaca zolnierza poprawnego, potrzebne do sprawdzania podczas usuwania
        public $id_soldier_tmp;
        
        // id odznaczenia
        public $id_badge;
        
        // id odznaczenia - tymczasowy
        public $id_badge_tmp;
        
        // stopien odznaczenia
        public $badge_type;
        
        // data przyznania
        public $date_grant;
        
        // data odebrania
        public $date_receive = NULL;
        
        // opis
        public $description = '';
        
        // opic podczas odebrania
        public $description_receive = '';
        
        // Użytkownik
        public $id_user;
        
        // Data aktualizacji
        public $date_update;
        
        // odebrany
        public $received = '0';
        
        // Usunięty
        public $deleted = '0';
        
        // nazwa odznaczenia
        public $badge_name;
        
        // nazwa statusu
        public $status_name;
        
        // misja start
        public $date_start;
        
        // misja end
        public $date_end;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldier2badges',
            'primary' => 'id_soldier2badges',
            'fields' => array(
                'id_soldier'            => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołnierza'),
                'id_badge'              => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id odznaczenia'),
                'badge_type'            => array('validate' => array('isNameSpaces'), 'name' => 'Typ odznaczenia'),
                'date_grant'            => array('required' => true, 'validate' => array('isDate'), 'name' => 'Data przyznania'),
                'date_receive'            => array('required' => true, 'validate' => array('isDate'), 'name' => 'Data odebrania'),
                'description'           => array('name' => 'Opis'),
                'description_receive'   => array('name' => 'Opis odebrania'),
                'id_user'               => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'           => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'received'              => array('validate' => array('isBool'), 'name' => 'Odebrany'),
                'deleted'               => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class)
            {
                // przypisanie poprawnego zolnierza do tymczasowej zmiennej
                $this->id_soldier_tmp = $this->id_soldier;
                
                // przypisanie poprawnego szkolenia do tymczasowej zmiennej
                $this->id_badge_tmp = $this->id_badge;
                
                // nazwa odznaczenia
                $item = new ClassBadge($this->id_badge);
                $this->badge_name = $item->name;
                
                // data przyznania
                $this->date_grant = date('d.m.Y', strtotime($this->date_grant));
                $this->date_receive = $this->date_receive  === NULL ? $this->date_receive : date('d.m.Y', strtotime($this->date_receive));
                
                // nazwa statusu szkolenia
                $this->status_name = self::getStatusBadge($this->received);
            }
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            // sprawdzanie czy odznaczenie istnieje i jest aktywna
            $item = new ClassBadge($this->id_badge);
            
            if(!$item->load_class){
                $this->errors = "Odznaczenie nie istnieje.";
                return false;
            }
            
            if($item->active != '1'){
                $this->errors = "Odznaczenie jest wyłączone.";
                return false;
            }
            
            // sprawdzenie czy zolnierz posiada juz to odznaczenie
            if(self::sqlCheckSoldierHasBadge($this->id_badge)){
                $this->errors = "Żołnierz posiada już to odznaczenie.";
                return false;
            }
            
            $this->date_grant = date('Y-m-d', strtotime($this->date_grant));
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas aktualizowania
        public function updateCustomValidate()
        {
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            if($this->id_badge_tmp != $this->id_badge){
                $this->errors = "Niepoprawna odznaka.";
                return false;
            }
            
            $this->date_grant = date('Y-m-d', strtotime($this->date_grant));
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            global $login;
            
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            if($this->id_badge_tmp != $this->id_badge){
                $this->errors = "Niepoprawna odznaka.";
                return false;
            }
            
            if($this->received == '1' && $login->auth_user['id_permission'] != '1'){
                $this->errors = "Żołniez odebrano to odznaczenie.<br />Tylko <b>Administrator</b> ma możliwość usunięcia odebranych odznaczeń żołnierzy.";
                return false;
            }
            
            return true;
        }
        
        // pobieranie nazwy statusu odznaczenia
        public static function getStatusBadge($received, $color = true){
            if($received == '1'){
                return $color ? '<span class="sew_orange">Odebrany</span>' : 'Odebrany';
            }else{
                return $color ? '<span class="sew_green">Posiadane</span>' : 'Posiadane';
            }
            
            return false;
        }
        
        // odebranie odznaczenia
        public function receive($auto_date = true){
            if(!isset($this->id)){
                $this->errors[] = "Brak podanego id.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_update')) {
                $this->date_update = date('Y-m-d H:i:s');
            }
            
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Niepoprawny żołnierz.";
                return false;
            }
            
            if($this->id_badge_tmp != $this->id_badge){
                $this->errors = "Niepoprawne odznaczenie.";
                return false;
            }
            
            if($this->received == '1'){
                $this->errors = "Żołnierzowi już odebrano to odznaczenie.";
                return false;
            }
            
            // sprawdzenie czy data zwrotu nie jest mniejsza niz data przyznania
            if(strtotime($this->date_receive) <= strtotime($this->date_grant)){
                $this->errors = "Data odebrania jest mniejsza lub równa dacie przyznania.";
                return false;
            }
            
            $this->date_receive = date('Y-m-d H:i:s', strtotime($this->date_receive));
            
            
            if (!$this->sqlReceive(static::$definition['table'], $this->id)){
                $this->errors[] = "Odebranie odznaczenia: Błąd aktualizacji rekordu w bazie.";
                return false;
            }
            
            unset($this->id);
            if($this->load_class){
                $this->load_class = false;
            }
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // sprawdzanie czy zolnierz posiada odznaczenie
        public static function sqlCheckSoldierHasBadge($id_badge){
            global $DB;
            $table_name = (self::$use_prefix ? self::$prefix : '').'soldier2badges';
            
            $zapytanie = "SELECT *
                FROM `{$table_name}`
                WHERE `id_badge` = '{$id_badge}'
                    AND `received` = '0'
                    AND `deleted` = '0'
            ;";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
            
        }
        
        
        // pobieranie wszystkich rekordow z odznaczeniami
        public static function sqlGetSoldierBadges($id_soldier, $using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
            global $DB;
            $where = '';
            $limit = '';
            
            if(static::$is_search && $controller_search != '' && $where_search = self::generateWhereList($controller_search))
            {
                $where .= "AND ";
                $where .= $where_search;
            }
            
            if($using_pages){
                $limit_start = ($current_page-1)*$items_on_page;
                $limit = " LIMIT {$limit_start}, {$items_on_page}";
            }
            
            $zapytanie = "SELECT sb.`id_soldier2badges`, sb.`id_soldier`, sb.`id_badge`, sb.`badge_type`, sb.`date_grant`, sb.`description`, sb.`description_receive`, sb.`received`, b.`name` as badge_name, br.`name` as badge_rank_name
                FROM `sew_soldier2badges` as sb, `sew_badges` as b, `sew_badge_ranks` as br
                WHERE sb.`deleted` = '0'
                    AND b.`deleted` = '0'
                    AND sb.`id_badge` = b.`id_badge`
                    AND b.`id_badge_rank` = br.`id_badge_rank`
                    AND sb.`id_soldier` = '{$id_soldier}'
                    {$where}
                ORDER BY `".static::$definition['primary']."`
                {$limit}
            ;";
            
            // print_r($zapytanie);
            $sql = $DB->pdo_fetch_all($zapytanie, true);
            // print_r($sql);
            
            if(($sql === false || !is_array($sql)) && (static::$is_search && $controller_search != '' && isset($_SESSION['search'][$controller_search]))){
                if(static::$is_search && isset($_SESSION['search'][$controller_search])){
                    $_SESSION['search'][$controller_search] = array();
                }
            }
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            
            foreach($sql as $key => $val)
            {
                // nazwa statusu szkolenia
                $sql[$key]['status_name'] = self::getStatusBadge($val['received']);
                
                // data przyznania
                $sql[$key]['date_grant'] = date('d.m.Y', strtotime($sql[$key]['date_grant']));
            }
            
            return $sql;
        }
        
        // odebranie odznaczenia
        protected function sqlReceive($table, $id_item){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').$table;
            $where = static::$definition['primary'].' = '.$id_item;
            
            if(static::$is_log){
                $table_name_log = static::$prefix_log.$table;
                
                if(!$item_to_log = self::sqlGetItem($id_item)){
                    $this->errors[] = "LOG: Błąd podczas pobierania rekordu z bazy.";
                    return false;
                }
                
                if(!$DB->insert($table_name_log, $item_to_log)){
                    $this->errors[] = "LOG: Błąd podczas zapisywania rekordu w tabeli z logami.";
                    return false;
                }
            }
            
            $data = array(
                'description_receive'   => $this->description_receive,
                'id_user'               => $this->id_user,
                'date_receive'          => $this->date_receive,
                'received'              => '1',
            );
            
            if (property_exists($this, 'date_update')) {
                $data['date_update'] = $this->date_update;
            }
            
            return $DB->update($table_name, $data, $where);
        }
    }
?>
