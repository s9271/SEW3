<?php
    class ClassSoldierModel extends ClassModel{
        protected static $use_prefix = true;
        protected static $has_deleted_column = true;
        
        // id zolnierza
        public $id_soldier;
        
        // usuniete
        public $deleted = 0;
        
        // id uzytkownika ktory dodal
        public $id_user_add = 0;
        
        // id uzytkownika ktory usunal
        public $id_user_delete = 0;
        
        // data dodania
        public $date_add;
        
        // data usuniecia
        public $date_delete;
        
        // opis podczas dodawania
        public $description_add = '';
        
        // opis podczas usuwania
        public $description_delete = '';
        
        // nazwa tabeli 1
        protected static $table1;
        
        // nazwa tabeli 2
        protected static $table2;
        
        // primary id
        protected static $primary;
        
        // primary id powiazany z id tabeli 1
        protected static $foreign_key;
        
        /* ************** FUNKCJE ************* */
        /* ************************************ */
        
        // pobieranie danych gdy jest podane id
        public function load(){
            return;
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
            
            $values = $this->getFieldsValidate();
            // print_r($values);
            
            if($this->errors && count($this->errors) > 0){
                return false;
            }
            
            if (!$id = $this->sqlAddTable1($values)){
                $this->errors[] = "Błąd zapisu do bazy (Tabela1).";
                return false;
            }
            
            $this->sqlAddTable2($id, $values);
            
            $this->id = $id;
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // dodawanie do bazy
        protected function sqlAddTable1($data){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$table1;
            
            $data_array = array();
            $data_array['id_soldier'] = $data['id_soldier'];
            $data_array['id_mission'] = $data['id_mission'];
            $data_array['deleted'] = $data['deleted'];
            
            return $DB->insert($table_name, $data_array);
        }
        
        // dodawanie do bazy
        protected function sqlAddTable2($id, $data){
            global $DB;
            $table_name = (static::$use_prefix ? static::$prefix : '').static::$table2;
            
            $data_array = array();
            $data_array['id_soldier2missions'] = $id;
            $data_array['id_user_add'] = $data['id_user_add'];
            $data_array['date_add'] = $data['date_add'];
            $data_array['description_add'] = $data['description_add'];
            
            return $DB->insert($table_name, $data_array);
        }
        
    }
?>
