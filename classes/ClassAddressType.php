<?php
    class ClassAddressType extends ClassCategoryModel
    {
        // Maksymalna ilość adresow
        public $max_addresses;
        
        // walidacja, primary id, tabela i kolumny
        public static $definition = array(
            'table' => 'address_types',
            'primary' => 'id_address_type',
            'fields' => array(
                'id_parent'         => array('required' => true, 'validate' => array('isInt'), 'name' => 'Rodzic podkategorii'),
                'name'              => array('required' => true, 'name' => 'Nazwa'),
                'max_addresses'     => array('required' => true, 'validate' => array('isInt'), 'name' => 'Maksymalna ilość adresów'),
                'id_user'           => array('required' => true, 'validate' => array('isInt'), 'name' => 'Użytkownik'),
                'date_update'       => array('required' => true, 'validate' => array('isDateTime'), 'name' => 'Data aktualizacji'),
                'active'            => array('validate' => array('isBool'), 'name' => 'Aktywny'),
                'deleted'           => array('validate' => array('isBool'), 'name' => 'Usunięty'),
            ),
        );
        
    }
?>
