<?php
    class ClassUser extends ClassModel{
        public static $use_prefix = true;
        
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
                'phone' =>             array('required' => true, 'validate' => array('isPhone'), 'name' => 'Telefon'),
                'date_add' =>          array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data dodania'),
                'id_user_add' =>       array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik dodania'),
                'date_delete' =>       array('validate' => array('isDateTime'), 'name' => 'Data usunięcia'),
                'id_user_delete' =>    array('validate' => array('isInt'), 'name' => 'Użytkownik usunięcia'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            // $this->getSoldier($this->id);
            return;
        }
        
        // pobieranie zolnierza
        public function getSoldier($id_soldier){
            if(!$soldier = $this->sqlGetSoldier($id_soldier)){
                $this->errors[] = "Brak żołnierza w bazie.";
                return false;
            }
            
            $this->soldierName = $soldier['soldierName'];
            $this->soldierSurname = $soldier['soldierSurname'];
            $this->birthday = $soldier['birthday'];
            $this->sex = $soldier['sex'];
            $this->phone = $soldier['phone'];
            $this->email = $soldier['email'];
            $this->code = $soldier['code'];
            $this->city = $soldier['city'];
            
            $this->load_class = true;
            return true;
        }
        
        // sprawdzanie czy zolnierz istnieje
        public static function isSoldier($id_soldier){
            return self::sqlSoldierExist($id_soldier);
        }
        
        // pobieranie profili (uprawnien)
        public static function getPermissions(){
            return self::sqlGetPermissions();
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
        
        protected function sqlGetSoldier($id_soldier){
            global $DB;
            $zapytanie = "SELECT * FROM soldiers WHERE id = {$id_soldier}";
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie zolnierzy
        public static function sqlGetAllSoldiers(){
            global $DB;
            $zapytanie = "SELECT * FROM soldiers";
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // sprawdzanie czy zolnierz istnieje
        public static function sqlSoldierExist($id_soldier){
            global $DB;
            $zapytanie = "SELECT id FROM soldiers WHERE `id` = {$id_soldier}";
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