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
        
        // generowanie klucza oraz jezeli zdefiniowane to rownierz guard key
        public static function userLogin($id_user, $guard = false){
            return self::sqlCreateUserLogin($id_user, $guard);
        }
        
        
        /* **************** SQL *************** */
        /* ************************************ */
        
        // generowanie klucza oraz jezeli zdefiniowane to rownierz guard key
        protected static function sqlCreateUserLogin($id_user, $guard){
            global $DB;
            
            $guard_key = false;
            
            // pobieranie id ip
            $user_guard = self::sqlGetUserIdIp($id_user, $guard);
            
            if(!$user_guard) // jezeli nie ma ip, doda go
            {
                $data_guard = array(
                    'id_user'    => $id_user,
                    'guard_key'  => $guard ? ClassTools::generateRandomPasswd(6, array('1', '2', '3')) : null,
                    'guard_ip'   => $_SERVER['REMOTE_ADDR'],
                    'date_add'   => date('Y-m-d H:i:s'),
                    'verified'   => $guard ? '0' : '1'
                );
                
                $user_guard = $data_guard;
                
                if($user_guard['guard_key'] !== null){
                    $guard_key = $user_guard['guard_key'];
                }
                
                if(!$user_guard['id_user_guard'] = $DB->insert('sew_user_guard', $data_guard)){
                    return false;
                }
            }
            elseif($user_guard && $guard && $user_guard['verified'] == '0') // jezeli jest ale nie zatwierdzony to wygeneruje nowy guard_key do wyslania przez mail
            {
                $where = "`id_user_guard` = '{$user_guard['id_user_guard']}'";
                $guard_key = ClassTools::generateRandomPasswd(6, array('1', '2', '3'));
                
                $DB->update('sew_user_guard', array('guard_key' => $guard_key), $where);
            }
            elseif($user_guard && !$guard && $user_guard['verified'] == '0') // jezeli jest ale nie zatwierdzony ale guard zostal wylaczony to ip zostanie aktywowane
            {
                $where = "`id_user_guard` = '{$user_guard['id_user_guard']}'";
                $DB->update('sew_user_guard', array('verified' => '1'), $where);
            }
            
            $data = array(
                'id_user'          => $id_user,
                'auth_key'         => ClassTools::generateRandomPasswd(40),
                'id_user_guard'    => $user_guard['id_user_guard'],
                'date_add'         => date('Y-m-d H:i:s')
            );
            
            if(!$id = $DB->insert('sew_user_login', $data)){
                return false;
            }
            
            $data['guard_key'] = $guard_key;
            
            return $data;
        }
        
        // pobieranie id ip
        protected static function sqlGetUserIdIp($id_user, $guard){
            global $DB;
            
            $ip = $_SERVER['REMOTE_ADDR'];
            
            $where = $guard ? 'AND `guard_key` IS NOT NULL' : '';
            
            $zapytanie = "SELECT `id_user_guard`, `verified`
                FROM `sew_user_guard`
                WHERE `id_user` = '{$id_user}'
                    AND `guard_ip` LIKE '{$ip}'
                    {$where}
            ;";
            
            $sql = $DB->pdo_fetch($zapytanie);
            
            if(!$sql || !is_array($sql) || count($sql) < 1){
                return false;
            }
            
            return $sql;
        }
    }
?>
