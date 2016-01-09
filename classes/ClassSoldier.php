<?php
    class ClassSoldier extends ClassModel{
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        public static $is_search = true;
        
        // id
        public $id = false;
        
        // plec
        public $sex;
        
        // imie
        public $name;
        
        // drugie imie
        public $second_name;
        
        // nazwisko
        public $surname;
        
        // urodziny
        public $date_birthday;
        
        // miejsce urodzenia
        public $place_birthday;
        
        // obywatelstwo
        public $citizenship;
        
        // narodowosc
        public $nationality;
        
        // pesel
        public $pesel;
        
        // Seria i Nr dowodu osobistego
        public $identity_document;
        
        // adres e-mail
        public $mail;
        
        // telefon
        public $phone;
        
        // wysokosc
        public $height = NULL;
        
        // waga
        public $weight = NULL;
        
        // nr buta
        public $shoe_number = NULL;
        
        // grupa krwi
        public $blood_group;
        
        // imie matki
        public $name_mother;
        
        // nazwisko matki
        public $surname_mother;
        
        // imie ojca
        public $name_father;
        
        // nazwisko ojca
        public $surname_father;
        
        // imie malzonka
        public $name_partner;
        
        // nazwisko malzonka
        public $surname_partner;
        
        // poziom wyksztalcenia
        public $id_education_type;
        
        // Wojskowa Komenda Uzupełnień
        public $wku;
        
        // kategoria zdrowia
        public $health_category;
        
        // Wypadki i urazy
        public $injuries;
        
        // status
        public $id_status;
        
        // Użytkownik
        public $id_user;
        
        // Data aktualizacji
        public $date_update;
        
        // Usunięty
        public $deleted = '0';
        
        // poziom wyksztalcenia nazwa
        public $education_type_name;
        
        // Nazwa statusu
        public $status_name;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldiers',
            'primary' => 'id_soldier',
            'fields' => array(
                'sex'                   => array('required' => true, 'validate' => array('isInt'), 'name' => 'Płeć'),
                'name'                  => array('required' => true, 'validate' => array('isName'), 'name' => 'Imię'),
                'second_name'           => array('validate' => array('isName'), 'name' => 'Drugie imię'),
                'surname'               => array('required' => true, 'validate' => array('isName'), 'name' => 'Nazwisko'),
                'date_birthday'         => array('required' => true, 'validate' => array('isDate'), 'name' => 'Urodziny'),
                'place_birthday'        => array('name' => 'Miejsce urodzenia'),
                'citizenship'           => array('required' => true, 'validate' => array('isName'), 'name' => 'Obywatelstwo'),
                'nationality'           => array('required' => true, 'validate' => array('isName'), 'name' => 'Narodowość'),
                'pesel'                 => array('validate' => array('isInt'), 'name' => 'Pesel'),
                'identity_document'     => array('required' => true, 'name' => 'Seria i Nr dowodu osobistego'),
                'mail'                  => array('required' => true, 'validate' => array('isEmail'), 'name' => 'Adres e-mail'),
                'phone'                 => array('required' => true, 'validate' => array('isPhone'), 'name' => 'Telefon'),
                'height'                => array('validate' => array('isInt'), 'name' => 'Wysokość'),
                'weight'                => array('validate' => array('isInt'), 'name' => 'Waga'),
                'shoe_number'           => array('validate' => array('isInt'), 'name' => 'Nr buta'),
                'blood_group'           => array('name' => 'Grupa krwi'),
                'name_mother'           => array('validate' => array('isName'), 'name' => 'Imię Matki'),
                'surname_mother'        => array('validate' => array('isName'), 'name' => 'Nazwisko panieńskie Matki'),
                'name_father'           => array('validate' => array('isName'), 'name' => 'Imię Ojca'),
                'surname_father'        => array('validate' => array('isName'), 'name' => 'Nazwisko Ojca'),
                'name_partner'          => array('validate' => array('isName'), 'name' => 'Imię małżonki(a)'),
                'surname_partner'       => array('validate' => array('isName'), 'name' => 'Nazwisko małżonki(a)'),
                'id_education_type'     => array('required' => true, 'validate' => array('isInt'), 'name' => 'Poziom wyksztalcenia'),
                'wku'                   => array('name' => 'Wojskowa Komenda Uzupełnień'),
                'health_category'       => array('name' => 'Kategoria zdrowia'),
                'injuries'              => array('name' => 'Wypadki i urazy'),
                'id_status'             => array('required' => true, 'validate' => array('isInt'), 'name' => 'Status'),
                'id_user'               => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'           => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'deleted'               => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie danych gdy jest podane id
        public function load(){
            parent::load();
            
            if($this->load_class)
            {
                // poziom wyksztalcenia nazwa
                $this->education_type_name = ClassEducationType::sqlGetItemNameByIdParent($this->id_education_type);
                
                // Nazwa statusu
                $this->status_name = ClassSoldierStatus::sqlGetItemNameByIdParent($this->id_status);
                
                // Zmiana daty na polski format
                $this->date_birthday = date('d.m.Y', strtotime($this->date_birthday));
            }
        }
        
        
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            // sprawdzanie czy typ wyksztalcenia istnieje
            $education = new ClassEducationType($this->id_education_type);
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$education->load_class){
                $this->errors[] = "Typ wykształcenia nie istnieje.";
                return false;
            }
            
            // sprawdza czy typ wyksztalcenia jest aktywny
            if($education->active != '1'){
                $this->errors[] = "Typ wykształcenia nie jest aktywny.";
                return false;
            }
            
            // sprawdzanie czy status zolnierza istnieje
            $status = new ClassSoldierStatus($this->id_status);
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$status->load_class){
                $this->errors[] = "Status żołnierza nie istnieje.";
                return false;
            }
            
            // sprawdza czy status zolnierza jest aktywny
            if($status->active != '1'){
                $this->errors[] = "Status żołnierza nie jest aktywny.";
                return false;
            }
            
            // konwersja danty na cele zapisu w bazie
            $this->date_birthday = date('Y-m-d', strtotime($this->date_birthday));
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas aktualizowania
        public function updateCustomValidate(){
            if(!$this->addCustomValidate()){
                return false;
            }
            
            // Przy zmianie status na "W rezerwie", "W stanie spoczynku" lub "Zmarły",
            // żołnierz zostaje automatycznie oddelegowany z misji oraz usunięty ze szkolenia na których obecnie przebywał.
            if(in_array($this->id_status, array('2', '3', '4'))){
                
            }
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy zolnierz jest na misji
            // if(self::checkSoldierHasEquipment($this->id)){
                // $this->errors = "Do wyposażenia powiązani są żołnierze.";
                // return false;
            // }
            
            // sprawdzanie czy zolnierz jest na szkoleniu
            // if(self::checkSoldierHasEquipment($this->id)){
                // $this->errors = "Do wyposażenia powiązani są żołnierze.";
                // return false;
            // }
            
            return true;
        }
        
        
        // pobieranie danych gdy jest podane id
        // public function load(){
            // $this->getSoldier($this->id);
            // return;
        // }
        
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
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = '', array $custom_where = array()){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page, $controller_search, $custom_where))
            {
                foreach($sql as $key => $val)
                {
                    // Nazwa statusu
                    $sql[$key]['status_name'] = ClassSoldierStatus::sqlGetItemNameByIdParent($val['id_status']);
                }
            }
            
            return $sql;
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
    }
?>
