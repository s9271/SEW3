<?php
    class ClassBadgeType extends ClassCategoryModel
    {
        public static $is_search = true;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'badge_types',
            'primary' => 'id_badge_type',
            'fields' => array(
                'id_parent'         => array('required' => true, 'validate' => array('isInt'), 'name' => 'Rodzic podkategorii'),
                'name'              => array('required' => true, 'name' => 'Nazwa'),
                'id_user'           => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'       => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'active'            => array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted'           => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
        // dodatkowe wlasne walidacje podczas usuwania
        public function deleteCustomValidate()
        {
            // sprawdzanie czy item jest powiazany z jakimas jednostka
            if(self::checkBadgeHasItem($this->id)){
                $this->errors = "Do tego rodzaju powiązana jest odznaka.";
                return false;
            }
            
            return true;
        }
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // sprawdzanie czy kategoria jest powiazana z jakims ekwipunkiem
        public static function checkBadgeHasItem($id_badge_type){
            global $DB;
            
            $zapytanie = "SELECT COUNT(*) as count_item
                FROM `sew_badges`
                WHERE `deleted` = '0'
                    AND `id_badge_type` = '{$id_badge_type}'
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1 || $sql['count_item'] < 1){
                return false;
            }
            
            return true;
        }
    }
?>
