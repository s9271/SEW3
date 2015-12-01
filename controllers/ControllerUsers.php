<?php
    class ControllerUsers extends ControllerModel{
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
                        // ladowanie strony z formularzem
                        return $this->getPageEdit();
                    break;
                }
            }
            
            return $this->getPageList();
        }
        
        // strona lista uzytkownikow
        protected function getPageList(){
            $this->actions();
            
            // strony
            $this->controller_name = 'uzytkownicy';
            $this->using_pages = true;
            $this->items_on_page = 20;
            $this->count_items = ClassUser::sqlGetCountItems();
            $this->current_page = ClassTools::getValue('page') ? ClassTools::getValue('page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Użytkownicy: Lista';
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            // pobieranie wszystkich uzytkownikow
            $this->tpl_values['users'] = ClassUser::getAllItemsList($this->using_pages, $this->current_page, $this->items_on_page);
            
            // ladowanie strony z lista uzytkownikow
            return $this->loadTemplate('/user/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            $id_current_type = false;
            
            // tytul strony
            $this->tpl_title = 'Użytkownicy: Dodaj';
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie profili (uprawnien)
            $this->tpl_values['form_permissions'] = ClassUser::getPermissions();
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/user/formAdd');
        }
        
        // strona edycji
        protected function getPageEdit(){
            // zmienne wyswietlania na wypadek gdy strona z uzytkownikiem nie istnieje
            $this->tpl_values['wstecz'] = '/uzytkownicy';
            $this->tpl_values['title'] = 'Edycja użytkownika';
            
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
            $this->tpl_title = 'Użytkownicy: Edycja';
            
            // skrypty
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // values
            // prypisanie zmiennych z wyslanego formularza, a jezeli nie istnieja przypisze pobrane z klasy
            // print_r($user);
            $this->tpl_values['id_user'] = $user->id;
            
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
            
            // ladowanie profili (uprawnien)
            $this->tpl_values['form_permissions'] = ClassUser::getPermissions();
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/user/formEdit');
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
                case 'user_add':
                    return $this->add(); // dodawanie
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
            $user->id_military = $military == '0' ? null : $military;
            $user->active = ($active && $active == '1') ? '1' : '0';
            $user->guard = ($guard && $guard == '1') ? '1' : '0';
            
            $user->name = ClassTools::getValue('form_name');
            $user->surname = ClassTools::getValue('form_surname');
            $user->phone = ClassTools::getValue('form_phone');
            // $user->id_user_add = ClassAuth::getCurrentUserId();
            $user->id_user_add = '123';
            
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
            
            // $user->id_user_delete = ClassAuth::getCurrentUserId();
            $user->id_user_delete = '321';
            
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
            $user->id_military = $military == '0' ? null : $military;
            $user->active = ($active && $active == '1') ? '1' : '0';
            $user->guard = ($guard && $guard == '1') ? '1' : '0';
            
            $user->name = ClassTools::getValue('form_name');
            $user->surname = ClassTools::getValue('form_surname');
            $user->phone = ClassTools::getValue('form_phone');
            // $user->id_user_edit = ClassAuth::getCurrentUserId();
            $user->id_user_update = '1';
            
            // komunikaty
            if(!$user->update()){
                $this->alerts['danger'] = $user->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano użytkownika: <b>{$user->name} {$user->surname}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
