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
    
    
    $controllers['uzytkownicy']      = array('controller' => 'ControllerUsers', 'permissions' => array('1'));
    $controllers['jednostki']        = array('controller' => 'ControllerMilitaries', 'permissions' => array('1'));
    $controllers['zolnierze']        = array('controller' => 'ControllerSoldiers', 'permissions' => array('1', '2'), 'childrens' => array(
        'stopien'       => array('controller' => 'ControllerSoldiers', 'permissions' => array('1', '2')),
        'wyposazenie'   => array('controller' => 'ControllerSoldiers', 'permissions' => array('1', '2')),
        'misje'         => array('controller' => 'ControllerSoldiers', 'permissions' => array('1', '2')),
        'szkolenia'     => array('controller' => 'ControllerSoldiers', 'permissions' => array('1', '2')),
        'odznaczenia'   => array('controller' => 'ControllerSoldiers', 'permissions' => array('1', '2')),
        'szkoly-wyzsze' => array('controller' => 'ControllerSoldierSchools', 'permissions' => array('1', '2')),
        'jezyki'        => array('controller' => 'ControllerSoldierLanguages', 'permissions' => array('1', '2')),
        'prawo-jazdy'   => array('controller' => 'ControllerSoldierDriveLicenses', 'permissions' => array('1', '2')),
        'dzieci'        => array('controller' => 'ControllerSoldierChildrens', 'permissions' => array('1', '2')),
        'adresy'        => array('controller' => 'ControllerSoldiers', 'permissions' => array('1', '2')),
    ));
    $controllers['wyposazenie']      = array('controller' => 'ControllerEquipments', 'permissions' => array('1', '2'));
    $controllers['misje']            = array('controller' => 'ControllerMissions', 'permissions' => array('1', '2'));
    $controllers['szkolenia']        = array('controller' => 'ControllerTrainings', 'permissions' => array('1', '2'));
    $controllers['odznaczenia']      = array('controller' => 'ControllerBadges', 'permissions' => array('1'));
    $controllers['404']              = array('controller' => 'Controller404', 'permissions' => array('1', '2'));
    $controllers['centra-szkolen']   = array('controller' => 'ControllerTrainingCenters', 'permissions' => array('1', '2'));
    $controllers['typy-wyposazenia'] = array('controller' => 'ControllerEquipmentTypes', 'permissions' => array('1', '2'));
    $controllers['login']            = array('controller' => 'ControllerLogin');
?>
