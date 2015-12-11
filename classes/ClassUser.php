<?php
    class ClassUser extends ClassModel{
        public static $use_prefix = true;
        
        // podczas pobierania rekordow, wywoluje sprawdzenie, ktore sa usuniete
        protected static $has_deleted_column = true;
        
        // min dlugosc hasla
        protected $min_length_password = 8;
        
        // login
        public $login;
        
        // mail
        public $mail;
        
        // haslo
        public $password;
        
        // id prawa uzytkownika
        public $id_permission;
        
        // id jednostki
        public $id_military;
        
        // aktywny
        public $active;
        
        // guard
        public $guard = 1;
        
        // usuniety
        public $deleted = 0;
        
        // imie
        public $name;
        
        // nazwisko
        public $surname;
        
        // telefon
        public $phone;
        
        // data dodania
        public $date_add;
        
        // uzytkownik dodania
        public $id_user_add;
        
        // data dodania
        public $date_delete;
        
        // uzytkownik usuniecia
        public $id_user_delete = null;
        
        // data aktualizacji
        public $date_update;
        
        // uzytkownik aktualizacji
        public $id_user_update = null;
        
        // nazwa profilu
        public $name_permission = '';
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'users',
            'primary' => 'id_user',
            'fields' => array(
                'name' =>              array('required' => true, 'validate' => array('isName'), 'name' => 'Imię'),
                'surname' =>           array('required' => true, 'validate' => array('isName'), 'name' => 'Nazwisko'),
                'login' =>             array('required' => true, 'validate' => array('isNormalChars'), 'name' => 'Login'),
                'mail' =>              array('required' => true, 'validate' => array('isEmail'), 'name' => 'Adres e-mail'),
                'password' =>          array('required' => true, 'name' => 'Hasło'),
                'id_permission' =>     array('required' => true, 'validate' => array('isInt'), 'name' => 'Profil uprawnień'),
                'id_military' =>       array('required' => true, 'validate' => array('isInt'), 'name' => 'Jednostka wojskowa'),
                'active' =>            array('required' => true, 'validate' => array('isBool'), 'name' => 'Aktywny'),
                'guard' =>             array('validate' => array('isBool'), 'name' => 'Guard'),
                'deleted' =>           array('validate' => array('isBool'), 'name' => 'Usunięty'),
                'phone' =>             array('validate' => array('isPhone'), 'name' => 'Telefon'),
                'date_add' =>          array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data dodania'),
                'id_user_add' =>       array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik dodania'),
                'date_delete' =>       array('validate' => array('isDateTime'), 'name' => 'Data usunięcia'),
                'id_user_delete' =>    array('validate' => array('isInt'), 'name' => 'Użytkownik usunięcia'),
                'date_update' =>       array('validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'id_user_update' =>    array('validate' => array('isInt'), 'name' => 'Użytkownik aktualizacji'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            $this->getUser($this->id);
            return;
        }
        
        // pobieranie uzytkownika
        public function getUser($id_user){
            if(!$user = $this->sqlGetUser($id_user)){
                $this->errors = "Brak użytkownika w bazie.";
                return false;
            }
            
            foreach($user as $key => $value){
                if (property_exists($this, $key)){
                    $this->$key = $value;
                }
            }
            
            $this->name_permission = $user['name_permission'];
            
            $this->load_class = true;
            return true;
        }
        
        // sprawdzanie czy uzytkownik istnieje
        public static function isUser($id_user){
            return self::sqlUserExist($id_user);
        }
        
        // pobieranie profili (uprawnien)
        public static function getPermissions(){
            return self::sqlGetPermissions();
        }
        
        // sprawdzanie czy wartosc sklada sie tylko z liczb
        public static function validIsInt($value){
            if($value === null){
                return true;
            }
            
            return parent::validIsInt($value);
        }
        
        // sprawdzanie sily hasla
        protected function checkPasswordStrong($password){
             // sprawdzanie czy haslo posiada znaki
            if (!preg_match("/\\d/", $password)) {
                return false;
            }
            
             // sprawdzanie czy haslo posiada male znaki
            if (!preg_match("/[a-z]/", $password)) {
                return false;
            }
            
             // sprawdzanie czy haslo posiada duze znaki
            if (!preg_match("/[A-Z]/", $password)) {
                return false;
            }
            
            return true;
        }
        
        // sprawdzanie czy login istnieje
        protected function checkLoginExists($login){
            return self::sqlCheckLoginExists($login);
        }
        
        // sprawdzanie czy mail istnieje
        protected function checkMailExists($mail){
            return self::sqlCheckMailExists($mail);
        }
        
        // pobieranie listy uzytkownikow
        public static function getAllItemsList($using_pages = false, $current_page = '1', $items_on_page = '5'){
            if(!$users = self::sqlGetAllItemsList($using_pages, $current_page, $items_on_page)){
                return false;
            }
            
            foreach($users as $key => $user){
                $users[$key]['name_status'] = self::getNameStatus($user['active']);
                $users[$key]['name_guard'] = self::getNameGuard($user['guard']);
            }
            
            return $users;
        }
        
        // nazwa statusu (active)
        public static function getNameStatus($status, $color = true){
            switch($status){
                case '0':
                    return $color ? '<span class="sew_red">Wyłączony</span>' : 'Wyłączony';
                break;
                case '1':
                    return $color ? '<span class="sew_green">Włączony</span>' : 'Włączony';
                break;
            }
            
            return false;
        }
        
        // snazwa guard
        public static function getNameGuard($guard, $color = true){
            switch($guard){
                case '0':
                    return $color ? '<span class="sew_red">Nieaktywny</span>' : 'Nieaktywny';
                break;
                case '1':
                    return $color ? '<span class="sew_green">Aktywny</span>' : 'Aktywny';
                break;
            }
            
            return false;
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        // dodawanie
        public function add($auto_date = true){
            if (isset($this->id)) {
                unset($this->id);
            }
            
            // automatyczne przypisywanie dat
            if ($auto_date && property_exists($this, 'date_add')) {
                $this->date_add = date('Y-m-d H:i:s');
            }
            
            // spawdzenie zmiennych
            $values = $this->getFieldsValidate();
            
            // sprawdzanie hasla
            $name = static::$definition['fields']['password']['name'];
            if(strlen($values['password']) < (int)$this->min_length_password){ // sprawdzanie dlugosci hasla
                $this->errors[] = "<b>{$name}</b>: Hasło jest za krótkie, minimalna długość <b>{$this->min_length_password}</b> znaków.";
            }elseif (preg_match("/\\s/", $values['password'])) { // sprawdzanie czy haslo ma spacje i inne biale znaki
                $this->errors[] = "<b>{$name}</b>: Hasło posiada spacje.";
            }elseif(!$this->checkPasswordStrong($values['password'])){ // sprawdzanie sily hasla
                $this->errors[] = "<b>{$name}</b>: Hasło powinno składać się minimalnie z jednego znaku małego, jednego znaku dużego i jednej cyfry.";
            }
            
            // sprawdzanie czy login istnieje
            if($this->checkLoginExists($values['login'])){
                $name = static::$definition['fields']['login']['name'];
                $this->errors[] = "<b>{$name}</b>: Nazwa użytkownika już istnieje.";
            }
            
            // sprawdzanie czy mail istnieje
            if($this->checkMailExists($values['mail'])){
                $name = static::$definition['fields']['mail']['name'];
                $this->errors[] = "<b>{$name}</b>: Użytkownik z tym mailem już istnieje.";
            }
            
            if($this->errors && count($this->errors) > 0){
                return false;
            }
            
            if (!$id = $this->sqlAdd(static::$definition['table'], $values)){
                $this->errors[] = "Błąd zapisu do bazy.";
                return false;
            }
            
            $this->id = $id;
            return true;
        }
        
        // usuwanie
        public function delete($auto_date = true){
            if(!isset($this->id)){
                $this->errors = "Brak podanego id.";
                return false;
            }
            
            if(!isset($this->id_user_delete) || $this->id_user_delete === null || $this->id_user_delete == ''){
                $this->errors = "Brak podanego id użytkownika.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_delete')) {
                $this->date_delete = date('Y-m-d H:i:s');
            }
            
            if(!$this->sqlDelete(static::$definition['table'], $this->id)){
                $this->errors[] = "Błąd podczas usuwania użytkownika.";
            }
            
            unset($this->id);
            if($this->load_class){
                $this->load_class = false;
            }
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
            
            // sprawdzanie czy uzytkownik z takim loginem juz istnieje
            if($this->sqlCheckUserLoginExists($this->login)){
                $this->errors = "Użytkownik o takim loginie już istnieje.";
                return false;
            }
            
            // sprawdzanie czy uzytkownik z takim mailem juz istnieje
            if($this->sqlCheckUserMailExists($this->mail)){
                $this->errors = "Użytkownik o takim adresie e-mail już istnieje.";
                return false;
            }
            
            if (!$this->sqlUpdate(static::$definition['table'], $values, static::$definition['primary'].' = '.$this->id)){
                $this->errors[] = "Błąd aktualizacji rekordu w bazie.";
                return false;
            }
            
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // sprawdzanie czy login istnieje
        protected function sqlCheckLoginExists($login){
            global $DB;
            $zapytanie = "SELECT * FROM `sew_users` WHERE `login` = '{$login}' AND `deleted` = '0'";
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
        
        // sprawdzanie czy login istnieje
        protected function sqlCheckUserLoginExists($login){
            global $DB;
            $zapytanie = "SELECT `login`
                FROM `sew_users`
                WHERE `login` = '{$login}'
                    AND `deleted` = '0'
                    AND `id_user` != '{$this->id}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
        
        // sprawdzanie czy mail istnieje
        protected function sqlCheckUserMailExists($mail){
            global $DB;
            $zapytanie = "SELECT `mail`
                FROM `sew_users`
                WHERE `mail` LIKE '{$mail}'
                    AND `deleted` = '0'
                    AND `id_user` != '{$this->id}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
        
        // sprawdzanie czy mail istnieje
        protected function sqlCheckMailExists($mail){
            global $DB;
            $zapytanie = "SELECT * FROM `sew_users` WHERE `mail` = '{$mail}' AND `deleted` = '0'";
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
        
        // dodawanie do bazy
        protected function sqlAdd($table, $data){
            global $DB;
            
            $data1 = array(
                'login' => $data['login'],
                'mail' => $data['mail'],
                'password' => $data['password'],
                'id_permission' => $data['id_permission'],
                'id_military' => $data['id_military'],
                'active' => $data['active'],
                'guard' => $data['guard']
            );
            
            if(!$id = $DB->insert('sew_users', $data1)){
                return false;
            }
            
            $data2 = array(
                'id_user' => $id,
                'name' => $data['name'],
                'surname' => $data['surname'],
                'phone' => $data['phone'],
                'date_add' => $data['date_add'],
                'id_user_add' => $data['id_user_add']
            );
            
            $DB->insert('sew_user_options', $data2);
            
            return true;
        }
        
        // dodawanie do bazy
        protected function sqlDelete($table, $id_user){
            global $DB;
            $where = "`id_user` = '{$id_user}'";
            
            $data1 = array(
                'deleted' => '1',
                'active' => '0'
            );
            
            $data2 = array(
                'date_delete' => $this->date_delete,
                'id_user_delete' => $this->id_user_delete
            );
            
            if(!$DB->update('sew_users', $data1, $where)){
                return false;
            }
            
            return $DB->update('sew_user_options', $data2, $where);
        }
        
        // aktualizacja w bazie
        protected function sqlUpdate($table, $data, $where){
            global $DB;
            
            // dodawanie logu
            if(!$user = $this->sqlGetUser($this->id, true)){
                $this->errors[] = "LOG: Błąd podczas pobierania rekordu z bazy.";
                return false;
            }
            
            $data_log = array(
                'id_user'        => $user['id_user'],
                'login'          => $user['login'],
                'mail'           => $user['mail'],
                'password'       => $user['password'],
                'id_permission'  => $user['id_permission'],
                'id_military'    => $user['id_military'],
                'active'         => $user['active'],
                'guard'          => $user['guard'],
                'name'           => $user['name'],
                'surname'        => $user['surname'],
                'phone'          => $user['phone'],
                'date_update'    => $data['date_update'],
                'id_user_update' => $data['id_user_update']
            );
                
            if(!$DB->insert('log_users', $data_log)){
                $this->errors[] = "LOG: Błąd podczas zapisywania rekordu w tabeli z logami.";
                return false;
            }
            
            // zapis poprawny
            $data1 = array(
                'login'          => $data['login'],
                'mail'           => $data['mail'],
                'id_permission'  => $data['id_permission'],
                'id_military'    => $data['id_military'],
                'active'         => $data['active'],
                'guard'          => $data['guard'],
            );
            
            $data2 = array(
                'name'           => $data['name'],
                'surname'        => $data['surname'],
                'phone'          => $data['phone'],
            );
            
            if(!$DB->update('sew_users', $data1, $where)){
                return false;
            }
            
            return $DB->update('sew_user_options', $data2, $where);
        }
        
        protected function sqlGetUser($id_user, $password = false){
            global $DB;
            
            $select = '';
            
            if($password){
                $select = ', su.`password`';
            }
            
            $zapytanie = "SELECT su.`id_user`, su.`login`, su.`mail`, su.`active`, su.`id_military`, su.`id_permission`, su.`guard`, su.`deleted`, suo.`name`, suo.`surname`, suo.`phone`, suo.`date_add`, suo.`id_user_add`, suo.`date_delete`, suo.`id_user_delete`, sp.`name` as name_permission{$select}
                FROM `sew_users` as su, `sew_user_options` as suo, `sew_permissions` as sp
                WHERE su.`id_user` = '{$id_user}'
                    AND su.`id_user` = suo.`id_user`
                    AND sp.`id_permission` = su.`id_permission`
                    AND su.`deleted` = '0'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // sprawdzanie czy uzytkownik istnieje
        public static function sqlUserExist($id_user){
            global $DB;
            $zapytanie = "SELECT `id_user`
                FROM `sew_users`
                WHERE `id_user` = {$id_user}
                    AND `deleted` = '0'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return true;
        }
        
        // pobieranie profili (uprawnien)
        public static function sqlGetPermissions(){
            global $DB;
            $zapytanie = "SELECT * FROM sew_permissions";
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie wszystkich uzytkownikow
        public static function sqlGetAllItemsList($using_pages = false, $current_page = '1', $items_on_page = '5'){
            global $DB;
            
            $where = '';
            $limit = '';
            
            if(static::$has_deleted_column){
                $where = " AND su.`deleted` = '0'";
            }
            
            if($using_pages){
                $limit_start = ($current_page-1)*$items_on_page;
                $limit = " LIMIT {$limit_start}, {$items_on_page}";
            }
            
            $zapytanie = "SELECT su.`id_user`, su.`mail`, su.`active`, su.`guard`, suo.`name`, suo.`surname`, sp.`name` as name_permission
                FROM `sew_users` as su, `sew_user_options` as suo, `sew_permissions` as sp
                WHERE su.`id_user` = suo.`id_user` AND sp.`id_permission` = su.`id_permission`{$where}
                ORDER BY `".static::$definition['primary']."`
                {$limit}
            ;";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            return $sql;
        }
        
        /* *************** AJAX ************** */
        /* *********************************** */
        
        // generowanie nowego hasla
        public static function generateNewPassword($ajaxData){
            return ClassTools::generateRandomPasswd();
        }
        
        // wyszukiwanie jednostek dla zolnierza
        public static function searchMilitaries($ajaxData){
            return array('error' => 'test');
            // return ClassTools::generateRandomPasswd();
        }
    }
?>
