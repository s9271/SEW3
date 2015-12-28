<?php
    class ClassSearch{
        // czy klasa uzywa wyszukiwarki
        public static $is_search = false;
        
        // pobieranie where dla zapytania
        protected static function generateWhereList($controller, $prefix = false){
            if(!$session_search = self::getSearchSession($controller)){
                return false;
            }
            
            $prefix = $prefix ? $prefix.'.' : '';
            
            $first = true;
            
            foreach($session_search as $key => $val){
                $val = ClassTools::pSQL($val);
                
                if($first){
                    $first = false;
                    $search = "{$prefix}`{$key}` LIKE '%{$val}%'";
                }else{
                    $search .= " AND {$prefix}`{$key}` LIKE '%{$val}%'";
                }
            }
            
            return $search;
        }
        
        protected static function getSearchSession($controller){
            if(!isset($_SESSION['search']) || !isset($_SESSION['search'][$controller])){
                return false;
            }
            
            return $_SESSION['search'][$controller];
        }
    }
?>
