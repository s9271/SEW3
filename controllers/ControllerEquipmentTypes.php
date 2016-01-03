<?php
    class ControllerEquipmentTypes extends ControllerCategoryModel{
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
                }
            }
            
            return $this->getPageList();
        }
        
        // strona lista
        protected function getPageList(){
            $this->searchActions();
            $this->actions();
            
            // aktualna strona kategorii
            $page = ClassTools::getValue('page') ? ClassTools::getValue('page') : NULL;
            
            // strony
            $this->controller_name = 'typy-wyposazenia';
            $this->using_pages = true;
            $this->count_items = ClassEquipmentType::sqlGetCountItemsById($page);
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Typy wyposażenia: Lista';
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassEquipmentType::sqlGetAllItemsById($page, false, $this->using_pages, $this->current_page, $this->items_on_page);
            
            // dodatkowe zmienne dla listy kategorii
            $this->tpl_values['list_page'] = $page;
            $this->tpl_values['list_page_name'] = $page === NULL ? '' : ' <b>'.ClassEquipmentType::sqlGetItemNameByIdParent($page).'</b>';
            
            // ladowanie strony z lista
            return $this->loadTemplate('/equipment/types-list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            // aktualna strona kategorii
            $page = ClassTools::getValue('page') ? ClassTools::getValue('page') : NULL;
            
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Typ wyposażenia: Dodaj';
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie glownych kategorii
            $this->tpl_values['parent_categories'] = ClassEquipmentType::sqlGetAllItemsById(NULL);
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // dodatkowe zmienne dla listy kategorii
            $form_parent = ClassTools::getValue('form_parent');
            $this->tpl_values['list_page'] = $page;
            $this->tpl_values['form_parent'] = $form_parent ? $form_parent : ($page === NULL ? false : $page);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/equipment/types-form');
        }
        
        // strona edycji
        protected function getPageEdit(){
            // zmienne wyswietlania na wypadek gdy strona nie istnieje
            $this->tpl_values['wstecz'] = '/typy-wyposazenia';
            $this->tpl_values['title'] = 'Edycja typu wyposażenia';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassEquipmentType($id_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Typ wyposażenia nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Typ wyposażenia: Edycja';
            
            // skrypty
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie glownych kategorii
            $this->tpl_values['parent_categories'] = ClassEquipmentType::sqlGetAllItemsById(NULL, $item->id);
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_equipment_type'     => $item->id,
                'form_parent'           => $item->id_parent,
                'form_name'             => $item->name,
                'form_active'           => $item->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // dodatkowe zmienne dla listy kategorii
            $this->tpl_values['list_page'] = $item->id_parent;
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/equipment/types-form');
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
                case 'equipment_type_add':
                    return $this->add(); // dodawanie
                break;
                case 'equipment_type_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'equipment_type_save':
                    return $this->edit(); // edycja
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $active = ClassTools::getValue('form_active');
            $form_parent = ClassTools::getValue('form_parent');
            
            $item = new ClassEquipmentType();
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
            $this->alerts['success'] = "Poprawnie dodano nowy typ wyposażenia: <b>{$item->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassEquipmentType(ClassTools::getValue('id_equipment_type'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class){
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto typ wyposażenia: <b>{$item->name}</b>";
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
            $item = new ClassEquipmentType(ClassTools::getValue('id_equipment_type'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Typ wyposażenia nie istnieje.";
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
            $this->alerts['success'] = "Poprawnie zaktualizowano typ wydażenia: <b>{$item->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
