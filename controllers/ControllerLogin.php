<?php
    class ControllerLogin extends ControllerModel{
        // funkcja ktora jest pobierana w indexie, jest wymagana w kazdym kontrolerze!!!!!
        public function getContent(){
            return $this->getPage();
        }
        
        /* ************** STRONY ************* */
        /* *********************************** */
        
        // pobieranie strony
        protected function getPage(){
            // sprawdzanie czy jest sie na podstronie
            /* if($page_action = ClassTools::getValue('page_action')){
                switch($page_action){
                    case 'dodaj':
                        // ladowanie strony z formularzem
                        return $this->getPageAdd();
                    break;
                    case 'edytuj':
                        // ladowanie strony z formularzem
                        return $this->getPageEdit();
                    break;
                    case 'podglad':
                        // ladowanie strony z podgladem misji
                        return $this->getPageView();
                    break;
                }
            } */
            
            return $this->getPageLogin();
        }
        
        // strona logowania
        protected function getPageLogin(){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Panel logowania';
            
            // ladowanie funkcji
            // $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            // $this->tpl_values = ClassMission::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page);
            
            // ladowanie strony z lista misji
            return $this->loadTemplate('/login/view');
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        protected function actions(){
            // sprawdzenie czy zostala wykonana jakas akcja zwiazana z formularzem
            if(!isset($_POST['form_action'])){
                return;
            }
            
            print_r($_POST);
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $this->setValuesTemplateByPost();
            
            switch($_POST['form_action']){
                case 'login':
                    return $this->login(); // logowanie
                break;
                case 'user_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'user_edit':
                    return $this->edit(); // edycja
                break;
            }
            
            return;
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
                // wysylanie maila z kluczem do guarda
                // $this->sendMailGuardKey($user['mail'], $auth['guard_key'], $_SERVER['REMOTE_ADDR']);
                
                // przejscie na strone z formularzem do wpisania klucza
                ClassTools::redirect('guard');
                exit;
            }
            
            // przejscie na strone glowna po poprawnym logowaniu
            ClassTools::redirect('');
            exit;
            
            return;
        }
        
        /* *************** MAILS ************** */
        /* ************************************ */
        
        // wysylanie maila z kluczem do guarda
        protected function sendMailGuardKey($adres, $guard_key, $ip){
            // mail
            $adres = "mariusz@nephax.com";
            
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
            $tresc_maila .= "KLUCZ GUARD: {$guard_key}\n\n\n\n";
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
