<?php
    class ClassMilitaryType extends ClassCategoryModel
    {
        public static $is_search = true;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'military_types',
            'primary' => 'id_military_type',
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
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy item jest powiazany z jakimas jednostka
            if(self::checkMilitaryHasItem($this->id)){
                $this->errors = "Do tego rodzaju powiązana jest jednostka wojskowa.";
                return false;
            }
            
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // sprawdzanie czy kategoria jest powiazana z jakims ekwipunkiem
        public static function checkMilitaryHasItem($id_military_type){
            global $DB;
            
            $zapytanie = "SELECT COUNT(*) as count_item
                FROM `sew_militaries`
                WHERE `deleted` = '0'
                    AND `id_military_type` = '{$id_military_type}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1 || $sql['count_item'] < 1){
                return false;
            }
            
            return true;
        }
    }
?>
