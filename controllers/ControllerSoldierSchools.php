<?php
    class ControllerSoldierSchools extends ControllerModel{
        // funkcja ktora jest pobierana w indexie, jest wymagana w kazdym kontrolerze!!!!!
        public function getContent(){
            return $this->getPage();
        }
        
        /* ************** STRONY ************* */
        /* *********************************** */
        
        // pobieranie strony
        protected function getPage()
        {
            // ladowanie klasy
            $item = new ClassSoldier(ClassTools::getValue('id_item'));
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Żołnierz nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
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
            $this->controller_name = 'szkoly-wyzsze';
            $this->using_pages = true;
            $this->count_items = ClassSoldierLanguage::sqlGetCountItems();
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Szkoły wyższe";
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassSoldierSchool::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page);
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $item->id;
            
            // pobieranie tytulow zawodowych
            $this->tpl_values['academic_degrees'] = ClassAcademicDegree::sqlGetAllItemsNameById(NULL, false, true);
            
            // ladowanie strony z lista
            return $this->loadTemplate('/soldier/schools');
        }
        
        // strona dodawania
        protected function getPageAdd($item){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Szkoły wyższe: Dodaj";
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // pobieranie tytulow zawodowych
            $this->tpl_values['academic_degrees'] = ClassAcademicDegree::sqlGetAllItemsNameById(NULL, false, true);
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $item->id;
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/schools-form');
        }
        
        // strona edycji
        protected function getPageEdit($soldier){
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/szkoly-wyzsze";
            $this->tpl_values['title'] = "{$soldier->name} {$soldier->surname}: Szkoły wyższe: Edycja";
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_child_item = ClassTools::getValue('id_child_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassSoldierSchool($id_child_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Szkoła nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie szkola jest przypisana do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Szkoła nie jest przypisana do tego żołnierza';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Szkoły wyższe: Edycja";
            
            // skrypty
            $this->load_select2 = true;
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // pobieranie tytulow zawodowych
            $this->tpl_values['academic_degrees'] = ClassAcademicDegree::sqlGetAllItemsNameById(NULL, false, true);
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_school'                 => $item->id,
                'id_soldier'                => $soldier->id,
                'form_name'                 => $item->name,
                'form_address'              => $item->address,
                'form_specialization'       => $item->specialization,
                'form_academic_degree'      => $item->id_academic_degree,
                'form_date_start'           => $item->date_start,
                'form_date_end'             => $item->date_end
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/schools-form');
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
                case 'school_add':
                    return $this->add(); // dodawanie
                break;
                case 'school_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'school_save':
                    return $this->edit(); // zapis
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $item = new ClassSoldierSchool();
            $item->name = ClassTools::getValue('form_name');
            $item->address = ClassTools::getValue('form_address');
            $item->specialization = ClassTools::getValue('form_specialization');
            $item->id_academic_degree = ClassTools::getValue('form_academic_degree');
            $item->date_start = ClassTools::getValue('form_date_start');
            $item->date_end = ClassTools::getValue('form_date_end');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nową szkolę.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassSoldierSchool(ClassTools::getValue('id_school'));
            $item->id_soldier = ClassTools::getValue('id_soldier');
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class){
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto szkołę: <b>{$item->name}</b>";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Szkoła nie istnieje.';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassSoldierSchool(ClassTools::getValue('id_school'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Szkoła żołnierza nie istnieje.";
            }
            
            $item->name = ClassTools::getValue('form_name');
            $item->address = ClassTools::getValue('form_address');
            $item->specialization = ClassTools::getValue('form_specialization');
            $item->id_academic_degree = ClassTools::getValue('form_academic_degree');
            $item->date_start = ClassTools::getValue('form_date_start');
            $item->date_end = ClassTools::getValue('form_date_end');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano szkolę żołnierza: <b>{$item->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
