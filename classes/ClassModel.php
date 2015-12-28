<?php
    class ClassModel extends ClassSearch{
        // prefix uzywany do nazw tabel w bazach
        public static $prefix = 'sew_';
        public static $prefix_log = 'log_';
        public static $use_prefix = false;
        
        // przypisuje sie na tru, gdy modul ma tabele z logami
        protected static $is_log = false;
        
        // podczas pobierania rekordow, wywoluje sprawdzenie, ktore sa usuniete
        protected static $has_deleted_column = false;
        
        // bledy
        public $errors = array();
        
        // id
        public $id = false;
        
        // pokazuje, czy dany id zostal poprawnie zaladowany
        public $load_class = false;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array();
        
        public function __construct($id = false){
            if ($id){
                $this->id = (int)$id;
                $this->load();
            }
        }
        
        // pobieranie danych gdy jest podane id
        // public function load(){
            // return;
        // }
        
        // pobieranie danych gdy jest podane id
        public function load(){
            if($values = $this->getItem($this->id)){
                $this->setProperties($values);
            }
            return;
        }
        
        // pobranie danych
        protected function getFieldsValidate(){
            $values = array();
            
            foreach(static::$definition['fields'] as $key => $valid){
                if (!property_exists($this, $key)){
                    $this->errors[] = "<b>{$valid['name']}</b>: Brak wlaściwości w klasie.";
                    continue;
                }
                
                $values[$key] = $this->$key !== null ? trim($this->$key) : $this->$key;
                
                if((isset($valid['required']) && $valid['required']) && $values[$key] == '' && $values[$key] !== null){
                    $this->errors[] = "<b>{$valid['name']}</b>: Proszę uzupełnić pole.";
                    continue;
                }
                // print_r($values);
                
                if(isset($valid['validate']) && is_array($valid['validate']) && count($valid['validate']) > 0){
                    foreach($valid['validate'] as $valid_key){
                        $values[$key] = $this->validByMethod($valid_key, $values[$key], $valid['name'], $key);
                    }
                }
            }
            
            return $values;
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        // zapis
        public function save($auto_date = true){
            return ($this->id && (int)$this->id > 0) ? $this->update($auto_date) : $this->add($auto_date);
        }
        
        // dodawanie
        public function add($auto_date = true){
            if (isset($this->id)) {
                unset($this->id);
            }
            
            // automatyczne przypisywanie dat
            if ($auto_date && property_exists($this, 'date_add')) {
                $this->date_add = date('Y-m-d H:i:s');
            }
            
            if ($auto_date && property_exists($this, 'date_update')) {
                $this->date_update = date('Y-m-d H:i:s');
            }
            
            $values = $this->getFieldsValidate();
            
            if($this->errors && count($this->errors) > 0){
                return false;
            }
            
            // dodatkowe wlasne walidacje
            if(!$this->addCustomValidate()){
                return false;
            }
            
            if (!$id = $this->sqlAdd(static::$definition['table'], $values)){
                $this->errors[] = "Błąd zapisu do bazy.";
                return false;
            }
            
            $this->id = $id;
            return true;
        }
        
        // aktualizacja
        public function update($auto_date = true){
            if(!isset($this->id)){
                $this->errors = "Brak podanego id.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_update')) {
                $this->date_update = date('Y-m-d H:i:s');
            }
            
            $values = $this->getFieldsValidate();
            
            if($this->errors && count($this->errors) > 0){
                return false;
            }
            
            // dodatkowe wlasne walidacje podczas aktualizowania
            if(!$this->updateCustomValidate()){
                return false;
            }
            
            if (!$this->sqlUpdate(static::$definition['table'], $values, static::$definition['primary'].' = '.$this->id)){
                $this->errors[] = "Błąd aktualizacji rekordu w bazie.";
                return false;
            }
            
            return true;
        }
        
        // usuwanie
        public function delete($auto_date = true){
            if(!isset($this->id)){
                $this->errors[] = "Brak podanego id.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_update')) {
                $this->date_update = date('Y-m-d H:i:s');
            }
            
            if(static::$has_deleted_column){
                $this->sqlDeleteOnColumn(static::$definition['table'], $this->id);
            }
            
            // if ($this->sqlDelete(static::$definition['table'], static::$definition['primary'].' = '.$this->id)){
                // $this->errors[] = "Błąd usuwania rekordu z bazy.";
                // return false;
            // }
            
            unset($this->id);
            if($this->load_class){
                $this->load_class = false;
            }
            return true;
        }
        
        // pobieranie nazwy daty po dacie zakonczenia
        public static function getDateEndNameByDateEnd($date_end){
            if($date_end === NULL || $date_end == '0000-00-00 00:00:00'){
                return 'Niezdefiniowano';
            }
            
            return $date_end;
        }
        
        // pobieranie nazwy daty po dacie zakonczenia
        public static function getStatusName($date_end, $active, $deleted = false, $color = true){
            switch(self::getStatus($date_end, $active, $deleted, $color)){
                case '0':
                    return $color ? '<span class="sew_red">Usunięta</span>' : 'Usunięta';
                break;
                case '1':
                    return $color ? '<span class="sew_green">Aktywna</span>' : 'Aktywna';
                break;
                case '2':
                    return $color ? '<span class="sew_orange">Nieaktywna</span>' : 'Nieaktywna';
                break;
                case '3':
                    return $color ? '<span class="sew_purple">Zakończona</span>' : 'Zakończona';
                break;
            }
        }
        
        // pobieranie nazwy daty po dacie zakonczenia
        public static function getStatus($date_end, $active, $deleted = false, $color = true){
            if($deleted && $deleted == '1'){
                return '0';
            }
            
            if(($date_end !== NULL && $date_end != '0000-00-00 00:00:00') && (strtotime($date_end) < strtotime("now"))){
                return '3';
            }
            
            if($active == '1'){
                return '1';
            }
            
            return '2';
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate(){
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas aktualizowania
        public function updateCustomValidate(){
            return true;
        }
        
        /* ************* WALIDACJA ************ */
        /* ************************************ */
        
        // walidacja wartosci
        public function validByMethod($method, $value, $name, $key){
            $value_new = false;
            
            switch($method){
                case 'isName':
                    if(!$value_new = self::validIsName($value)){
                        $this->errors[] = "<b>{$name}</b>: Pole nie jest tekstem.";
                    }
                break;
                case 'isDate':
                    if(!$value_new = self::validIsDate($value)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format daty.";
                    }
                break;
                case 'isDateTime':
                    if(!$value_new = self::validIsDateTime($value)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format daty.";
                    }
                break;
                case 'isInt':
                    if(!$value_new = static::validIsInt($value)){
                        $this->errors[] = "<b>{$name}</b>: Pole nie jest liczbą.";
                    }
                break;
                case 'isPhone':
                    if(!$value_new = self::validIsPhone($value, $key)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format telefonu.";
                    }
                break;
                case 'isEmail':
                    if(!$value_new = self::validIsEmail($value)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format maila.";
                    }
                break;
                case 'isBool':
                    if(!$value_new = self::validIsBool($value)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format.";
                    }
                case 'isNormalChars':
                    if(!$value_new = self::validIsNormalChars($value)){
                        $this->errors[] = "<b>{$name}</b>: Niepoprawny format.";
                    }
                break;
            }
            
            if($value_new !== true && $value_new !== false && $value_new != $value){
                $value = $value_new;
            }
            
            return $value;
        }
        
        // sprawdzanie czy wartosc sklada sie tylko z liter
        public static function validIsName($value){
            // spółka
            if (preg_match('/^[a-zA-ZążśźęćńółĄŻŚŹĘĆŃÓŁ]+$/', $value)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc sklada sie tylko z liter bez polskich znakow i liczb
        public static function validIsNormalChars($value){
            // asasdasd213ad
            if (preg_match('/^[a-zA-Z0-9]+$/', $value)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc jest data
        public static function validIsDate($value){
            if($value === NULL){
                return true;
            }
            
            // 2012-09-12
            if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $value)) {
                return true;
            }
            
            if (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])[\.](0[1-9]|1[0-2])[\.]([0-9]{4})$/', $value)){
                $date = DateTime::createFromFormat('d.m.Y', $value);
                return $date->format('Y-m-d');
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc jest datetime
        public static function validIsDateTime($value){
            if($value === NULL || $value == '0000-00-00 00:00:00'){
                return true;
            }
            
            // 2012-09-12 12:35:45
            // if (preg_match('/^(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})$/', $value)) {
            if (preg_match('/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) ([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $value)) {
                return true;
            }
            
            if (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])[\.](0[1-9]|1[0-2])[\.]([0-9]{4}) ([0-1][0-9]|2[0-3]):([0-5][0-9])$/', $value)) {
                $value = $value.':00';
                $date = DateTime::createFromFormat('d.m.Y H:i:s', $value);
                return $date->format('Y-m-d H:i:s');
            }
            
            if (preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])[\.](0[1-9]|1[0-2])[\.]([0-9]{4}) ([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $value)) {
                $date = DateTime::createFromFormat('d.m.Y H:i:s', $value);
                return $date->format('Y-m-d H:i:s');
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc sklada sie tylko z liczb
        public static function validIsInt($value){
            // 23424
            if (preg_match('/^\d+$/', $value)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc sklada sie tylko z liczb
        public static function validIsPhone($value, $key){
            if((!isset(static::$definition['fields'][$key]['required']) || (isset(static::$definition['fields'][$key]['required']) && static::$definition['fields'][$key]['required'] == false)) && $value == ''){
                return true;
            }
            
            // +000000000
            // 0000000000
            // 000000000
            // 0000000
            // +00 000 00 00
            // (+00) 000 00 00
            // (00) 000 00 00
            // +00-000-00-00
            // 000 000 00 00
            // 000-000-00-00
            // 000 00 00
            // 000-00-00
            // 000 000 000
            // 000-000-000
            if (preg_match('/^(\(([0-9]{3}|[+]?[0-9]{2})\)|[0-9]{3}|[+]?[0-9]{2})?([ -]?)([0-9]{3})([ -]?)([0-9]{2,3})([ -]?)([0-9]{2,3})$/', $value)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc jest mailem
        public static function validIsEmail($value){
            if(filter_var($value, FILTER_VALIDATE_EMAIL)) {
                return true;
            }
            
            return false;
        }
        
        // sprawdzanie czy wartosc jest true/false
        public static function validIsBool($value){
            if($value === false || $value === true || $value == '1' || $value == '0' || $value == 'true' || $value == 'false'){
                return true;
            }
            
            return false;
        }
        
        // pobieranie rekordu
        public function getItem($id){
            if(!$values = self::sqlGetItem($id)){
                $this->errors[] = "Brak rekordu w bazie.";
                return false;
            }
            return $values;
        }
        
        // przypisanie cech
        protected function setProperties($values){
            if(!isset(static::$definition['fields'])){
                return;
            }
            
            foreach(static::$definition['fields'] as $key => $val){
                $this->$key = $values[$key];
            }
            $this->load_class = true;
        }
        
        // konwersja daty ang na polska
        public static function getPlDate($date, $format_return = 'd.m.Y H:i', $format_get = 'Y-m-d H:i:s'){
            if($date === NULL || $date == '0000-00-00 00:00:00'){
                return '';
            }
            
            $datetime = DateTime::createFromFormat($format_get, $date);
            return $datetime->format($format_return);
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // dodawanie do bazy
        protected function sqlAdd($table, $data){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').$table;
            return $DB->insert($table_name, $data);
        }
        
        // aktualizacja w bazie
        protected function sqlUpdate($table, $data, $where){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').$table;
            
            if(static::$is_log){
                $table_name_log = static::$prefix_log.$table;
                
                if(!$item_to_log = self::sqlGetItem($this->id)){
                    $this->errors[] = "LOG: Błąd podczas pobierania rekordu z bazy.";
                    return false;
                }
                
                if(!$DB->insert($table_name_log, $item_to_log)){
                    $this->errors[] = "LOG: Błąd podczas zapisywania rekordu w tabeli z logami.";
                    return false;
                }
            }
            
            return $DB->update($table_name, $data, $where);
        }
        
        // zmiana w tabeli w kolumnie deleted rekordu na 0
        protected function sqlDeleteOnColumn($table, $id_item){
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
            
            $data = array();
            $data['id_user'] = ClassAuth::getCurrentUserId();
            $data['deleted'] = '1';
            
            if (property_exists($this, 'date_update')) {
                $data['date_update'] = $this->date_update;
            }
            
            if (property_exists($this, 'active')) {
                $data['active'] = '0';
            }
            
            return $DB->update($table_name, $data, $where);
        }
        
        // pobieranie rekordu
        public static function sqlGetItem($id){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$definition['table'];
            $where = '';
            
            if(static::$has_deleted_column){
                $where = " AND `deleted` = '0'";
            }
            
            $zapytanie = "SELECT * FROM {$table_name} WHERE ".static::$definition['primary']." = {$id}{$where}";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$definition['table'];
            $where = '';
            $limit = '';
            
            if(static::$has_deleted_column){
                $where = " WHERE `deleted` = '0'";
            }
            
            if(static::$is_search && $where_search = self::generateWhereList($controller_search)){
                if($where == ''){
                    $where = "WHERE ";
                }else{
                    $where .= " AND ";
                }
                
                $where .= $where_search;
            }
            
            if($using_pages){
                $limit_start = ($current_page-1)*$items_on_page;
                $limit = " LIMIT {$limit_start}, {$items_on_page}";
            }
            
            $zapytanie = "SELECT * FROM {$table_name}{$where} ORDER BY `".static::$definition['primary']."`{$limit}";
            // print_r($zapytanie);
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie liczby wszystkich rekordow
        public static function sqlGetCountItems($controller_search = ''){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$definition['table'];
            $where = '';
            
            if(static::$has_deleted_column){
                $where = " WHERE `deleted` = '0'";
            }
            
            if(static::$is_search && $where_search = self::generateWhereList($controller_search)){
                if($where == ''){
                    $where = "WHERE ";
                }else{
                    $where .= " AND ";
                }
                
                $where .= $where_search;
            }
            
            $zapytanie = "SELECT COUNT(*) as count_items
                FROM `{$table_name}`
                {$where}
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql['count_items'];
        }
        
        // pobieranie ostatnich zmian
        public function sqlGetLogItem($limit = '5'){
            if(!static::$is_log || !$this->load_class){
                return false;
            }
            
            global $DB;
            $table_name_log = static::$prefix_log.static::$definition['table'];
            
            // zmiana id_mission na id_log_mission
            $id_log = substr_replace(static::$definition['primary'],'log_',3,0);
            
            $zapytanie = "
                SELECT log_table.`{$id_log}` as id_log, log_table.`date_update`, users.`first_name`, users.`second_name`
                FROM `{$table_name_log}` as log_table, users
                WHERE log_table.`".static::$definition['primary']."` = {$this->id}
                    AND log_table.`id_user` = users.`user_id`
                ORDER BY log_table.`date_update` DESC
                LIMIT {$limit}
            ";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
    }
?>