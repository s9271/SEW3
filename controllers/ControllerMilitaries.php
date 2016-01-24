<?php
    class ControllerMilitaries extends ControllerModel{
        protected $search_controller = 'militaries';
        protected $using_top_title = true;
        protected $top_ico = 'map-marker';
        protected $top_help_button = true;
        protected $top_help_file = 'militaries';
        
        public function __construct(){
            $this->search_definition = $this->getSearchDefinition();
            
            $this->breadcroumb = array(
                array('name' => 'Jednostki Wojskowe', 'link' => '/jednostki')
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
                        // ladowanie strony z podgladem
                        return $this->getPageView();
                    break;
                }
            }
            
            return $this->getPageList();
        }
        
        // strona lista
        protected function getPageList(){
            $this->searchActions();
            $this->actions();
            
            // strony
            $this->controller_name = 'jednostki';
            $this->using_pages = true;
            $this->count_items = ClassMilitary::sqlGetCountItems($this->search_controller);
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Jednostki: Lista';
            
            // tylul na pasku
            $this->top_title = 'Lista jednostek wojskowych';
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values = ClassMilitary::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page, $this->search_controller);
            
            // ladowanie strony z lista
            return $this->loadTemplate('/military/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Jednostka Wojskowa: Dodaj';
            
            // tylul na pasku
            $this->top_title = 'Dodaj jednostkę wojskową';
            
            $this->breadcroumb[] = array('name' => 'Dodaj', 'link' => '/jednostki/dodaj');
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie rodzajow
            // $this->tpl_values['form_groups'] = ClassMilitary::getGroups();
            $this->tpl_values['form_groups'] = ClassMilitaryType::sqlGetAllItemsNameById(NULL, false, true);
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/military/form');
        }
        
        // strona edycji
        protected function getPageEdit()
        {
            // tylul na pasku
            $this->top_title = 'Edytuj jednostki wojskowej';
            
            // zmienne wyswietlania na wypadek gdy strona z jednostka nie istnieje
            $this->tpl_values['wstecz'] = '/jednostki';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $military = new ClassMilitary($id_item);
            
            // sprawdzanie czy jednostka zostala poprawnie zaladowana
            if(!$military->load_class){
                $this->alerts['danger'] = 'Jednostka nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Jednostka Wojskowa: Edycja';
            
            // skrypty
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($military->name), 'link' => "/jednostki/podglad/{$military->id}");
            $this->breadcroumb[] = array('name' => 'Edytuj', 'link' => "/jednostki/edytuj/{$military->id}");
            
            // ladowanie rodzajow
            // $this->tpl_values['form_groups'] = ClassMilitary::getGroups();
            $this->tpl_values['form_groups'] = ClassMilitaryType::sqlGetAllItemsNameById(NULL, false, true);
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_military'       => $military->id,
                'form_name'         => $military->name,
                'form_number'       => $military->number,
                'form_group'        => $military->id_military_type,
                'form_location'     => $military->location,
                'form_active'       => $military->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/military/form');
        }
        
        // strona podglądu
        protected function getPageView(){
            // tylul na pasku
            $this->top_title = 'Podgląd jednostki wojskowej';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = '/jednostki';
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
            $military = new ClassMilitary($id_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$military->load_class){
                $this->alerts['danger'] = 'Jednostka nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($military->name), 'link' => "/jednostki/podglad/{$military->id}");
            
            // tytul
            $this->tpl_title = 'Jednostka Wojskowa: Podgląd';
            
            
            $this->tpl_values['active_name'] = $military->active_name;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_military'           => $military->id,
                'form_name'             => $military->name,
                'form_number'           => $military->number,
                'form_group'            => $military->id_military_type,
                'form_location'         => $military->location,
                'military_group_name'   => $military->military_group_name,
                'form_active'           => $military->active_name
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/military/view');
        }
        
        /* ************ WYSZUKIWARKA *********** */
        /* ************************************* */
        
        protected function getSearchDefinition(){
            $form_values = array(
                'class' => 'ClassMilitary',
                'controller' => $this->search_controller,
                'form' => array(
                    'id_military' => array(
                        'class' => 'table_id',
                        'type' => 'text'
                    ),
                    'number' => array(
                        'class' => 'table_number',
                        'type' => 'text'
                    ),
                    'name' => array(
                        'class' => 'table_name',
                        'type' => 'text'
                    ),
                    'location' => array(
                        'class' => 'table_lokalizacja',
                        'type' => 'text'
                    ),
                    'id_military_type' => array(
                        'class' => 'table_rodzaj',
                        'type' => 'select',
                        // 'options' => ClassMilitary::getGroups()
                        'options' => ClassMilitaryType::sqlGetAllItemsNameById(NULL)
                    ),
                    'active' => array(
                        'class' => 'table_status',
                        'type' => 'select',
                        'options' => array(
                            '0' => 'Wyłączony',
                            '1' => 'Włączony',
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
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $this->setValuesTemplateByPost();
            
            switch($_POST['form_action']){
                case 'military_add':
                    return $this->add(); // dodawanie
                break;
                case 'military_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'military_save':
                    return $this->edit(); // edycja
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $active = ClassTools::getValue('form_active');
            
            $military = new ClassMilitary();
            $military->id_military_type = ClassTools::getValue('form_group');
            $military->number = ClassTools::getValue('form_number');
            $military->name = ClassTools::getValue('form_name');
            $military->location = ClassTools::getValue('form_location');
            $military->id_user = ClassAuth::getCurrentUserId();
            $military->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$military->add()){
                $this->alerts['danger'] = $military->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nową jednostkę: <b>{$military->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $military = new ClassMilitary(ClassTools::getValue('id_military'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($military->load_class)
            {
                // usuwanie
                if($military->delete())
                {
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto jednostkę: <b>{$military->name}</b>.";
                    return;
                }
                else
                {
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $military->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Jednostka nie istnieje.';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $military = new ClassMilitary(ClassTools::getValue('id_military'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$military->load_class){
                $this->alerts['danger'] = "Jednostka nie istnieje.";
            }
            
            $active = ClassTools::getValue('form_active');
            
            $military->id_military_type = ClassTools::getValue('form_group');
            $military->number = ClassTools::getValue('form_number');
            $military->name = ClassTools::getValue('form_name');
            $military->location = ClassTools::getValue('form_location');
            $military->id_user = ClassAuth::getCurrentUserId();
            $military->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$military->update()){
                $this->alerts['danger'] = $military->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano jednostkę: <b>{$military->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
