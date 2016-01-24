<?php
    class ControllerSoldierAddresses extends ControllerModel{
        protected $using_top_title = true;
        protected $top_ico = 'home';
        protected $top_help_button = true;
        protected $top_help_file = 'soldier_addresses';
        
        public function __construct(){
            $this->breadcroumb = array(
                array('name' => 'Żołnierze', 'link' => '/zolnierze')
            );
        }
        
        // funkcja ktora jest pobierana w indexie, jest wymagana w kazdym kontrolerze!!!!!
        public function getContent(){
            return $this->getPage();
        }
        
        /* ************** STRONY ************* */
        /* *********************************** */
        
        // pobieranie strony
        protected function getPage()
        {
            // tylul na pasku
            $this->top_title = 'Lista adresów żołnierza';
            
            // ladowanie klasy
            $item = new ClassSoldier(ClassTools::getValue('id_item'));
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->tpl_values['wstecz'] = '/zolnierze';
                $this->alerts['danger'] = 'Żołnierz nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->breadcroumb[] = array('name' => "{$item->name} {$item->surname}", 'link' => "/zolnierze/podglad/{$item->id}");
            $this->breadcroumb[] = array('name' => "Adresy", 'link' => "/zolnierze/{$item->id}/adresy");
            
            // sprawdzanie czy jest sie na podstronie
            if($page_action = ClassTools::getValue('page_action')){
                switch($page_action){
                    case 'dodaj':
                        // ladowanie strony z formularzem
                        return $this->getPageAdd($item);
                    break;
                    case 'edytuj':
                        // ladowanie strony z formularzem
                        return $this->getPageEdit($item);
                    break;
                }
            }
            
            return $this->getPageList($item);
        }
        
        // strona lista
        protected function getPageList($item){
            $this->actions();
            
            // strony
            $this->controller_name = 'adresy';
            $this->using_pages = true;
            $this->count_items = ClassSoldierAddress::sqlGetCountItems('', array('id_soldier' => $item->id));
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Adresy";
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassSoldierAddress::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page, '', array('id_soldier' => $item->id));
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $item->id;
            
            // pobieranie typow adresu
            $this->tpl_values['address_types'] = ClassAddressType::sqlGetAllItemsNameById(NULL, false, true);
            
            // ladowanie strony z lista
            return $this->loadTemplate('/soldier/addresses');
        }
        
        // strona dodawania
        protected function getPageAdd($item)
        {
            // tylul na pasku
            $this->top_title = 'Dodaj adres żołnierza';
            
            $this->actions();
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Adresy: Dodaj";
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            $this->breadcroumb[] = array('name' => "Dodaj", 'link' => "/zolnierze/{$item->id}/adresy/dodaj");
            
            // pobieranie typow adresu
            $this->tpl_values['address_types'] = ClassAddressType::sqlGetAllItemsNameById(NULL, false, true);
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $item->id;
            
            // pobieranie typow adresow przypisanych do tego adresu
            $this->tpl_values['soldier_address_types'] = false;
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/addresses-form');
        }
        
        // strona edycji
        protected function getPageEdit($soldier)
        {
            // tylul na pasku
            $this->top_title = 'Edytuj adres żołnierza';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/adresy";
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_child_item = ClassTools::getValue('id_child_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/adresy";
            
            // ladowanie klasy
            $item = new ClassSoldierAddress($id_child_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Adres nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie szkola jest przypisana do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Adres nie jest przypisany do tego żołnierza';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Adresy: Edycja";
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->street), 'link' => "/zolnierze/{$soldier->id}/adresy/podglad/{$item->id}");
            $this->breadcroumb[] = array('name' => "Edytuj", 'link' => "/zolnierze/{$soldier->id}/adresy/edytuj/{$item->id}");
            
            // skrypty
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie typow adresu
            $this->tpl_values['address_types'] = ClassAddressType::sqlGetAllItemsNameById(NULL, false, true);
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_address'                => $item->id,
                'id_soldier'                => $soldier->id,
                'form_street'               => $item->street,
                'form_postcode'             => $item->postcode,
                'form_city'                 => $item->city,
                'form_country'              => $item->country,
                'form_type'                 => $item->soldier_address_types && is_array($item->soldier_address_types) ? array_keys($item->soldier_address_types) : false
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/addresses-form');
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
                case 'address_add':
                    return $this->add(); // dodawanie
                break;
                case 'address_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'address_save':
                    return $this->edit(); // zapis
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $item = new ClassSoldierAddress();
            $item->street = ClassTools::getValue('form_street');
            $item->postcode = ClassTools::getValue('form_postcode');
            $item->city = ClassTools::getValue('form_city');
            $item->country = ClassTools::getValue('form_country');
            $item->id_address_types = ClassTools::getValue('form_type');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowy adres.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassSoldierAddress(ClassTools::getValue('id_address'));
            $item->id_soldier = ClassTools::getValue('id_soldier');
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class){
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto adres.";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Adres nie istnieje.';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassSoldierAddress(ClassTools::getValue('id_address'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Szkoła żołnierza nie istnieje.";
            }
            
            $item->street = ClassTools::getValue('form_street');
            $item->postcode = ClassTools::getValue('form_postcode');
            $item->city = ClassTools::getValue('form_city');
            $item->country = ClassTools::getValue('form_country');
            $item->id_address_types = ClassTools::getValue('form_type');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano adres żołnierza.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
