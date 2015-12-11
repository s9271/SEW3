<?php

    require_once("libraries/password_compatibility_library.php");
    
    class ClassAuth{
        private static $cost = '12';
        
        // generowanie hasla
        public static function generatePassword($password){
            $options = array(
                'cost' => self::$cost
            );
            
            return password_hash($password, PASSWORD_BCRYPT, $options);
        }
        
        // sprawdzanie hasla
        public static function checkPassword($password, $hash){
            return password_verify($password, $hash);
        }
        
    }
?>