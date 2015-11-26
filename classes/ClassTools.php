<?php
    class ClassTools{
        
        // pobieranie wartosci z post i get
        public static function getValue($key){
            if (!isset($key) || empty($key) || !is_string($key)){
                return false;
            }
            
            $ret = (isset($_POST[$key]) ? $_POST[$key] : (isset($_GET[$key]) ? $_GET[$key] : false));

            if (is_string($ret) === true)
                $ret = urldecode(preg_replace('/((\%5C0+)|(\%00+))/i', '', urlencode($ret)));
            return !is_string($ret)? $ret : stripslashes($ret);
        }
        
        // pobieranie domeny
        public static function getDomainLink(){
            if(isset($_SERVER['HTTPS'])){
                $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
            }else{
                $protocol = 'http';
            }
            return $protocol . "://" . $_SERVER['SERVER_NAME'].'/';
        }
        
        // redirect do podstrony
        public static function redirect($url){

            if (strpos($url, 'http://') === false && strpos($url, 'https://') === false){
                $url = self::getDomainLink().$url;
            }

            header('Location: '.$url);
            exit;
        }
        
        // ladowanie podstawowego template - do usuniecia
        public static function loadTemplate($page_name, $values = false){
            if($page_name && file_exists($_SERVER['DOCUMENT_ROOT'].'/views/'.$page_name.'.tpl')){
                ob_start();
                    include_once 'views/partial/header.php';
                    include_once 'views/partial/top-nav.php';
                    include_once 'views/'.$page_name.'.tpl';
                    include_once 'views/partial/footer.php';
                    $content = ob_get_contents();
                ob_end_clean();
                return $content;
            }
            
            return false;
        }
        
        // zmiana \n, \r\n i \r na <br />
        public static function nl2br($str){
            return str_replace(array("\r\n", "\r", "\n"), '<br />', $str);
        }
        
        // ganarowanie random znakow
        public static function generateRandomPasswd($length = 15, array $types = array()){
            if(count($types) < 1){
                $types = array('1', '2', '3', '4');
            }
            
            $str = '';
            
            foreach($types as $type){
                switch($type){
                    case '1':
                        $str .= '0123456789';
                    break;
                    case '2':
                        $str .= 'abcdefghijklmnopqrstuvwxyz';
                    break;
                    case '3':
                        $str .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    break;
                    case '4':
                        $str .= '!@#$%^&*()[]{}\\|/,.<>?;:\'"';
                    break;
                }
            }
            
            
			return substr(str_shuffle($str), 0, $length);
		}
    }
?>
