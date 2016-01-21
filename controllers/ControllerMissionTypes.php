<?php
    class ControllerMissionTypes extends ControllerCategoryModel{
        protected $using_top_title = true;
        protected $top_ico = 'question';
        
        public function __construct(){
            $this->breadcroumb = array(
                array('name' => 'Rodzaje misji', 'link' => '/rodzaje-misji')
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
            
            // tylul na pasku
            $this->top_title = 'Lista rodzajów misji';
            
            // aktualna strona kategorii
            $page = ClassTools::getValue('page') ? ClassTools::getValue('page') : NULL;
            
            if($page !== NULL){
                $equipment_type = new ClassMissionType($page);
                $this->tpl_values['wstecz'] = '/rodzaje-misji';
            
                // sprawdzanie czy klasa zostala poprawnie zaladowana
                if(!$equipment_type->load_class){
                    $this->alerts['danger'] = 'Rodzaj misji nie istnieje.';
                    
                    // ladowanie strony do wyswietlania bledow
                    // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                    return $this->loadTemplate('alert');
                }
            
                // sprawdzanie czy jest parentem
                if($equipment_type->id_parent !== NULL){
                    $this->alerts['danger'] = 'Podkategoria nie może posiadać własnych podkategorii.';
                    
                    // ladowanie strony do wyswietlania bledow
                    // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                    return $this->loadTemplate('alert');
                }
                
                $this->top_title = 'Lista rodzajów misji: '.$equipment_type->name;
                $this->breadcroumb[] = array('name' => htmlspecialchars($equipment_type->name), 'link' => "/rodzaje-misji/podglad/{$page}");
                $this->breadcroumb[] = array('name' => 'Lista', 'link' => "/rodzaje-misji/{$page}");
            }
            
            // strony
            $this->controller_name = 'rodzaje-misji';
            $this->using_pages = true;
            $this->count_items = ClassMissionType::sqlGetCountItemsById($page);
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            // $this->items_on_page = 2;
            
            // tytul strony
            $this->tpl_title = 'Rodzaje misji: Lista';
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassMissionType::sqlGetAllItemsById($page, false, false, $this->using_pages, $this->current_page, $this->items_on_page);
            
            // dodatkowe zmienne dla listy kategorii
            $this->tpl_values['list_page'] = $page;
            $this->tpl_values['list_page_name'] = $page === NULL ? '' : ' <b>'.ClassMissionType::sqlGetItemNameByIdParent($page).'</b>';
            
            // ladowanie strony z lista
            return $this->loadTemplate('/mission/types-list');
        }
        
        // strona dodawania
        protected function getPageAdd()
        {
            // tylul na pasku
            $this->top_title = 'Dodaj rodzaj misji';
            
            // aktualna strona kategorii
            $page = ClassTools::getValue('page') ? ClassTools::getValue('page') : NULL;
            
            if($page !== NULL){
                $equipment_type = new ClassMissionType($page);
                $this->tpl_values['wstecz'] = '/rodzaje-misji';
            
                // sprawdzanie czy klasa zostala poprawnie zaladowana
                if(!$equipment_type->load_class){
                    $this->alerts['danger'] = 'Rodzaj misji nie istnieje.';
                    
                    // ladowanie strony do wyswietlania bledow
                    // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                    return $this->loadTemplate('alert');
                }
            
                // sprawdzanie czy jest parentem
                if($equipment_type->id_parent !== NULL){
                    $this->alerts['danger'] = 'Podkategoria nie może posiadać własnych podkategorii.';
                    
                    // ladowanie strony do wyswietlania bledow
                    // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                    return $this->loadTemplate('alert');
                }
            }
            
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Rodzaj misji: Dodaj';
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie glownych kategorii
            $this->tpl_values['parent_categories'] = ClassMissionType::sqlGetAllItemsById(NULL);
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // dodatkowe zmienne dla listy kategorii
            $form_parent = ClassTools::getValue('form_parent');
            $this->tpl_values['list_page'] = $page;
            $this->tpl_values['form_parent'] = $form_parent ? $form_parent : ($page === NULL ? false : $page);
            
            if($page !== NULL){
                $name_page = ClassMissionType::sqlGetItemNameByIdParent($page);
                $this->breadcroumb[] = array('name' => htmlspecialchars($name_page), 'link' => "/rodzaje-misji/podglad/{$page}");
                $this->breadcroumb[] = array('name' => 'Dodaj', 'link' => "/rodzaje-misji/{$page}/dodaj");
            }
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/mission/types-form');
        }
        
        // strona edycji
        protected function getPageEdit()
        {
            // tylul na pasku
            $this->top_title = 'Edytuj rodzaj misji';
            
            // zmienne wyswietlania na wypadek gdy strona nie istnieje
            $this->tpl_values['wstecz'] = '/rodzaje-misji';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassMissionType($id_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->tpl_values['wstecz'] = '/rodzaje-misji';
                $this->alerts['danger'] = 'Typ wyposażenia nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Rodzaj misji: Edycja';
            
            // skrypty
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie glownych kategorii
            $this->tpl_values['parent_categories'] = ClassMissionType::sqlGetAllItemsById(NULL, $item->id);
            
            if($item->id_parent !== NULL){
                $name_page = ClassMissionType::sqlGetItemNameByIdParent($item->id_parent);
                $this->breadcroumb[] = array('name' => htmlspecialchars($name_page), 'link' => "/rodzaje-misji/podglad/{$item->id_parent}");
            }
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->name), 'link' => "/rodzaje-misji/podglad/{$item->id}");
            $this->breadcroumb[] = array('name' => 'Edytuj', 'link' => "/rodzaje-misji/{$item->id_parent}/edytuj");
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_mission_type'       => $item->id,
                'form_parent'           => $item->id_parent,
                'form_name'             => $item->name,
                'form_active'           => $item->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // dodatkowe zmienne dla listy kategorii
            $this->tpl_values['list_page'] = $item->id_parent;
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/mission/types-form');
        }
        
        // strona podglądu
        protected function getPageView()
        {
            // tylul na pasku
            $this->top_title = 'Podgląd rodzaju misji';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = '/rodzaje-misji';
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
            $item = new ClassMissionType($id_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Rodzaj misji nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->tpl_values['category_name'] = 'Rodzaj misji jest kategorią główną';
            
            if($item->id_parent !== NULL){
                $name_page = ClassMissionType::sqlGetItemNameByIdParent($item->id_parent);
                $this->breadcroumb[] = array('name' => htmlspecialchars($name_page), 'link' => "/rodzaje-misji/podglad/{$item->id_parent}");
                $this->tpl_values['category_name'] = $name_page;
            }
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->name), 'link' => "/rodzaje-misji/podglad/{$item->id}");
            
            // tytul
            $this->tpl_title = 'Rodzaj misji: Podgląd';
            
            $this->tpl_values['active_name'] = $item->active_name;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_mission_type'       => $item->id,
                'form_parent'           => $item->id_parent,
                'form_name'             => $item->name,
                'form_active'           => $item->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/mission/types-view');
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
            // print_r($this->tpl_values);
            
            switch($_POST['form_action']){
                case 'mission_type_add':
                    return $this->add(); // dodawanie
                break;
                case 'mission_type_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'mission_type_save':
                    return $this->edit(); // edycja
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            // return;
            $active = ClassTools::getValue('form_active');
            $form_parent = ClassTools::getValue('form_parent');
            
            $item = new ClassMissionType();
            $item->name = ClassTools::getValue('form_name');
            $item->id_parent = $form_parent != '' && is_numeric($form_parent) ? $form_parent : NULL;
            $item->id_user = ClassAuth::getCurrentUserId();
            $item->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowy rodzaj misji: <b>{$item->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassMissionType(ClassTools::getValue('id_mission_type'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class){
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto rodzaj misji: <b>{$item->name}</b>";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Typ wyposażenia nie istnieje';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassMissionType(ClassTools::getValue('id_mission_type'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Rodzaj misji nie istnieje.";
                return;
            }
            
            $active = ClassTools::getValue('form_active');
            $form_parent = ClassTools::getValue('form_parent');
            
            $item->name = ClassTools::getValue('form_name');
            $item->id_parent = $form_parent != '' && is_numeric($form_parent) ? $form_parent : NULL;
            $item->id_user = ClassAuth::getCurrentUserId();
            $item->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano rodzaj misji: <b>{$item->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
