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
                        // return $this->getPageEdit();
                    break;
                    case 'podglad':
                        // ladowanie strony z podgladem misji
                        // return $this->getPageView();
                    break;
                }
            }
            
            return $this->getPageList();
        }
        
        // strona lista misjii
        protected function getPageList(){
            $this->actions();
            
            // strony
            $this->controller_name = 'uzytkownicy';
            $this->using_pages = true;
            $this->count_items = ClassUser::sqlGetCountItems();
            $this->current_page = ClassTools::getValue('page') ? ClassTools::getValue('page') : '1';
            // print_r($this->count_items);
            // tytul strony
            $this->tpl_title = 'Użytkownicy: Lista';
            
            // ladowanie funkcji
            // $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            // $this->tpl_values = ClassTraining::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page);
            
            // ladowanie strony z lista misji
            return $this->loadTemplate('/user/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            $id_current_type = false;
            
            // if(isset($_POST['form_type']) && $_POST['form_type'] != ''){
                // $id_current_type = $_POST['form_type'];
            // }
            
            // tytul strony
            $this->tpl_title = 'Użytkownicy: Dodaj';
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie rodzajow misjii
            // $this->tpl_values['form_type'] = ClassMission::getTypes($id_current_type);
            
            // ladowanie profili (uprawnien)
            $this->tpl_values['form_permissions'] = ClassUser::getPermissions();
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/user/formAdd');
        }
        
        // strona edycji
        protected function getPageEdit(){
            // zmienne wyswietlania na wypadek gdy strona z misja nie istnieje
            $this->tpl_values['wstecz'] = '/misje';
            $this->tpl_values['title'] = 'Edycja misji';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy i misji
            $mission = new ClassMission($id_item);
            
            // sprawdzanie czy misja zostala poprawnie zaladowana
            if(!$mission->load_class){
                $this->alerts['danger'] = 'Misja nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Misja: Edycja';
            
            // skrypty
            $this->load_datetimepicker = true;
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // rodzaje misji
            $this->tpl_values['form_type'] = ClassMission::getTypes((isset($_POST['form_type']) ? $_POST['form_type'] : $mission->id_mission_type));
            $this->tpl_values['sew_action'] = 'edit';
            
            // values
            // prypisanie zmiennych z wyslanego formularza, a jezeli nie istnieja przypisze pobrane z klasy
            $this->tpl_values['id_mission'] = $mission->id;
            $this->tpl_values['form_name'] = (isset($_POST['form_name']) ? $_POST['form_name'] : $mission->name);
            $this->tpl_values['form_location'] = (isset($_POST['form_location']) ? $_POST['form_location'] : $mission->location);
            $this->tpl_values['form_description'] = (isset($_POST['form_description']) ? $_POST['form_description'] : $mission->description);
            $this->tpl_values['form_date_start'] = (isset($_POST['form_date_start']) ? $_POST['form_date_start'] : ClassMission::getPlDate($mission->date_start));
            $this->tpl_values['form_date_end'] = (isset($_POST['form_date_end']) ? $_POST['form_date_end'] : ClassMission::getPlDate($mission->date_end));
            $this->tpl_values['form_active'] = (isset($_POST['form_active']) ? $_POST['form_active'] : $mission->active);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/mission/form');
        }
        
        // strona podgladu
        protected function getPageView(){
            // zmienne wyswietlania na wypadek gdy strona z misja nie istnieje
            $this->tpl_values['wstecz'] = '/misje';
            $this->tpl_values['title'] = 'Podgląd misji';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy i misji
            $mission = new ClassMission($id_item);
            
            // sprawdzanie czy misja zostala poprawnie zaladowana
            if(!$mission->load_class){
                $this->alerts['danger'] = 'Misja nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Misja: Podgląd';
            
            // skrypty
            $this->load_js_functions = true;
            
            // print_r($mission);
            
            // values
            $this->tpl_values['id_mission'] = $mission->id;
            $this->tpl_values['form_name'] = $mission->name;
            $this->tpl_values['form_location'] = $mission->location;
            $this->tpl_values['form_description'] = ClassTools::nl2br($mission->description);
            $this->tpl_values['form_date_start'] = ClassMission::getPlDate($mission->date_start);
            $this->tpl_values['form_date_end'] = ClassMission::getPlDate($mission->date_end);
            $this->tpl_values['form_active'] = $mission->active;
            $this->tpl_values['status'] = $mission->status;
            $this->tpl_values['type'] = $mission->mission_type_name;
            $this->tpl_values['date_update'] = $mission->date_update;
            
            $this->tpl_values['log'] = $mission->sqlGetLogItem();
            
            // print_r($this->tpl_values['log']);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/mission/view');
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
                case 'mission_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'mission_save':
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
            // ladowanie klasy i misji
            $mission = new ClassMission($_POST['id_mission']);
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($mission->load_class){
                // usuwanie misji
                if($mission->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto misję: <b>{$mission->name}</b>";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem misji
                    $this->alerts['danger'] = $mission->errors;
                }
            }
            
            $this->alerts['danger'] = 'Misja nie istnieje';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function edit(){
            // ladowanie klasy i misji
            $mission = new ClassMission($_POST['id_mission']);
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$mission->load_class){
                $this->alerts['danger'] = "Misja nie istnieje.";
            }
            
            // przypisanie zmiennych wyslanych z formularza do danych w klasie
            $mission->id_mission_type = $_POST['form_type'];
            $mission->name = $_POST['form_name'];
            $mission->location = $_POST['form_location'];
            $mission->description = $_POST['form_description'];
            // $mission->id_user = ClassAuth::getCurrentUserId();
            $mission->id_user = '999';
            $mission->date_start = $_POST['form_date_start'];
            $mission->date_end = $_POST['form_date_end'] != '' ? $_POST['form_date_end'] : NULL;
            $mission->active = (isset($_POST['form_active']) && $_POST['form_active'] == '1') ? '1' : '0';
            $mission->deleted = '0';
            
            // custom - dodatkowy warunek odnosnie dat
            // sprawdza, czy data rozpoczecia nie jest mniejsza niz data zakonczenia
            if($mission->date_end != NULL && ClassMission::validIsDateTime($mission->date_start) && ClassMission::validIsDateTime($mission->date_end)){
                $date_start = date('Y-m-d H:i:s', strtotime($mission->date_start));
                $date_end = date('Y-m-d H:i:s', strtotime($mission->date_end));
                
                if($date_start > $date_end){
                    $mission->errors[] = "Data rozpoczęcia misji jest większa od daty końca misji.";
                }
            }
            
            // komunikaty
            if(!$mission->update()){
                $this->alerts['danger'] = $mission->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano misję: <b>{$mission->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
