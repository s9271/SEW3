<?php
    class ControllerLogin extends ControllerModel{
        // trzymanie klasy login
        protected $login = false;
        
        public function __construct(){
            global $login;
            // $this->login = new ClassLogin();
            $this->login = $login;
        }
        
        // funkcja ktora jest pobierana w indexie, jest wymagana w kazdym kontrolerze!!!!!
        public function getContent(){
            return $this->getPage();
        }
        
        /* ************** STRONY ************* */
        /* *********************************** */
        
        // pobieranie strony
        protected function getPage(){
            // sprawdzanie czy jest sie na podstronie
            if($page_action = ClassTools::getValue('page_action')){
                switch($page_action){
                    case 'guard':
                        // ladowanie strony z formularzem do podania klucza
                        return $this->getPageGuard();
                    break;
                    case 'haslo':
                        // ladowanie strony z formularzem do zmiany hasla
                        return $this->getPagePassword();
                    break;
                    case 'logout':
                        // wylogowanie
                        return $this->prepareLogout();
                    break;
                }
            }
            
            return $this->getPageLogin();
        }
        
        // strona logowania
        protected function getPageLogin(){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Panel logowania';
            
            // ladowanie strony z formularzem logowania
            return $this->loadTemplate('/login/view');
        }
        
        // strona z formularzem na kod guard
        protected function getPageGuard(){
            // $this->login->getUserByAuthKey(true);
                
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Panel logowania - Guard';
            
            // login zalogowanego uzytkownika
            $this->tpl_values['login_name'] = $this->login->auth_user['login'];
            
            // ladowanie strony z formularzem na kod guard
            return $this->loadTemplate('/login/guard');
        }
        
        // strona z formularzem na nowe haslo
        protected function getPagePassword(){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Panel logowania - Przywracanie hasła';
            
            if(!$password_key = ClassTools::getValue('password_key'))
            {
                // ladowanie strony z formularzem na nowe haslo
                return $this->loadTemplate('/login/password');
            }
            
            // komunikaty bledu
            if(!$new_password = $this->login->sendNewPassword($password_key)){
                $this->alerts['danger'] = $this->login->errors;
            }else{
                $this->alerts['success'] = "Nowe hasło zostało wysłane na adres e-mail.";
                $this->sendMailNewPassword($this->login->auth_user->mail, $new_password);
            }
            
            // ladowanie strony z wysylka nowego hasla
            return $this->loadTemplate('/login/password_noticed');
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        protected function actions(){
            // sprawdzenie czy zostala wykonana jakas akcja zwiazana z formularzem
            if(!isset($_POST['form_action'])){
                return;
            }
            
            // print_r($_POST);
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $this->setValuesTemplateByPost();
            
            switch($_POST['form_action']){
                case 'login':
                    return $this->login(); // logowanie
                break;
                case 'logout':
                    return $this->logout(); // wylogowanie
                break;
                case 'guard':
                    return $this->guard(); // sprawdzanie klucza guard
                break;
                case 'guard_resend':
                    return $this->guardResend(); // wygenerowanie i wyslanie nowego klucza
                break;
                case 'new_password':
                    return $this->sendNewPasswordLink(); // wysylanie linka do generowania nowego hasla
                break;
            }
            
            return;
        }
        
        // wylogowanie;
        protected function prepareLogout(){
            global $login;
            $login->prepareLogout();
            exit;
        }
        
        // logowanie
        protected function login(){
            $login = new ClassLogin();
            $login->login = ClassTools::getValue('form_login');
            $login->password = ClassTools::getValue('form_password');
            
            // komunikaty bledu
            if(!$user = $login->login()){
                $this->alerts['danger'] = $login->errors;
                return;
            }
            
            $guard = ($user['guard'] == '1') ? true : false;
            
            if(!$auth = ClassAuth::userLogin($user['id_user'], $guard)){
                $this->alerts['danger'] = 'Błąd podczas zapisu sesji logowania do bazy.';
                return;
            }
            
            // przypisanie klucza logowania do sesji
            $_SESSION['user']['auth_key'] = $auth['auth_key'];
            
            if($auth['guard_key']){
                if($auth['guard_key'] !== true){
                    // wysylanie maila z kluczem do guarda
                    $this->sendMailGuardKey($user['mail'], $auth['guard_key'], $_SERVER['REMOTE_ADDR']);
                }
                
                // przejscie na strone z formularzem do wpisania klucza
                ClassTools::redirect('guard');
                exit;
            }
            
            // przejscie na strone glowna po poprawnym logowaniu
            ClassTools::redirect('');
            exit;
            
            return;
        }
        
        // wylogowanie
        protected function logout(){
            $this->login->logout();
            return;
        }
        
        // sprawdzanie klucza guard
        protected function guard(){
            $key = ClassTools::getValue('form_key');
            
            // komunikaty bledu
            if(!$this->login->insertGuard($key)){
                $this->alerts['danger'] = $this->login->errors;
                return;
            }
            
            return;
        }
        
        // sprawdzanie klucza guard
        protected function guardResend(){
            $key = ClassTools::getValue('form_key');
            
            // komunikaty bledu
            if(!$new_key = $this->login->guardResend()){
                $this->alerts['danger'] = $this->login->errors;
                return;
            }
            
            // wysylanie maila z nowym kluczem do guarda
            $this->sendMailGuardKey($this->login->auth_user['mail'], $new_key, $this->login->auth_user['guard_ip']);
            $this->alerts['success'] = "Wysłano nowy klucz guard na e-mail.";
            
            return;
        }
        
        // wysylanie linka do generowania nowego hasla
        protected function sendNewPasswordLink(){
            $this->login->login = ClassTools::getValue('form_login');
            
            // komunikaty bledu
            if(!$password_key = $this->login->sendNewPasswordLink()){
                $this->alerts['danger'] = $this->login->errors;
                return;
            }
            
            // wysylanie maila z linkiem do zmiany hasla
            $this->sendMailNewPasswordLink($this->login->auth_user['mail'], $password_key);
            $this->alerts['success'] = "Wysłano na adres e-mail instrukcje do zmiany hasła.";
            
            return;
        }
        
        /* *************** MAILS ************** */
        /* ************************************ */
        
        // wysylanie maila z kluczem do guarda
        protected function sendMailGuardKey($adres, $guard_key, $ip){
            // mail
            // $adres = "mariusz@nephax.com";
            
            // od kogo
            $from = "System Ewidencji Wojskowej <system@sew.org.pl>";
            
            // temat
            $temat = "Zabezpieczenie guard";
            
            // wysylka
            $boundary = "-->===_54654747_===<---->>4255==_";
            
            $head = 'From: '.$from."\n";
            $head .= "MIME-version: 1.0\n";
            $head .= "Content-type: multipart/mixed; ";
            $head .= "boundary=\"$boundary\"\n";
            $head .= "Content-Transfer-Encoding: 8bit\n";
            $head .= "charset=\"iso-8859-2\"\n";

            $tresc_maila = "--" . $boundary . "\n";
            $tresc_maila .= "Content-Type: text/plain; charset=\"iso-8859-2\"\n\n";
            $tresc_maila .= "Witam, wykryliśmy logowanie do panelu SEW z nowego ip: {$ip}\n\n";
            $tresc_maila .= "Proszę podać poniższy klucz aby dokończyć logowanie do systemu.\n\n";
            $tresc_maila .= "Klucz jest aktywny tylko przez ".ClassAuth::$guard_time." minut.\n\n";
            $tresc_maila .= "KLUCZ GUARD: {$guard_key}\n\n\n\n";
            $tresc_maila .= "Wiadomość została wygenerowana przez system, prosimy na niego nie odpowiadać.\n\n";
            $tresc_maila .= "--" . $boundary . "-- \n\n";
            
            $temat_iso = iconv('UTF-8', 'ISO-8859-2//TRANSLIT', $temat);
            $tresc_maila_iso = iconv('UTF-8', 'ISO-8859-2//TRANSLIT', $tresc_maila);
            $head_iso = iconv('UTF-8', 'ISO-8859-2//TRANSLIT', $head);
            
            mail($adres, $temat_iso, $tresc_maila_iso, $head_iso);
            // mail($adres, $temat, $tresc_maila, $head);
        }
        
        // wysylanie maila z linkiem do zmiany hasla
        protected function sendMailNewPasswordLink($adres, $password_key){
            // mail
            // $adres = "mariusz@nephax.com";
            
            // od kogo
            $from = "System Ewidencji Wojskowej <system@sew.org.pl>";
            
            // temat
            $temat = "Resetowanie hasła";
            
            // link
            $link = ClassTools::getDomainLink()."haslo/{$password_key}";
            
            // wysylka
            $boundary = "-->===_54654747_===<---->>4255==_";
            
            $head = 'From: '.$from."\n";
            $head .= "MIME-version: 1.0\n";
            $head .= "Content-type: multipart/mixed; ";
            $head .= "boundary=\"$boundary\"\n";
            $head .= "Content-Transfer-Encoding: 8bit\n";
            $head .= "charset=\"iso-8859-2\"\n";

            $tresc_maila = "--" . $boundary . "\n";
            $tresc_maila .= "Content-Type: text/plain; charset=\"iso-8859-2\"\n\n";
            $tresc_maila .= "Aby zresetować hasło proszę wejść na na poniższy link:\n\n";
            $tresc_maila .= '<a href="'.$link.'">'.$link.'</a>'."\n\n";
            $tresc_maila .= "Link aktywny jest tylko przez ".ClassAuth::$password_link_time." godzin.\n\n";
            $tresc_maila .= "Wiadomość została wygenerowana przez system, prosimy na niego nie odpowiadać.\n\n";
            $tresc_maila .= "--" . $boundary . "-- \n\n";
            
            $temat_iso = iconv('UTF-8', 'ISO-8859-2//TRANSLIT', $temat);
            $tresc_maila_iso = iconv('UTF-8', 'ISO-8859-2//TRANSLIT', $tresc_maila);
            $head_iso = iconv('UTF-8', 'ISO-8859-2//TRANSLIT', $head);
            
            mail($adres, $temat_iso, $tresc_maila_iso, $head_iso);
            // mail($adres, $temat, $tresc_maila, $head);
        }
        
        // wysylanie maila z linkiem do zmiany hasla
        protected function sendMailNewPassword($adres, $new_password){
            // od kogo
            $from = "System Ewidencji Wojskowej <system@sew.org.pl>";
            
            // temat
            $temat = "Nowe hasło";
            
            // wysylka
            $boundary = "-->===_54654747_===<---->>4255==_";
            
            $head = 'From: '.$from."\n";
            $head .= "MIME-version: 1.0\n";
            $head .= "Content-type: multipart/mixed; ";
            $head .= "boundary=\"$boundary\"\n";
            $head .= "Content-Transfer-Encoding: 8bit\n";
            $head .= "charset=\"iso-8859-2\"\n";

            $tresc_maila = "--" . $boundary . "\n";
            $tresc_maila .= "Content-Type: text/plain; charset=\"iso-8859-2\"\n\n";
            $tresc_maila .= "Nowe hasło do systemu: {$new_password}\n\n";
            $tresc_maila .= "Wiadomość została wygenerowana przez system, prosimy na niego nie odpowiadać.\n\n";
            $tresc_maila .= "--" . $boundary . "-- \n\n";
            
            $temat_iso = iconv('UTF-8', 'ISO-8859-2//TRANSLIT', $temat);
            $tresc_maila_iso = iconv('UTF-8', 'ISO-8859-2//TRANSLIT', $tresc_maila);
            $head_iso = iconv('UTF-8', 'ISO-8859-2//TRANSLIT', $head);
            
            mail($adres, $temat_iso, $tresc_maila_iso, $head_iso);
            // mail($adres, $temat, $tresc_maila, $head);
        }
        
        /* ************* OVERRIDE ************ */
        /* *********************************** */
        
        // ladowanie template
        protected function loadTemplate($page_name){
            if($page_name && file_exists($_SERVER['DOCUMENT_ROOT'].'/views/'.$page_name.'.tpl')){
                $this->loadScripts();
                
                ob_start();
                    include_once 'views/login/header.tpl';
                    include_once 'views/'.$page_name.'.tpl';
                    include_once 'views/partial/footer.php';
                    $content = ob_get_contents();
                ob_end_clean();
                return $content;
            }
            
            return false;
        }
    }
?>
