<?php
    class ControllerSoldiers extends ControllerModel{
        public function __construct(){
            // potrzebne do ajaxa i stronnicowania
            $this->controller_name = 'zolnierze';
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
                        // return $this->getPageAdd();
                    break;
                    case 'edytuj':
                        // ladowanie strony z formularzem
                        // return $this->getPageEdit();
                    break;
                    case 'podglad':
                        // ladowanie strony z podgladem misji
                        // return $this->getPageView();
                    break;
                    case 'wyposazenie':
                        // ladowanie strony z wyposazeniem
                        // return $this->getPageView();
                    break;
                    case 'misje':
                        // ladowanie strony z misjami
                        return $this->getPageMissions();
                    break;
                    case 'szkolenia':
                        // ladowanie strony ze szkoleniami
                        // return $this->getPageView();
                    break;
                    case 'odznaczenia':
                        // ladowanie strony z odznaczeniami
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
            $this->controller_name = 'szkolenia';
            $this->using_pages = true;
            $this->count_items = ClassTraining::sqlGetCountItems();
            $this->current_page = ClassTools::getValue('page') ? ClassTools::getValue('page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Szkolenia: Lista';
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values = ClassTraining::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page);
            
            // ladowanie strony z lista misji
            return $this->loadTemplate('/training/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            $id_current_type = false;
            
            // if(isset($_POST['form_type']) && $_POST['form_type'] != ''){
                // $id_current_type = $_POST['form_type'];
            // }
            
            // tytul strony
            $this->tpl_title = 'Szkolenie: Dodaj';
            
            // ladowanie pluginow
            $this->load_datetimepicker = true;
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie rodzajow misjii
            // $this->tpl_values['form_type'] = ClassMission::getTypes($id_current_type);
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/training/form');
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
        
        /* *** MISJE *** */
        /* ************* */
        
        // pobieranie strony misji
        protected function getPageMissions(){
            // zmienne wyswietlania na wypadek gdy strona z misja nie istnieje
            $this->tpl_values['wstecz'] = '/soldier.php?action=list';
            $this->tpl_values['title'] = 'Misje żołnierza';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_soldier = ClassTools::getValue('id_soldier')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // ladowanie klasy i misji
            $soldier = new ClassSoldier($id_soldier);
            
            // sprawdzanie czy misja zostala poprawnie zaladowana
            if(!$soldier->load_class){
                $this->alerts['danger'] = 'Żołnierz nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // print_r($_GET);
            // sprawdzanie czy jest sie na podstronie
            if($page_action_option = ClassTools::getValue('page_action_option')){
                // zmienne wyswietlania na wypadek gdy strona z misja nie istnieje
                $this->tpl_values['wstecz'] = "/zolnierze/{$id_soldier}/misje";
                $this->tpl_values['title'] = 'Misjia żołnierza';
                
                // sprawdzanie czy id misji istnieje w linku
                if(!$id_mission = ClassTools::getValue('page_action_id')){
                    $this->alerts['danger'] = 'Brak podanego id';
                    
                    // ladowanie strony do wyswietlania bledow
                    // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                    return $this->loadTemplate('alert');
                }
                
                // ladowanie klasy i misji
                $mission = new ClassMission($id_mission);
                
                // sprawdzanie czy misja zostala poprawnie zaladowana
                if(!$mission->load_class){
                    $this->alerts['danger'] = 'Misja nie istnieje';
                    
                    // ladowanie strony do wyswietlania bledow
                    // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                    return $this->loadTemplate('alert');
                }
                
                // sprawdzanie czy misja jest powiazana z zolnierzem
                if(!ClassSoldier2Missions::sqlCheckSoldierToMission($soldier->id, $mission->id, ClassTools::getValue('page_action_option_id'))){
                    $this->alerts['danger'] = 'Misja nie jest powiązana z tym żołnierzem';
                    
                    // ladowanie strony do wyswietlania bledow
                    // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                    return $this->loadTemplate('alert');
                }
                
                switch($page_action_option){
                    case 'podglad':
                        // ladowanie strony z podgladem misji
                        return $this->getPageMissionsView($soldier, $mission);
                    break;
                    case 'oddeleguj':
                        // ladowanie strony do odlegowania zolnierza
                        return $this->getPageMissionsSeconded($soldier, $mission);
                    break;
                }
            }
            
            return $this->getPageMissionsList($soldier);
        }
        
        // strona z lista misjii zolnierza
        protected function getPageMissionsList($soldier){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Misje żołnierza';
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['table_list'] = ClassSoldier2Missions::getSoldierMissions($soldier->id, $this->using_pages, $this->current_page, $this->items_on_page);
            
            // nazwa klasy i funkcji z ktorej bedzie pobierac opcje do selekta (w klasie musi istniec statyczna funkcja do obslugi tego ajaxa)
            $this->tpl_values['ajax_class'] = 'Soldier2Missions';
            $this->tpl_values['ajax_function'] = 'sqlSearchMissionForSoldier';
            
            // imie i nazwisko
            $this->tpl_values['name'] = $soldier->soldierName.' '.$soldier->soldierSurname;
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $soldier->id;
            
            // ladowanie strony z lista misji
            return $this->loadTemplate('/soldier/missions');
        }
        
        // strona z podgladem misji zolnierza
        protected function getPageMissionsView($soldier, $mission){
            $this->actions();
            // page_action_option_id
            // tytul strony
            $this->tpl_title = 'Podgląd misji żołnierza';
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            // pobieranie misji
            if(!$soldier_mission = ClassSoldier2Missions::getSoldierMission(ClassTools::getValue('page_action_option_id'), $soldier->id, $mission->id, $mission->date_end, $mission->active)){
                $this->alerts['danger'] = 'Problem pobierania misji powiazanej do zolnierza';
                return $this->loadTemplate('alert');
            }
            
            // zmienne przydzialu
            $this->tpl_values['s2m_date_add'] = $soldier_mission['date_add'];
            $this->tpl_values['s2m_user_add'] = $soldier_mission['user_name'];
            $this->tpl_values['s2m_description_add'] = ClassTools::nl2br($soldier_mission['description_add']);
            $this->tpl_values['s2m_status'] = $soldier_mission['status'];
            $this->tpl_values['s2m_deleted'] = $soldier_mission['deleted'];
            $this->tpl_values['id_soldier2missions'] = $soldier_mission['id_soldier2missions'];
            
            if($soldier_mission['deleted'] == '1'){
                $this->tpl_values['s2m_date_delete'] = $soldier_mission['date_delete'];
                $this->tpl_values['s2m_user_delete'] = $soldier_mission['user_name_delete'];
                $this->tpl_values['s2m_description_delete'] = ClassTools::nl2br($soldier_mission['description_delete']);
            }
            
            // imie i nazwisko
            $this->tpl_values['name'] = "Podgląd misji <b>{$mission->name}</b> żołnierza <b>{$soldier->soldierName} {$soldier->soldierSurname}</b>";
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $soldier->id;
            $this->tpl_values['id_mission'] = $mission->id;
            
            // misja
            $this->tpl_values['id_mission'] = $mission->id;
            $this->tpl_values['form_name'] = $mission->name;
            $this->tpl_values['form_location'] = $mission->location;
            $this->tpl_values['form_description'] = ClassTools::nl2br($mission->description);
            $this->tpl_values['form_date_start'] = ClassMission::getPlDate($mission->date_start);
            $this->tpl_values['form_date_end'] = ClassMission::getPlDate($mission->date_end);
            $this->tpl_values['form_active'] = $mission->active;
            $this->tpl_values['type'] = $mission->mission_type_name;
            $this->tpl_values['date_update'] = $mission->date_update;
            
            // ladowanie strony z lista misji
            return $this->loadTemplate('/soldier/missionView');
        }
        
        // ladowanie strony do odlegowania zolnierza
        protected function getPageMissionsSeconded($soldier, $mission){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Oddelegowanie żołnierza';
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            // pobieranie misji
            if(!$soldier_mission = ClassSoldier2Missions::getSoldierMission(ClassTools::getValue('page_action_option_id'), $soldier->id, $mission->id, $mission->date_end, $mission->active)){
                $this->alerts['danger'] = 'Problem pobierania misji powiazanej do zolnierza';
                return $this->loadTemplate('alert');
            }
            
            // print_r($soldier_mission);
            
            $this->tpl_values['s2m_deleted'] = $soldier_mission['deleted'];
            
            if($soldier_mission['deleted'] == '1'){
                $this->alerts['danger'] = 'Zołnierz jest już oddelegowany od tej misji.';
            }
            
            // imie i nazwisko
            $this->tpl_values['name'] = "Oddelegowanie żołnierza <b>{$soldier->soldierName} {$soldier->soldierSurname}</b> z misji <b>{$mission->name}</b>";
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $soldier->id;
            $this->tpl_values['id_mission'] = $mission->id;
            $this->tpl_values['id_soldier2missions'] = ClassTools::getValue('page_action_option_id');
            
            // ladowanie strony z lista misji
            return $this->loadTemplate('/soldier/missionSeconded');
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
            $this->tpl_values = $_POST;
            
            switch($_POST['form_action']){
                case 'mission_add':
                    return $this->add(); // dodawanie
                break;
                case 'mission_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'mission_save':
                    return $this->edit(); // edycja
                break;
                case 'add_mission':
                    return $this->addMissionToSoldier(); // powiazanie zolnierza z misja
                break;
                case 'soldier_mission_delete':
                    return $this->deleteMissionFromSoldier(); // usuwanie misji z zolnierza
                break;
                case 'soldier_mission_seconded':
                    return $this->secondedMissionFromSoldier(); // oddelegowywanie zolnierza od misji
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add(){
            $mission = new ClassMission();
            $mission->id_mission_type = $_POST['form_type'];
            $mission->name = $_POST['form_name'];
            $mission->location = $_POST['form_location'];
            $mission->description = $_POST['form_description'];
            // $mission->id_user = ClassAuth::getCurrentUserId();
            $mission->id_user = '1';
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
            
            // komunikaty bledu
            if(!$mission->add()){
                $this->alerts['danger'] = $mission->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nową misję: <b>{$mission->name}</b>";
            
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
        
        
        /* *** MISJE *** */
        /* ************* */
        
        // powiazanie zolnierza z misja
        protected function addMissionToSoldier(){
            $id_soldier = ClassTools::getValue('id_soldier');
            $id_mission = ClassTools::getValue('add_form_list_id');
            
            // sprawdzanie czy zolnierz istnieje
            if(!ClassSoldier::isSoldier($id_soldier)){
                $this->alerts['danger'] = "Żołnierz nie istnieje.";
                return;
            }
            
            // sprawdzanie czy misja istnieje
            if(!ClassMission::isMission($id_mission)){
                $this->alerts['danger'] = "Misja nie istnieje.";
                return;
            }
        
            // sprawdzanie czy zolnierz posiada dana misje
            if(ClassSoldier2Missions::soldierHasMission($id_soldier, $id_mission)){
                $this->alerts['danger'] = "Żołnierz posiada już tą misję.";
                return;
            }
            
            // sprawdzenie czy misja nie koliduje ze szkoleniem
            if($training = ClassSoldier2Missions::checkSoldierMissionConflictWithTraining($id_soldier, $id_mission)){
                $this->alerts['warning'] = "Czas misji koliduje się ze szkoleniem <b>{$training['name']}</b>, które odbywa się w tym samym czasie. Czy mimo tego chcesz przydzielić żołnierza do misji?";
                $this->alerts['warning'] .= '
                    <form method="post" action="" class="clearfix">
                        <input type="hidden" name="id_soldier" value="'.$id_soldier.'" />
                        <input type="hidden" name="id_mission" value="'.$id_mission.'" />
                        <input type="hidden" name="description_add" value="'.ClassTools::getValue('add_form_description').'" />
                        <button class="btn btn-primary" type="submit" name="form_action" value="add_mission_important">Przydziel mimo to</button>
                        <a href="/zolnierze/'.$id_soldier.'/misje/" class="btn btn-danger" title="Nie przydzielaj">Nie przydzielaj</a>
                    </form>
                ';
                return;
            }
            /* 
            // ladowanie zolnierza przez klase
            $soldier = new ClassSoldier($id_soldier);
            
            // sprawdzanie czy poprawnie klasa zaladowala zolnierza
            if(!$soldier->load_class){
                $this->alerts['danger'] = "Klasa nie mogła załadować żołnierza.";
                return;
            } */
            
            $soldier2mission = new ClassSoldier2Missions();
            $soldier2mission->id_soldier = $id_soldier;
            $soldier2mission->id_mission = $id_mission;
            $soldier2mission->id_user_add = '4';
            // $soldier2mission->id_user_add = ClassAuth::getCurrentUserId();
            $soldier2mission->description_add = ClassTools::getValue('add_form_description');
            
            // komunikaty bledu
            if(!$soldier2mission->add()){
                $this->alerts['danger'] = $soldier2mission->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie przydzielono żołnierza <b>{$soldier2mission->soldier_name}</b> do misji <b>{$soldier2mission->mission_name}</b>.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie powiazania zolnierza z misja
        protected function deleteMissionFromSoldier(){
            $id_soldier2missions = ClassTools::getValue('id_soldier2missions');
            
            // sprawdzanie czy misja istnieje w zolnierzu
            if(!ClassSoldier2Missions::checkMissionInSoldier($id_soldier2missions)){
                $this->alerts['danger'] = 'Misja nie jest powiązana z żołnierzem.';
                return;
            }
            
            $soldier2mission = new ClassSoldier2Missions($id_soldier2missions);
            // $soldier2mission->id_user_delete = ClassAuth::getCurrentUserId();;
            $soldier2mission->id_user_delete = '2';
            
            // usuwanie misji z zolnierza
            if($soldier2mission->delete()){
                // komunikat
                $this->alerts['success'] = "Poprawnie usunięto misję powiązaną z żołnierzem";
            }else{
                // bledy w przypadku problemow z usunieciem misji
                $this->alerts['danger'] = $soldier2mission->errors;
            }
            
            $_POST = array();
            
            return;
        }
        
        // oddelegowywanie zolnierza od misji
        protected function secondedMissionFromSoldier(){
            $id_soldier2missions = ClassTools::getValue('id_soldier2missions');
            $description_delete = ClassTools::getValue('description_delete');
            
            // sprawdzanie czy misja istnieje w zolnierzu
            if(!ClassSoldier2Missions::checkMissionInSoldier($id_soldier2missions)){
                $this->alerts['danger'] = 'Misja nie jest powiązana z żołnierzem.';
                return;
            }
            
            // sprawdzanie czy misja istnieje w zolnierzu
            if(!ClassSoldier2Missions::checkMissionInSoldier2($id_soldier2missions)){
                $this->alerts['danger'] = 'Żołnierz jest już oddelegowany od tej misji.';
                return;
            }
            
            $soldier2mission = new ClassSoldier2Missions($id_soldier2missions);
            // $soldier2mission->id_user_delete = ClassAuth::getCurrentUserId();;
            $soldier2mission->id_user_delete = '2';
            $soldier2mission->description_delete = $description_delete;
            
            // oddelegowywanie zolnierza z misji
            if($soldier2mission->seconded()){
                // komunikat
                $this->alerts['success'] = "Poprawnie oddelegowano żołnierza z misji";
            }else{
                // bledy w przypadku problemow z usunieciem misji
                $this->alerts['danger'] = $soldier2mission->errors;
            }
            
            $_POST = array();
            
            return;
        }
    }
?>
