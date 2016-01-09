<?php
    class ClassDriveCategories extends ClassCategoryModel
    {
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'drive_categories',
            'primary' => 'id_drive_category',
            'fields' => array(
                'id_parent'         => array('required' => true, 'validate' => array('isInt'), 'name' => 'Rodzic podkategorii'),
                'name'              => array('required' => true, 'name' => 'Nazwa'),
                'id_user'           => array('required' => true, 'validate' => array('isInt'), 'name' => 'U¿ytkownik'),
                'date_update'       => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'active'            => array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted'           => array('validate' => array('isBool'), 'name' => 'Usuniêty'),
            ),
        );
        
    }
?>
