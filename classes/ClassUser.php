<?php
    class ClassUser extends ClassModel{
        public static $use_prefix = true;
        public static $is_search = true;
        
        // podczas pobierania rekordow, wywoluje sprawdzenie, ktore sa usuniete
        protected static $has_deleted_column = true;
        
        // min dlugosc hasla
        protected $min_length_password = 8;
        
        // login
        public $login;
        
        // login tymczasowy
        public $login_tmp;
        
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
        
        // nazwa jednoski
        public $military_name = 'Nie przypisano';
        
        // nazwa statusu
        public $status_name = '';
        
        // nazwa guard
        public $guard_name = '';
        
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
            
            // nazwa uprawnien
            $this->name_permission = $user['name_permission'];
                
            // nazwa jednostki wojskowej
            if($this->id_military !== null){
                $military = new ClassMilitary($this->id_military);
                $this->military_name = $military->name.', '.$military->location;
            }
            
            // nazwa statusu
            $this->status_name = self::getNameStatus($this->active);
            
            // nazwa guard
            $this->guard_name = self::getNameGuard($this->guard);
            
            // login tymczasowo
            $this->login_tmp = $this->login;
            
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
        public static function getAllItemsList($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
            if(!$users = self::sqlGetAllItemsList($using_pages, $current_page, $items_on_page, $controller_search)){
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
        
        // pobieranie loginu usera przez klucz z sesji
        public static function getUserByAuthKey($auth_key){
            return self::sqlGetUserByAuthKey($auth_key);
        }
        
        /* ************* MOJE KONTO ************ */
        /* ************************************ */
        
        public function myAccountEdit($auto_date = true)
        {
            if(!isset($this->id)){
                $this->errors = "Brak podanego id.";
                return false;
            }
            
            if ($auto_date && property_exists($this, 'date_update')) {
                $this->date_update = date('Y-m-d H:i:s');
            }
            
            // spawdzenie zmiennych
            $values = $this->getFieldsValidate();
            
            if($this->errors && count($this->errors) > 0){
                return false;
            }
            
            // sprawdzanie czy uzytkownik z takim mailem juz istnieje
            if($this->sqlCheckUserMailExists($this->mail)){
                $this->errors = "Użytkownik o takim adresie e-mail już istnieje.";
                return false;
            }
            
            if (!$this->sqlMyAccountUpdate(static::$definition['table'], $values, static::$definition['primary'].' = '.$this->id)){
                $this->errors[] = "Aktualizacja mojego konta: Błąd aktualizacji rekordu w bazie.";
                return false;
            }
            
            // return false;
            return true;
        }
        
        // zmiana hasla
        public function myAccountEditPassword($new_password, $new_password_repeat)
        {
            if(!isset($this->id)){
                $this->errors = "Brak podanego id.";
                return false;
            }
            
            $empty = false;
            $new_password = trim($new_password);
            $new_password_repeat = trim($new_password_repeat);
            
            // sprawdzanie czy haslo jest puste
            if($new_password == '' || empty($new_password)){
                $this->errors[] = "<b>Nowe hasło</b>: Pole jest puste.";
                $empty = true;
            }
            
            // sprawdzanie czy haslo jest puste
            if($new_password_repeat == '' || empty($new_password_repeat)){
                $this->errors[] = "<b>Powtórz nowe hasło</b>: Pole jest puste.";
                $empty = true;
            }
            
            if($empty){
                return false;
            }
            
            // sprawdzanie czy hasla sie nie roznia
            if($new_password != $new_password_repeat){
                $this->errors = "Hasła się różnią.";
                return false;
            }
            
            // haslo
            $name = static::$definition['fields']['password']['name'];
            if(strlen($new_password) < (int)$this->min_length_password){ // sprawdzanie dlugosci hasla
                $this->errors[] = "<b>{$name}</b>: Hasło jest za krótkie, minimalna długość <b>{$this->min_length_password}</b> znaków.";
            }elseif (preg_match("/\\s/", $new_password)) { // sprawdzanie czy haslo ma spacje i inne biale znaki
                $this->errors[] = "<b>{$name}</b>: Hasło posiada spacje.";
            }elseif (preg_match("/['\"]/", $new_password)) { // sprawdzanie czy haslo ma cudzyslow lub apostrof
                $this->errors[] = "<b>{$name}</b>: Hasło posiada cudzysłów lub apostrof.";
            }elseif(!$this->checkPasswordStrong($new_password)){ // sprawdzanie sily hasla
                $this->errors[] = "<b>{$name}</b>: Hasło powinno składać się minimalnie z jednego znaku małego, jednego znaku dużego i jednej cyfry.";
            }
            
            if($this->errors && count($this->errors) > 0){
                return false;
            }
            
            if (!$id = $this->sqlUpdatePassword($new_password)){
                $this->errors[] = "Zmiana hasła: Błąd zapisu do bazy.";
                return false;
            }
            
            return true;
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        // dodawanie
        public function add($auto_date = true)
        {
            // sprawdzanie czy uzytkownik ma uprawnienia do akcji
            if(!$this->checkHasPermissions()){
                $this->errors[] = "Użytkownik nie ma uprawnień do dodawania w tym module.";
                return false;
            }
            
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
            }elseif (preg_match("/['\"]/", $values['password'])) { // sprawdzanie czy haslo ma cudzyslow lub apostrof
                $this->errors[] = "<b>{$name}</b>: Hasło posiada cudzysłów lub apostrof.";
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
            
            // sprawdzanie czy item istnieje i jest aktywna
            if($this->id_military !== NULL)
            {
                $item = new ClassMilitary($this->id_military);
                
                if(!$item->load_class){
                    $this->errors = "Jednostka wojskowa nie istnieje.";
                    return false;
                }
                
                if($item->active != '1'){
                    $this->errors = "Jednostka wojskowa jest wyłączona.";
                    return false;
                }
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
        public function delete($auto_date = true)
        {
            // sprawdzanie czy uzytkownik ma uprawnienia do akcji
            if(!$this->checkHasPermissions()){
                $this->errors[] = "Użytkownik nie ma uprawnień do usuwania w tym module.";
                return false;
            }
            
            if(!isset($this->id)){
                $this->errors = "Brak podanego id.";
                return false;
            }
            
            if(!isset($this->id_user_delete) || $this->id_user_delete === null || $this->id_user_delete == ''){
                $this->errors = "Brak podanego id użytkownika.";
                return false;
            }
            
            if($this->id == $this->id_user_delete){
                $this->errors = "Nie można siebie usunąć.";
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
        public function update($auto_date = true)
        {
            // sprawdzanie czy uzytkownik ma uprawnienia do akcji
            if(!$this->checkHasPermissions()){
                $this->errors[] = "Użytkownik nie ma uprawnień do edycji w tym module.";
                return false;
            }
            
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
            
            // sprawdzanie czy item istnieje i jest aktywna
            if($this->id_military !== NULL)
            {
                $item = new ClassMilitary($this->id_military);
                
                if(!$item->load_class){
                    $this->errors = "Jednostka wojskowa nie istnieje.";
                    return false;
                }
                
                if($item->active != '1'){
                    $this->errors = "Jednostka wojskowa jest wyłączona.";
                    return false;
                }
            }
            
            if (!$this->sqlUpdate(static::$definition['table'], $values, static::$definition['primary'].' = '.$this->id)){
                $this->errors[] = "Błąd aktualizacji rekordu w bazie.";
                return false;
            }
            
            return true;
        }
        
        // zmiana hasla
        public function passwordUpdate($new_password, $new_password_repeat)
        {
            // sprawdzanie czy uzytkownik ma uprawnienia do akcji
            if(!$this->checkHasPermissions()){
                $this->errors[] = "Użytkownik nie ma uprawnień do edycji w tym module.";
                return false;
            }
            
            global $login;
            
            if(!isset($this->id)){
                $this->errors = "Brak podanego id.";
                return false;
            }
            
            $empty = false;
            $new_password = trim($new_password);
            $new_password_repeat = trim($new_password_repeat);
            
            // sprawdzanie czy haslo jest puste
            if($new_password == '' || empty($new_password)){
                $this->errors[] = "<b>Nowe hasło</b>: Pole jest puste.";
                $empty = true;
            }
            
            // sprawdzanie czy haslo jest puste
            if($new_password_repeat == '' || empty($new_password_repeat)){
                $this->errors[] = "<b>Powtórz nowe hasło</b>: Pole jest puste.";
                $empty = true;
            }
            
            if($empty){
                return false;
            }
            
            // sprawdzanie czy hasla sie nie roznia
            if($new_password != $new_password_repeat){
                $this->errors = "Hasła się różnią.";
                return false;
            }
            // preg_match("/[\'|\"]/", "sdafsdf")
            // haslo
            $name = static::$definition['fields']['password']['name'];
            if(strlen($new_password) < (int)$this->min_length_password){ // sprawdzanie dlugosci hasla
                $this->errors[] = "<b>{$name}</b>: Hasło jest za krótkie, minimalna długość <b>{$this->min_length_password}</b> znaków.";
            }elseif (preg_match("/\\s/", $new_password)) { // sprawdzanie czy haslo ma spacje i inne biale znaki
                $this->errors[] = "<b>{$name}</b>: Hasło posiada spacje.";
            }elseif (preg_match("/['\"]/", $new_password)) { // sprawdzanie czy haslo ma cudzyslow lub apostrof
                $this->errors[] = "<b>{$name}</b>: Hasło posiada cudzysłów lub apostrof.";
            }elseif(!$this->checkPasswordStrong($new_password)){ // sprawdzanie sily hasla
                $this->errors[] = "<b>{$name}</b>: Hasło powinno składać się minimalnie z jednego znaku małego, jednego znaku dużego i jednej cyfry.";
            }
            
            if($this->errors && count($this->errors) > 0){
                return false;
            }
            
            if (!$id = $this->sqlUpdatePassword($new_password)){
                $this->errors[] = "Błąd zapisu do bazy.";
                return false;
            }
            
            // wylogowanie uzytkownika ktoremu zmieniono haslo
            $login->logoutById($this->id);
            
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie loginu usera przez klucz z sesji
        public static function sqlGetUserByAuthKey($auth_key){
            global $DB;
            
            $zapytanie = "SELECT su.`id_user`, su.`login`, su.`id_permission`,  sul.`id_users_login`,  sul.`auth_key`,  sul.`id_user_guard`,  sul.`is_logged`,  sul.`date_refresh`,  su.`mail`,  su.`guard`,  sug.`guard_key`,  sug.`guard_ip`, sug.`verified`, sug.`date_guard_send`
                FROM `sew_users` as su, `sew_user_login` as sul, `sew_user_guard` as sug
                WHERE sul.`auth_key` = '".ClassTools::pSQL($auth_key)."'
                    AND su.`id_user` = sul.`id_user`
                    AND sug.`id_user_guard` = sul.`id_user_guard`
                    AND su.`deleted` = '0'
                    AND su.`active` = '1'
            ;";
            // print_r($zapytanie);
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
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
                // 'password' => $data['password'],
                'password' => ClassAuth::generatePassword($data['password']), // [hEf&ReI?d1:Em(
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
        
        // aktualizacja w bazie mojego konta
        protected function sqlMyAccountUpdate($table, $data, $where){
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
                'mail'           => $data['mail'],
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
        public static function sqlGetAllItemsList($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = ''){
            global $DB;
            
            $where = '';
            $limit = '';
            
            if(static::$has_deleted_column){
                $where = " AND su.`deleted` = '0'";
            }
            
            if(static::$is_search && $where_search = static::generateWhereList($controller_search, 'su')){
                $where .= " AND ";
                $where .= $where_search;
            }
            
            if($using_pages){
                $limit_start = ($current_page-1)*$items_on_page;
                $limit = " LIMIT {$limit_start}, {$items_on_page}";
            }
            
            $zapytanie = "SELECT su.`id_user`, su.`mail`, su.`login`, su.`active`, su.`guard`, suo.`name`, suo.`surname`, sp.`name` as name_permission
                FROM `sew_users` as su, `sew_user_options` as suo, `sew_permissions` as sp
                WHERE su.`id_user` = suo.`id_user`
                    AND sp.`id_permission` = su.`id_permission`
                    {$where}
                ORDER BY `".static::$definition['primary']."`
                {$limit}
            ;";
            
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
                $where = "AND `deleted` = '0'";
            }
            
            if(static::$is_search && $where_search = static::generateWhereList($controller_search, 'su')){
                $where .= " AND ";
                $where .= $where_search;
            }
            
            $zapytanie = "SELECT COUNT(*) as count_items
                FROM `sew_users` as su, `sew_user_options` as suo
                WHERE su.`id_user` = suo.`id_user`
                    {$where}
            ;";
            // print_r($where);
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql['count_items'];
        }
        
        // dodawanie do bazy
        public function sqlUpdatePassword($new_password, $id_user_new_password = false){
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
                'date_update'    => date('Y-m-d H:i:s'),
                'id_user_update' => ClassAuth::getCurrentUserId()
            );
                
            if(!$DB->insert('log_users', $data_log)){
                $this->errors[] = "LOG: Błąd podczas zapisywania rekordu w tabeli z logami.";
                return false;
            }
            
            $where = "`id_user` = '{$this->id}'";
            
            if(!$DB->update('sew_users', array('password' => ClassAuth::generatePassword($new_password)), $where)){
                return false;
            }
            
            if($id_user_new_password){
                $where = "`id_user_new_password` = '{$id_user_new_password}'";
                
                if(!$DB->update('sew_user_new_password', array('generated' => '1'), $where)){
                    return false;
                }
            }
            
            return true;
        }
        
        // pobieranie imienia i nazwy uzytkownika
        public static function sqlGetNameSurnameById($id_user){
            global $DB;
            
            $zapytanie = "SELECT `id_user`, `name`, `surname`
                FROM `sew_user_options`
                WHERE `id_user` = '{$id_user}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
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
            
        
        /* *********** WYSZUKIWARKA ********** */
        /* *********************************** */
        
        // pobieranie where dla zapytania
        protected static function generateWhereList($controller, $prefix = false){
            if(!$session_search = self::getSearchSession($controller)){
                return false;
            }
            
            $prefix = $prefix ? "{$prefix}." : '';
            $first = true;
            
            foreach($session_search as $key => $val){
                $val = ClassTools::pSQL($val);
                
                if($prefix && ($key == 'name' || $key == 'surname')){
                    if($first){
                        $first = false;
                        $search = "suo.`{$key}` LIKE '%{$val}%'";
                    }else{
                        $search .= " AND suo.`{$key}` LIKE '%{$val}%'";
                    }
                }else{
                    if($first){
                        $first = false;
                        $search = "su.`{$key}` LIKE '%{$val}%'";
                    }else{
                        $search .= " AND su.`{$key}` LIKE '%{$val}%'";
                    }
                }
            }
            
            return $search;
        }
    }
?>
