<?php
    class ControllerEquipments extends ControllerModel{
        protected $search_controller = 'equipments';
        
        public function __construct(){
            $this->search_definition = $this->getSearchDefinition();
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
            $this->controller_name = 'wyposazenie';
            $this->using_pages = true;
            $this->count_items = ClassEquipment::sqlGetCountItems($this->search_controller);
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Wyposażenie: Lista';
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassEquipment::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page, $this->search_controller);
            
            // ladowanie strony z lista
            return $this->loadTemplate('/equipment/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Wyposażenie: Dodaj';
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie rodzajow
            $this->tpl_values['form_types'] = ClassEquipmentType::getEquipmentTypes();
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/equipment/form');
        }
        
        // strona edycji
        protected function getPageEdit(){
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $this->tpl_values['wstecz'] = '/wyposazenie';
            $this->tpl_values['title'] = 'Edycja Wyposażenia';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassEquipment($id_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Wyposażenie nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Wyposażenie: Edycja';
            
            // skrypty
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie rodzajow
            $this->tpl_values['form_types'] = ClassEquipmentType::getEquipmentTypes();
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_equipment'      => $item->id,
                'form_name'         => $item->name,
                'form_description'  => $item->description,
                'form_type'         => $item->id_equipment_type,
                'form_active'       => $item->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/equipment/form');
        }
        
        /* ************ WYSZUKIWARKA *********** */
        /* ************************************* */
        
        protected function getSearchDefinition(){
            $form_values = array(
                'class' => 'ClassEquipment',
                'controller' => $this->search_controller,
                'form' => array(
                    'id_equipment' => array(
                        'class' => 'table_id',
                        'type' => 'text'
                    ),
                    'name' => array(
                        'class' => 'table_name',
                        'type' => 'text'
                    ),
                    'id_equipment_type' => array(
                        'class' => 'table_type',
                        'type' => 'select',
                        'search' => true,
                        'optgroup' => ClassEquipmentType::getEquipmentTypes()
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
            // print_r($this->tpl_values);
            
            switch($_POST['form_action']){
                case 'equipment_add':
                    return $this->add(); // dodawanie
                break;
                case 'equipment_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'equipment_save':
                    return $this->edit(); // edycja
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $active = ClassTools::getValue('form_active');
            
            $item = new ClassEquipment();
            $item->id_equipment_type = ClassTools::getValue('form_type');
            $item->name = ClassTools::getValue('form_name');
            $item->description = ClassTools::getValue('form_description');
            $item->id_user = ClassAuth::getCurrentUserId();
            $item->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowe wyposażenie: <b>{$item->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassEquipment(ClassTools::getValue('id_equipment'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class)
            {
                // usuwanie
                if($item->delete())
                {
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto wyposażenie: <b>{$item->name}</b>.";
                    return;
                }
                else
                {
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                }
            }
            
            $this->alerts['danger'] = 'Wyposażenie nie istnieje.';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassEquipment(ClassTools::getValue('id_equipment'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Wyposażenie nie istnieje.";
            }
            
            $active = ClassTools::getValue('form_active');
            
            $item->id_equipment_type = ClassTools::getValue('form_type');
            $item->name = ClassTools::getValue('form_name');
            $item->description = ClassTools::getValue('form_description');
            $item->id_user = ClassAuth::getCurrentUserId();
            $item->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano wyposażenie: <b>{$item->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
