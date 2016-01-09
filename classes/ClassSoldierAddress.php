<?php
    class ClassSoldierAddress extends ClassModel
    {
        public static $use_prefix = true;
        protected static $has_deleted_column = true;
        protected static $is_log = true;
        
        // id
        public $id = false;
        
        // id zolnierza
        public $id_soldier;
        
        // ulica
        public $street = '';
        
        // kod pocztowy
        public $postcode = '';
        
        // miasto
        public $city = '';
        
        // kraj
        public $country;
        
        // id typu zameldowania
        public $id_address_types;
        
        // Użytkownik
        public $id_user;
        
        // Użytkownik - zmienna trzymajaca zolnierza poprawnego, potrzebne do sprawdzania podczas usuwania
        public $id_soldier_tmp;
        
        // Data aktualizacji
        public $date_update;
        
        // Usunięty
        public $deleted = '0';
        
        // nazwa typu adresu
        public $address_type_name;
        
        // pobieranie typow adresow przypisanych do tego adresu
        public $soldier_address_types = array();
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'soldier_addresses',
            'primary' => 'id_address',
            'fields' => array(
                'id_soldier'        => array('required' => true, 'validate' => array('isInt'), 'name' => 'Id żołnierza'),
                'street'            => array('required' => true, 'name' => 'Ulica'),
                'postcode'          => array('name' => 'Kod pocztowy'),
                'city'              => array('required' => true, 'validate' => array('isNameSpaces'), 'name' => 'Miasto'),
                'country'           => array('required' => true, 'validate' => array('isNameSpaces'), 'name' => 'Kraj'),
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
                
                // pobieranie typow adresow przypisanych do tego adresu
                $this->soldier_address_types = self::sqlGetAddresses2TypesByAddres($this->id);
            }
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            // sprawdzenie czy wybrano typ
            if(!is_array($this->id_address_types) || count($this->id_address_types) < 1){
                $this->errors = "Proszę wybrać przynajmniej 1 typ.";
                return false;
            }
            
            // sprawdzanie czy wszystkie typy istnieja i sa aktywne
            $addres_types = ClassAddressType::sqlGetAllItemsNameById(NULL, false, true);
            foreach($this->id_address_types as $id_address_type)
            {
                if(!isset($addres_types[$id_address_type]))
                {
                    $this->errors = "Przynajmniej 1 typ nie istnieje lub jest wyłączony.";
                    return false;
                }
            }
            
            // sprawdzanie czy zolnierz nie chce posiadac wiecej typow adresu niz powinien
            if($soldier_address_types = self::sqlGetAllAddresses2TypesBySoldier($this->id_soldier))
            {
                foreach($this->id_address_types as $id_address_type)
                {
                    if(isset($soldier_address_types[$id_address_type]) && $soldier_address_types[$id_address_type]['max_addresses'] !== NULL && $soldier_address_types[$id_address_type]['max_addresses'] <= $soldier_address_types[$id_address_type]['count_types'])
                    {
                        $this->errors = "<b>{$soldier_address_types[$id_address_type]['name']}</b>: Nie można posiadać więcej tego typu adresu.";
                        return false;
                    }
                }
            }
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas aktualizowania
        public function updateCustomValidate(){
            // sprawdzanie czy id_zolnierza do ktorego jest przypisana szkola zgadza sie z tym do edycji
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Żołnierz nie posiada tego adresu.";
                return false;
            }
            
            // sprawdzenie czy wybrano typ
            if(!is_array($this->id_address_types) || count($this->id_address_types) < 1){
                $this->errors = "Proszę wybrać przynajmniej 1 typ.";
                return false;
            }
            
            // sprawdzanie czy wszystkie typy istnieja i sa aktywne
            $addres_types = ClassAddressType::sqlGetAllItemsNameById(NULL, false, true);
            foreach($this->id_address_types as $id_address_type)
            {
                if(!isset($addres_types[$id_address_type]))
                {
                    $this->errors = "Przynajmniej 1 typ nie istnieje lub jest wyłączony.";
                    return false;
                }
            }
            
            // sprawdzanie czy zolnierz nie chce posiadac wiecej typow adresu niz powinien
            if($soldier_address_types = self::sqlGetAllAddresses2TypesBySoldier($this->id_soldier))
            {
                // print_r($soldier_address_types);
                foreach($this->id_address_types as $id_address_type)
                {
                    if(isset($soldier_address_types[$id_address_type]) && !in_array($this->id, $soldier_address_types[$id_address_type]['id_addresses']) && $soldier_address_types[$id_address_type]['max_addresses'] !== NULL && $soldier_address_types[$id_address_type]['max_addresses'] <= $soldier_address_types[$id_address_type]['count_types'])
                    {
                        $this->errors = "<b>{$soldier_address_types[$id_address_type]['name']}</b>: Nie można posiadać więcej tego typu adresu.";
                        return false;
                    }
                }
            }
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy id_zolnierza do ktorego jest przypisany adres zgadza sie z tym do edycji
            if($this->id_soldier_tmp != $this->id_soldier){
                $this->errors = "Żołnierz nie posiada tego adresu.";
                return false;
            }
            
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        // sew_soldier_addresses_types
        // pobieranie wszystkich rekordow
        public static function sqlGetAllItems($using_pages = false, $current_page = '1', $items_on_page = '5', $controller_search = '', array $custom_where = array()){
            if($sql = parent::sqlGetAllItems($using_pages, $current_page, $items_on_page, $controller_search, $custom_where))
            {
                foreach($sql as $key => $val)
                {
                    // pobieranie typow adresow przypisanych do tego adresu
                    $sql[$key]['soldier_address_types'] = self::sqlGetAddresses2TypesByAddres($val['id_address']);
                }
            }
            
            return $sql;
        }
        
        // pobieranie powiazan adresow z adresami zolnierza
        public static function sqlGetAddresses2TypesByAddres($id_address){
            global $DB;
            
            $zapytanie = "SELECT sat.`id_address_type`, sat.`id_soldier`, sat.`id_address`, at.`name`, at.`max_addresses`
                FROM `sew_soldier_address_types` as sat, `sew_address_types` as at
                WHERE sat.`id_address` = {$id_address}
                    AND sat.`id_address_type` = at.`id_address_type`
                    AND at.`deleted` = '0'
            ;";
            
            $sql = $DB->pdo_fetch_all_group($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
        
        // pobieranie powiazan adresow ze wszystkimi adresami zolnierza
        public static function sqlGetAllAddresses2TypesBySoldier($id_soldier){
            global $DB;
            
            $zapytanie = "SELECT sat.`id_address_type`, sat.`id_address`, at.`name`, at.`max_addresses`
                FROM `sew_soldier_address_types` as sat, `sew_address_types` as at, `sew_soldier_addresses` as sa
                WHERE sat.`id_soldier` = {$id_soldier}
                    AND sat.`id_address_type` = at.`id_address_type`
                    AND sat.`id_address` = sa.`id_address`
                    AND at.`deleted` = '0'
                    AND sa.`deleted` = '0'
            ;";
            
            $sql = $DB->pdo_fetch_all($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            $new_sql = array();
            
            foreach($sql as $value)
            {
                if(isset($new_sql[$value['id_address_type']])){
                    $new_sql[$value['id_address_type']]['count_types']++;
                }else{
                    $new_sql[$value['id_address_type']] = $value;
                    $new_sql[$value['id_address_type']]['count_types'] = '1';
                }
                $new_sql[$value['id_address_type']]['id_addresses'][] = $value['id_address'];
            }
            
            return $new_sql;
        }
        
        // dodawanie do bazy
        protected function sqlAdd($table, $data){
            if(!$id = parent::sqlAdd($table, $data)){
                return false;
            }
            
            global $DB;
            
            foreach($this->id_address_types as $id_address_type){
                $DB->insert('sew_soldier_address_types', array('id_soldier' => $this->id_soldier, 'id_address' => $id, 'id_address_type' => $id_address_type));
            }
            
            return $id;
        }
        
        // aktualizacja w bazie
        protected function sqlUpdate($table, $data, $where){
            if(!parent::sqlUpdate($table, $data, $where)){
                return false;
            }
            
            global $DB;
            $DB->delete('sew_soldier_address_types', "`id_address` = '{$this->id}'", false);
            
            foreach($this->id_address_types as $id_address_type){
                $DB->insert('sew_soldier_address_types', array('id_soldier' => $this->id_soldier, 'id_address' => $this->id, 'id_address_type' => $id_address_type));
            }
            
            return true;
        }
    }
?>
