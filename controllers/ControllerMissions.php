<?php
    class ControllerMissions extends ControllerModel{
        protected $search_controller = 'misje';
        protected $using_top_title = true;
        protected $top_ico = 'question';
        protected $top_help_button = true;
        protected $top_help_file = 'missions';
        
        public function __construct(){
            $this->search_definition = $this->getSearchDefinition();
            $this->breadcroumb = array(
                array('name' => 'Misje', 'link' => '/misje')
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
                        // ladowanie strony z formularzem
                        return $this->getPageEdit();
                    break;
                    case 'podglad':
                        // ladowanie strony z podgladem misji
                        return $this->getPageView();
                    break;
                }
            }
            
            return $this->getPageList();
        }
        
        // strona lista misjii
        protected function getPageList(){
            $this->searchActions();
            $this->actions();
            
            // tylul na pasku
            $this->top_title = 'Lista misji';
            
            // strony
            $this->controller_name = 'misje';
            $this->using_pages = true;
            $this->count_items = ClassMission::sqlGetCountItems($this->search_controller);
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Misja: Lista';
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values = ClassMission::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page, $this->search_controller);
            
            // ladowanie strony z lista misji
            return $this->loadTemplate('/mission/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Misja: Dodaj';
            
            // tylul na pasku
            $this->top_title = 'Dodaj misję';
            
            $this->breadcroumb[] = array('name' => 'Dodaj', 'link' => '/misje/dodaj');
            
            // ladowanie pluginow
            $this->load_datetimepicker = true;
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie rodzajow misjii
            // $this->tpl_values['form_types'] = ClassMission::getTypes();
            $this->tpl_values['form_types'] = ClassMissionType::getMissionTypes();
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/mission/form');
        }
        
        // strona edycji
        protected function getPageEdit()
        {
            // tylul na pasku
            $this->top_title = 'Edytuj misję';
            
            // zmienne wyswietlania na wypadek gdy strona z misja nie istnieje
            $this->tpl_values['wstecz'] = '/misje';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            $this->tpl_values['wstecz'] = '/misje';
            
            // ladowanie klasy i misji
            $item = new ClassMission($id_item);
            
            // sprawdzanie czy misja zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Misja nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Misja: Edycja';
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->name), 'link' => "/misje/podglad/{$item->id}");
            $this->breadcroumb[] = array('name' => 'Edytuj', 'link' => "/misje/edytuj/{$item->id}");
            
            // skrypty
            $this->load_datetimepicker = true;
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            // rodzaje misji
            // $this->tpl_values['form_types'] = ClassMission::getTypes();
            $this->tpl_values['form_types'] = ClassMissionType::getMissionTypes();
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_mission'            => $item->id,
                'form_type'             => $item->id_mission_type,
                'form_name'             => $item->name,
                'form_location'         => $item->location,
                'form_description'      => $item->description,
                'form_date_start'       => ClassMission::getPlDate($item->date_start),
                'form_date_end'         => ClassMission::getPlDate($item->date_end),
                'form_active'           => $item->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/mission/form');
        }
        
        // strona podgladu
        protected function getPageView()
        {
            // tylul na pasku
            $this->top_title = 'Podgląd misji';
            
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
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($mission->name), 'link' => "/misje/podglad/{$mission->id}");
            
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
            $this->tpl_values['user'] = ClassUser::sqlGetNameSurnameById($mission->id_user);
            
            // print_r($this->tpl_values['log']);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/mission/view');
        }
        
        /* ************ WYSZUKIWARKA *********** */
        /* ************************************* */
        
        protected function getSearchDefinition()
        {
            // ladowanie rodzajow misjii
            // $types = ClassMission::getTypes();
            $types = ClassMissionType::getMissionTypes();
            
            $form_values = array(
                'class' => 'ClassMission',
                'controller' => $this->search_controller,
                'form' => array(
                    'id_mission' => array(
                        'class' => 'table_id',
                        'type' => 'text'
                    ),
                    'name' => array(
                        'class' => 'table_name',
                        'type' => 'text'
                    ),
                    'id_mission_type' => array(
                        'class' => 'table_rodzaj',
                        'type' => 'select',
                        'optgroup' => $types
                    ),
                    'location' => array(
                        'class' => 'table_lokalizacja',
                        'type' => 'text'
                    ),
                    'date_start' => array(
                        'class' => 'table_date_start',
                    ),
                    'date_end' => array(
                        'class' => 'table_date_end',
                    ),
                    'status' => array(
                        'class' => 'table_status'
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
                case 'mission_add':
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
        protected function add()
        {
            $active = ClassTools::getValue('form_active');
            $form_date_end = ClassTools::getValue('form_date_end');
            
            $item = new ClassMission();
            $item->name = ClassTools::getValue('form_name');
            $item->id_mission_type = ClassTools::getValue('form_type');
            $item->location = ClassTools::getValue('form_location');
            $item->description = ClassTools::getValue('form_description');
            $item->date_start = ClassTools::getValue('form_date_start');
            $item->date_end = $form_date_end != '' ? $form_date_end : NULL;
            $item->id_user = ClassAuth::getCurrentUserId();
            $item->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nową misję: <b>{$item->name}</b>";
            
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
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Misja nie istnieje';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassMission(ClassTools::getValue('id_mission'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Misja nie istnieje.";
                return;
            }
            
            $active = ClassTools::getValue('form_active');
            $form_date_end = ClassTools::getValue('form_date_end');
            
            $item->name = ClassTools::getValue('form_name');
            $item->id_mission_type = ClassTools::getValue('form_type');
            $item->location = ClassTools::getValue('form_location');
            $item->description = ClassTools::getValue('form_description');
            $item->date_start = ClassTools::getValue('form_date_start');
            $item->date_end = $form_date_end != '' ? $form_date_end : NULL;
            $item->id_user = ClassAuth::getCurrentUserId();
            $item->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano misję: <b>{$item->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
