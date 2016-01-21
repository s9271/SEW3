<?php
    class ControllerUsers extends ControllerModel{
        protected $search_controller = 'users';
        protected $using_top_title = true;
        protected $top_ico = 'users';
        
        public function __construct(){
            $this->search_definition = $this->getSearchDefinition();
            
            $this->breadcroumb = array(
                array('name' => 'Użytkownicy', 'link' => '/uzytkownicy')
            );
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
                    case 'dodaj':
                        // ladowanie strony z formularzem
                        return $this->getPageAdd();
                    break;
                    case 'edytuj':
                        $page_action_option = ClassTools::getValue('page_action_option');
                        
                        if($page_action_option && $page_action_option == 'haslo'){
                            // ladowanie strony z edycji hasla
                            return $this->getPageEditPassword();
                        }else{
                            // ladowanie strony z formularzem
                            return $this->getPageEdit();
                        }
                    break;
                    case 'podglad':
                        // ladowanie strony z podgladem
                        return $this->getPageView();
                    break;
                }
            }
            
            return $this->getPageList();
        }
        
        // strona lista uzytkownikow
        protected function getPageList(){
            $this->searchActions();
            $this->actions();
            
            // strony
            $this->controller_name = 'uzytkownicy';
            $this->using_pages = true;
            // $this->items_on_page = 20;
            $this->count_items = ClassUser::sqlGetCountItems($this->search_controller);
            // print_r($this->count_items);
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Użytkownicy: Lista';
            
            // tylul na pasku
            $this->top_title = 'Lista użytkowników';
            
            // skrypty
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich uzytkownikow
            $this->tpl_values['users'] = ClassUser::getAllItemsList($this->using_pages, $this->current_page, $this->items_on_page, $this->search_controller);
            
            // ladowanie strony z lista uzytkownikow
            return $this->loadTemplate('/user/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            $id_current_type = false;
            
            // tytul strony
            $this->tpl_title = 'Użytkownicy: Dodaj';
            
            // tylul na pasku
            $this->top_title = 'Dodaj użytkownika';
            
            $this->breadcroumb[] = array('name' => 'Dodaj', 'link' => '/uzytkownicy/dodaj');
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie profili (uprawnien)
            $this->tpl_values['form_permissions'] = ClassUser::getPermissions();
            
            // ladowanie jednostek
            $this->tpl_values['form_militaries'] = ClassMilitary::getMilitariesWithGroups();
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/user/formAdd');
        }
        
        // strona edycji
        protected function getPageEdit()
        {
            // tylul na pasku
            $this->top_title = 'Edytuj użytkownika';
            
            // zmienne wyswietlania na wypadek gdy strona z uzytkownikiem nie istnieje
            $this->tpl_values['wstecz'] = '/uzytkownicy';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // ladowanie klasy i uzytkownika
            $user = new ClassUser(ClassTools::getValue('id_item'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$user->load_class){
                $this->alerts['danger'] = $user->errors;
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Użytkownicy: Edycja';
            
            // skrypty
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // values
            // prypisanie zmiennych z wyslanego formularza, a jezeli nie istnieja przypisze pobrane z klasy
            // print_r($user);
            $this->tpl_values['id_user'] = $user->id;
            
            $this->breadcroumb[] = array('name' => "{$user->name} {$user->surname}", 'link' => "/uzytkownicy/podglad/{$user->id}");
            $this->breadcroumb[] = array('name' => 'Edytuj', 'link' => "/uzytkownicy/edytuj/{$user->id}");
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'form_login' => $user->login,
                'form_name' => $user->name,
                'form_surname' => $user->surname,
                'form_mail' => $user->mail,
                'form_phone' => $user->phone,
                'form_permission' => $user->id_permission,
                'form_military' => ($user->id_military !== null ? $user->id_military : '0'),
                'form_active' => $user->active,
                'form_guard' => $user->guard
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            $this->actions();
            
            // ladowanie profili (uprawnien)
            $this->tpl_values['form_permissions'] = ClassUser::getPermissions();
            
            // ladowanie jednostek
            $this->tpl_values['form_militaries'] = ClassMilitary::getMilitariesWithGroups();
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/user/formEdit');
        }
        
        // ladowanie strony z edycji hasla
        protected function getPageEditPassword()
        {
            // tylul na pasku
            $this->top_title = 'Zmiana hasła';
            
            // zmienne wyswietlania na wypadek gdy strona z uzytkownikiem nie istnieje
            $this->tpl_values['wstecz'] = '/uzytkownicy';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // ladowanie klasy i uzytkownika
            $user = new ClassUser(ClassTools::getValue('id_item'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$user->load_class){
                $this->alerts['danger'] = $user->errors;
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // tytul
            $this->tpl_title = 'Użytkownicy: Zmiana hasła';
            
            // skrypty
            $this->load_js_functions = true;
            
            $this->breadcroumb[] = array('name' => "{$user->name} {$user->surname}", 'link' => "/uzytkownicy/podglad/{$user->id}");
            $this->breadcroumb[] = array('name' => 'Edytuj', 'link' => "/uzytkownicy/edytuj/{$user->id}");
            $this->breadcroumb[] = array('name' => 'Zmień hasło', 'link' => "/uzytkownicy/edytuj/{$user->id}/haslo");
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_user' => $user->id,
                'form_name' => $user->name,
                'form_surname' => $user->surname
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/user/password');
        }
        
        // strona podglądu
        protected function getPageView(){
            // tylul na pasku
            $this->top_title = 'Podgląd użytkownika';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = '/uzytkownicy';
            $this->tpl_values['wstecz'] = $wstecz;
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            $this->tpl_values['wstecz'] = $wstecz;
            
            // ladowanie klasy
            $user = new ClassUser($id_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$user->load_class){
                $this->alerts['danger'] = $user->errors;
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->breadcroumb[] = array('name' => "{$user->name} {$user->surname}", 'link' => "/uzytkownicy/podglad/{$user->id}");
            
            // tytul
            $this->tpl_title = 'Użytkownik: Podgląd';
            
            
            $this->tpl_values['form_active'] = $user->status_name;
            $this->tpl_values['form_guard'] = $user->guard_name;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_user'           => $user->id,
                'form_login'        => $user->login,
                'form_name'         => $user->name,
                'form_surname'      => $user->surname,
                'form_mail'         => $user->mail,
                'form_phone'        => $user->phone,
                'form_permission'   => $user->name_permission,
                'form_military'     => $user->military_name,
                // 'form_active'       => $user->status_name,
                // 'form_guard'        => $user->guard_name
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/user/view');
        }
        
        /* ************ WYSZUKIWARKA *********** */
        /* ************************************* */
        
        protected function getSearchDefinition(){
            $permissions = ClassUser::getPermissions();
            $form_permissions = array();
            
            foreach($permissions as $permission){
                $form_permissions[$permission['id_permission']] = $permission['name'];
            }
                
            $form_values = array(
                'class' => 'ClassUser',
                'controller' => $this->search_controller,
                // 'controller' => 'users',
                'form' => array(
                    'id_user' => array(
                        'class' => 'table_id',
                        'type' => 'text'
                    ),
                    'name' => array(
                        'class' => 'table_name',
                        'type' => 'text'
                    ),
                    'surname' => array(
                        'class' => 'table_surname',
                        'type' => 'text'
                    ),
                    'login' => array(
                        'class' => 'table_login',
                        'type' => 'text'
                    ),
                    'mail' => array(
                        'class' => 'table_mail',
                        'type' => 'text'
                    ),
                    'id_permission' => array(
                        'class' => 'table_permission',
                        'type' => 'select',
                        'options' => $form_permissions
                    ),
                    'active' => array(
                        'class' => 'table_status',
                        'type' => 'select',
                        'options' => array(
                            '0' => 'Wyłączony',
                            '1' => 'Włączony',
                        )
                    ),
                    'guard' => array(
                        'class' => 'table_guard',
                        'type' => 'select',
                        'options' => array(
                            '0' => 'Nieaktywny',
                            '1' => 'Aktywny',
                        )
                    ),
                    'actions' => array(
                        'class' => 'table_akcje'
                    )
                )
            );
            
            return $form_values;
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
                case 'user_add':
                    return $this->add(); // dodawanie
                break;
                case 'user_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'user_edit':
                    return $this->edit(); // edycja
                break;
                case 'user_password_update':
                    return $this->passwordUpdate(); // zmiana hasla
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add(){
            $active = ClassTools::getValue('form_active');
            $military = ClassTools::getValue('form_military');
            $guard = ClassTools::getValue('form_guard');
            
            $user = new ClassUser();
            $user->login = ClassTools::getValue('form_login');
            $user->mail = ClassTools::getValue('form_mail');
            $user->password = ClassTools::getValue('form_password');
            $user->id_permission = ClassTools::getValue('form_permission');
            $user->id_military = !$military || $military == '0' || $military == '' ? NULL : $military;
            // $user->id_military = $military == '0' ? null : $military;
            $user->active = ($active && $active == '1') ? '1' : '0';
            $user->guard = ($guard && $guard == '1') ? '1' : '0';
            
            $user->name = ClassTools::getValue('form_name');
            $user->surname = ClassTools::getValue('form_surname');
            $user->phone = ClassTools::getValue('form_phone');
            $user->id_user_add = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$user->add()){
                $this->alerts['danger'] = $user->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowego użytkownika: <b>{$user->name} {$user->surname}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy i uzytkownika
            $user = new ClassUser(ClassTools::getValue('id_user'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$user->load_class){
                $this->alerts['danger'] = $user->errors;
                return;
            }
            
            $user->id_user_delete = ClassAuth::getCurrentUserId();
            
            // usuwanie
            if(!$user->delete()){
                $this->alerts['danger'] = $user->errors;
                return;
            }
            
            $this->alerts['success'] = "Poprawnie usunięto użytkownika: <b>{$user->name} {$user->surname}</b>";
            return;
        }
        
        // edycja
        protected function edit(){
            // ladowanie klasy i uzytkownika
            $user = new ClassUser(ClassTools::getValue('id_user'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$user->load_class){
                $this->alerts['danger'] = $user->errors;
                return;
            }
            
            $active = ClassTools::getValue('form_active');
            $military = ClassTools::getValue('form_military');
            $guard = ClassTools::getValue('form_guard');
            
            $user->login = ClassTools::getValue('form_login');
            $user->mail = ClassTools::getValue('form_mail');
            $user->id_permission = ClassTools::getValue('form_permission');
            $user->id_military = !$military || $military == '0' || $military == '' ? NULL : $military;
            $user->active = ($active && $active == '1') ? '1' : '0';
            $user->guard = ($guard && $guard == '1') ? '1' : '0';
            
            $user->name = ClassTools::getValue('form_name');
            $user->surname = ClassTools::getValue('form_surname');
            $user->phone = ClassTools::getValue('form_phone');
            $user->id_user_edit = ClassAuth::getCurrentUserId();
            $user->id_user_update = ClassAuth::getCurrentUserId();
            
            // komunikaty
            if(!$user->update()){
                $this->alerts['danger'] = $user->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano użytkownika: <b>{$user->name} {$user->surname}</b>";
            
            // czyszczeie zmiennych wyswietlania
            // $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // zmiana hasla
        protected function passwordUpdate(){
            // ladowanie klasy i uzytkownika
            $user = new ClassUser(ClassTools::getValue('id_user'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$user->load_class){
                $this->alerts['danger'] = $user->errors;
                return;
            }
            
            // komunikaty
            if(!$user->passwordUpdate(ClassTools::getValue('form_new_password'), ClassTools::getValue('form_new_password_repeat'))){
                $this->alerts['danger'] = $user->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zmieniono hasło: <b>{$user->name} {$user->surname}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = array();
            $_POST = array();
            
            return;
        }
    }
?>
