<?php
    class ClassEquipmentType extends ClassCategoryModel
    {
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'equipment_types',
            'primary' => 'id_equipment_type',
            'fields' => array(
                'id_parent'         => array('required' => true, 'validate' => array('isInt'), 'name' => 'Rodzic podkategorii'),
                'name'              => array('required' => true, 'name' => 'Nazwa'),
                'id_user'           => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'       => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'active'            => array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted'           => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // pobieranie listy typow
        public static function getEquipmentTypes()
        {
            // pobieranie glownych kategorii
            if(!$parents = self::sqlGetAllItemsById(NULL, false, true)){
                return false;
            }
            
            $array = array();
            
            foreach($parents as $parent){
                $array[$parent['id_equipment_type']]['name'] = $parent['name'];
                
                if($childs = self::sqlGetAllItemsById($parent['id_equipment_type'], false, true))
                {
                    foreach($childs as $child){
                        $array[$parent['id_equipment_type']]['childs'][$child['id_equipment_type']] = $child['name'];
                    }
                }
            }
            
            return $array;
        }
        
        // dodatkowe wlasne walidacje podczas dodawania
        public function addCustomValidate()
        {
            // sprawdzanie czy parent istnieje
            if($this->id_parent !== NULL && !self::sqlCategoryItemExists($this->id_parent, true)){
                $this->errors[] = "Kategoria wybrana z listy nie istnieje.";
                return false;
            }
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas aktualizowania
        public function updateCustomValidate(){
            if(!$this->addCustomValidate()){
                return false;
            }
            
            // sprawdzanie czy kategoria zapisywana nie jest zapisywana do niej samej
            if($this->id_parent == $this->id){
                $this->errors[] = "Nie można powiązać typu ze samym sobą.";
                return false;
            }
            
            // sprawdzanie czy kategoria ma jakies dziecii i czy glownej kategorii nie chce sie powiazac z inna kategorie glowna
            $item = new ClassEquipmentType($this->id);
            if($item->id_parent === NULL && $this->id_parent !== NULL && self::checkParentHasChilds($this->id)){
                $this->errors[] = "Głównego typu wyposażenia nie może zmienić na inny typ gdy posiada podkategorie.";
                return false;
            }
            
            // podczas zmiany podkategori na kategorie glowna
            // sprawdza czy kategoria jest powiazana z jakims ekwipunkiem
            if($item->id_parent !== NULL && $this->id_parent === NULL && self::checkEquipmentHasItem($this->id)){
                $this->errors[] = "Aby zmienić podkategorię na kategorię główną trzeba wpierw usunąć ją z powiązanym wyposażeniem.";
                return false;
            }
            
            return true;
        }
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy kategoria jest glowna i posiada dzieci
            if($this->id_parent === NULL && self::checkParentHasChilds($this->id)){
                $this->errors = "Do typu wyposażenia powiązane są podkategorie.";
                return false;
            }
            
            // sprawdzanie czy kategoria nie jest glowna i czy jest powiazana z jakims ekwipunkiem
            if($this->id_parent !== NULL && self::checkEquipmentHasItem($this->id)){
                $this->errors = "Do typu wyposażenia powiązane jest wyposażenie.";
                return false;
            }
            
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // sprawdzanie czy kategoria jest powiazana z jakims ekwipunkiem
        public static function checkEquipmentHasItem($id_equipment_type){
            global $DB;
            
            $zapytanie = "SELECT COUNT(*) as count_item
                FROM `sew_equipments`
                WHERE `deleted` = '0'
                    AND `id_equipment_type` = '{$id_equipment_type}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1 || $sql['count_item'] < 1){
                return false;
            }
            
            return true;
        }
    }
?>
