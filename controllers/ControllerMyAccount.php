<?php
    class ControllerMyAccount extends ControllerModel{
        protected $using_top_title = true;
        protected $top_ico = 'user';
        protected $top_help_button = true;
        protected $top_help_file = 'my-account';
        
        public function __construct(){
            $this->breadcroumb = array(
                array('name' => 'Moje konto', 'link' => '/moje-konto')
            );
        }
        
        // funkcja ktora jest pobierana w indexie, jest wymagana w kazdym kontrolerze!!!!!
        public function getContent(){
            return $this->getPage();
        }
        
        /* ************** STRONY ************* */
        /* *********************************** */
        
        // pobieranie strony
        protected function getPage()
        {
            // sprawdzanie czy jest sie na podstronie
            if($page_action = ClassTools::getValue('page_action_password')){
                switch($page_action){
                    case 'haslo':
                        // ladowanie strony z formularzem
                        return $this->getPageEditPassword();
                    break;
                }
            }
            
            return $this->getPageEdit();
        }
        
        // strona lista
        protected function getPageEdit(){
            global $login;
            
            // tylul na pasku
            $this->top_title = 'Moje konto';
            
            $this->actions();
            
            // ladowanie klasy i uzytkownika
            $user = new ClassUser($login->auth_user['id_user']);
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$user->load_class){
                $this->alerts['danger'] = $user->errors;
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($user->active != '1'){
                $this->alerts['danger'] = 'Użytkownik jest wyłączony';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($user->deleted == '1'){
                $this->alerts['danger'] = 'Użytkownik nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tylul na pasku
            $this->top_title = "{$user->name} {$user->surname}";
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            $this->tpl_values['form_login'] = $user->login;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_user'       => $user->id,
                // 'form_login'    => $user->login,
                'form_name'     => $user->name,
                'form_surname'  => $user->surname,
                'form_mail'     => $user->mail,
                'form_phone'    => $user->phone,
                'form_guard'    => $user->guard
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z lista
            return $this->loadTemplate('/user/my-account');
        }
        
        // ladowanie strony z edycji hasla
        protected function getPageEditPassword()
        {
            global $login;
            
            // tylul na pasku
            $this->top_title = 'Zmiana hasła';
            
            $this->actions();
            
            // ladowanie klasy i uzytkownika
            $user = new ClassUser($login->auth_user['id_user']);
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$user->load_class){
                $this->alerts['danger'] = $user->errors;
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($user->active != '1'){
                $this->alerts['danger'] = 'Użytkownik jest wyłączony';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($user->deleted == '1'){
                $this->alerts['danger'] = 'Użytkownik nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // skrypty
            $this->load_js_functions = true;
            
            $this->breadcroumb[] = array('name' => 'Zmień hasło', 'link' => "/moje-konto/haslo");
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_user'                   => $user->id,
                'form_new_password'         => '',
                'form_new_password_repeat'  => ''
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/user/my-account-password');
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
                case 'user_edit':
                    return $this->edit(); // zapis
                break;
                case 'user_password_update':
                    return $this->editPassword(); // edycja hasla
                break;
            }
            
            return;
        }
        
        // zapis
        protected function edit()
        {
            global $login;
            
            // sprawdza czy uzytkownik nie zmienil sb id podczas edycji
            if(ClassTools::getValue('id_user') != $login->auth_user['id_user']){
                $this->alerts['danger'] = 'Niepoprawny użytkownik';
                return false;
            }
            
            $guard = ClassTools::getValue('form_guard');
            
            $item = new ClassUser(ClassTools::getValue('id_user'));
            $item->mail = ClassTools::getValue('form_mail');
            $item->name = ClassTools::getValue('form_name');
            $item->surname = ClassTools::getValue('form_surname');
            $item->phone = ClassTools::getValue('form_phone');
            $item->guard = ($guard && $guard == '1') ? '1' : '0';
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->myAccountEdit()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie zaktualizowano użytkownika.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // zapis
        protected function editPassword()
        {
            global $login;
            
            // sprawdza czy uzytkownik nie zmienil sb id podczas edycji
            if(ClassTools::getValue('id_user') != $login->auth_user['id_user']){
                $this->alerts['danger'] = 'Niepoprawny użytkownik';
                return false;
            }
            
            $item = new ClassUser(ClassTools::getValue('id_user'));
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->myAccountEditPassword(ClassTools::getValue('form_new_password'), ClassTools::getValue('form_new_password_repeat'))){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie zmieniono hasło.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
