<?php
    $controllers = array();
    // $controllers['uzytkownicy'] = 'ControllerUsers';
    // $controllers['jednostki'] = 'ControllerMilitaries';
    // $controllers['zolnierze'] = 'ControllerSoldiers';
    // $controllers['wyposazenie'] = 'ControllerEquipments';
    // $controllers['misje'] = 'ControllerMissions';
    // $controllers['szkolenia'] = 'ControllerTrainings';
    // $controllers['odznaczenia'] = 'ControllerOrders';
    // $controllers['404'] = 'Controller404';
    // $controllers['login'] = 'ControllerLogin';
    
    
    $controllers['uzytkownicy']  = array('controller' => 'ControllerUsers', 'permissions' => array('1'));
    $controllers['jednostki']    = array('controller' => 'ControllerMilitaries', 'permissions' => array('1'));
    $controllers['zolnierze']    = array('controller' => 'ControllerSoldiers', 'permissions' => array('1', '2'));
    $controllers['wyposazenie']  = array('controller' => 'ControllerEquipments', 'permissions' => array('1', '2'));
    $controllers['misje']        = array('controller' => 'ControllerMissions', 'permissions' => array('1', '2'));
    $controllers['szkolenia']    = array('controller' => 'ControllerTrainings', 'permissions' => array('1', '2'));
    $controllers['odznaczenia']  = array('controller' => 'ControllerOrders', 'permissions' => array('1', '2'));
    $controllers['404']          = array('controller' => 'Controller404', 'permissions' => array('1', '2'));
    $controllers['login']        = array('controller' => 'ControllerLogin');
?>
