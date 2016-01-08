<?php
    class ClassEducationType extends ClassCategoryModel
    {
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'education_types',
            'primary' => 'id_education_type',
            'fields' => array(
                'id_parent'         => array('required' => true, 'validate' => array('isInt'), 'name' => 'Rodzic podkategorii'),
                'name'              => array('required' => true, 'name' => 'Nazwa'),
                'id_user'           => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'       => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'active'            => array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted'           => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
    }
?>
