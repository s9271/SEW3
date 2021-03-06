<?php
    class ControllerMilitaryTypes extends ControllerCategoryModel{
        protected $search_controller = 'military-types';
        protected $using_top_title = true;
        protected $top_ico = 'map-marker';
        
        public function __construct(){
            $this->search_definition = $this->getSearchDefinition();
            
            $this->breadcroumb = array(
                array('name' => 'Rodzaje jednostek wojskowych', 'link' => '/rodzaje-jednostek')
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
            // print_r($_GET);
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
            $this->controller_name = 'rodzaje-jednostek';
            $this->using_pages = true;
            // $this->items_on_page = 2;
            $this->count_items = ClassMilitaryType::sqlGetCountItemsById(NULL, $this->search_controller);
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Rodzaje jednostek wojskowych: Lista';
            
            // tylul na pasku
            $this->top_title = 'Lista rodzajów jednostek wojskowych';
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassMilitaryType::sqlGetAllItemsById(NULL, false, false, $this->using_pages, $this->current_page, $this->items_on_page, $this->search_controller);
            
            // ladowanie strony z lista
            return $this->loadTemplate('/military/types-list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Rodzaj jednoski wojskowej: Dodaj';
            
            // tylul na pasku
            $this->top_title = 'Dodaj rodzaj jednoski wojskowej';
            
            $this->breadcroumb[] = array('name' => 'Dodaj', 'link' => '/rodzaje-jednostek/dodaj');
            
            // ladowanie pluginow
            $this->load_js_functions = true;
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/military/types-form');
        }
        
        // strona edycji
        protected function getPageEdit(){
            // tylul na pasku
            $this->top_title = 'Edytuj rodzaj jednostki wojskowej';
            
            // zmienne wyswietlania na wypadek gdy strona nie istnieje
            $this->tpl_values['wstecz'] = '/rodzaje-jednostek';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassMilitaryType($id_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Rodzaj jednoski wojskowej nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Rodzaj jednoski wojskowej: Edycja';
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->name), 'link' => "/rodzaje-jednostek/podglad/{$item->id}");
            $this->breadcroumb[] = array('name' => 'Edytuj', 'link' => "/rodzaje-jednostek/edytuj/{$item->id}");
            
            // skrypty
            $this->load_js_functions = true;
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_military_type'      => $item->id,
                'form_name'             => $item->name,
                'form_active'           => $item->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/military/types-form');
        }
        
        // strona podglądu
        protected function getPageView(){
            // tylul na pasku
            $this->top_title = 'Podgląd rodzaju jednostki wojskowej';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = '/rodzaje-jednostek';
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
            $item = new ClassMilitaryType($id_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Rodzaj jednoski wojskowej nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->name), 'link' => "/rodzaje-jednostek/podglad/{$item->id}");
            
            // tytul
            $this->tpl_title = 'Rodzaj Jednostki Wojskowej: Podgląd';
            
            
            $this->tpl_values['active_name'] = $item->active_name;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_military_type'      => $item->id,
                'form_name'             => $item->name,
                'form_active'           => $item->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/military/types-view');
        }
        
        /* ************ WYSZUKIWARKA *********** */
        /* ************************************* */
        
        protected function getSearchDefinition(){
            $form_values = array(
                'class' => 'ClassMilitaryType',
                'controller' => $this->search_controller,
                'form' => array(
                    'id_military_type' => array(
                        'class' => 'table_id',
                        'type' => 'text'
                    ),
                    'name' => array(
                        'class' => 'table_name',
                        'type' => 'text'
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
            
            print_r($_POST);
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $this->setValuesTemplateByPost();
            
            switch($_POST['form_action']){
                case 'military_type_add':
                    return $this->add(); // dodawanie
                break;
                case 'military_type_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'military_type_save':
                    return $this->edit(); // edycja
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $active = ClassTools::getValue('form_active');
            
            $item = new ClassMilitaryType();
            $item->name = ClassTools::getValue('form_name');
            $item->id_user = ClassAuth::getCurrentUserId();
            $item->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowy rodzaj jednostki wojskowej: <b>".htmlspecialchars($item->name)."</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassMilitaryType(ClassTools::getValue('id_military_type'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class){
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto rodzaj jednoski wojskowej: <b>".htmlspecialchars($item->name)."</b>";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Rodzaj jednoski wojskowej nie istnieje';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassMilitaryType(ClassTools::getValue('id_military_type'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Rodzaj jednoski wojskowej nie istnieje';
                return;
            }
            
            $active = ClassTools::getValue('form_active');
            
            $item->name = ClassTools::getValue('form_name');
            $item->id_user = ClassAuth::getCurrentUserId();
            $item->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano rodzaj jednoski wojskowej: <b>".htmlspecialchars($item->name)."</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
