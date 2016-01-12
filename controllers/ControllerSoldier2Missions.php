<?php
    class ControllerSoldier2Missions extends ControllerModel{
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
                        // return $this->getPageAdd($item);
                    break;
                    case 'edytuj':
                        // ladowanie strony z formularzem
                        return $this->getPageEdit($item);
                    break;
                    case 'oddeleguj':
                        // ladowanie strony z formularzem
                        return $this->getPageDetach($item);
                    break;
                }
            }
            
            return $this->getPageList($item);
        }
        
        // strona lista
        protected function getPageList($item){
            $this->actions($item);
            
            // strony
            $this->controller_name = 'misje';
            $this->using_pages = true;
            $this->count_items = ClassSoldier2Mission::sqlGetCountItems('', array('id_soldier' => $item->id));
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Misje";
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            // $this->tpl_values['items'] = ClassSoldier2Mission::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page, '', array('id_soldier' => $item->id));
            $this->tpl_values['items'] = ClassSoldier2Mission::sqlGetSoldierMissions($item->id, $this->using_pages, $this->current_page, $this->items_on_page);
            
            $this->tpl_values['id_soldier'] = $item->id;
            
            // nazwa klasy i funkcji z ktorej bedzie pobierac opcje do selekta (w klasie musi istniec statyczna funkcja do obslugi tego ajaxa)
            $this->tpl_values['ajax_class'] = 'Soldier2Mission';
            $this->tpl_values['ajax_function'] = 'sqlSearchMissionForSoldier';
            
            // Zaznaczona misja
            $this->tpl_values['mission_selectes'] = '';
            
            if($id_mission = ClassTools::getValue('form_mission')){
                $item = new ClassMission($id_mission);
                
                if($item->load_class && $item->active == '1'){
                    $this->tpl_values['mission_selectes'] = '<option value="'.$id_mission.'" selected="selected">'.$item->name.'</option>';
                }
            }
            
            // ladowanie strony z lista
            return $this->loadTemplate('/soldier/missions');
        }
        
        // strona edycji
        protected function getPageEdit($soldier){
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/misje";
            $this->tpl_values['title'] = "{$soldier->name} {$soldier->surname}: Misje: Edycja";
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_child_item = ClassTools::getValue('id_child_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassSoldier2Mission($id_child_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Misja nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie szkola jest przypisana do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Misja nie jest przypisana do tego żołnierza';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie czy misja nie jest oddelegowana
            if($item->detached == '1'){
                $this->alerts['danger'] = 'Żołnierz został oddelegowany od tej misji.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Misja: Edycja";
            
            // skrypty
            $this->load_js_functions = true;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2missions'       => $item->id,
                'id_soldier'                => $soldier->id,
                'id_mission'                => $item->id_mission,
                'form_description'          => $item->description
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/missions-form');
        }
        
        // strona oddelegowania
        protected function getPageDetach($soldier){
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/misje";
            $this->tpl_values['title'] = "{$soldier->name} {$soldier->surname}: Misje: Oddelegowanie";
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_child_item = ClassTools::getValue('id_child_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassSoldier2Mission($id_child_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Misja nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie szkola jest przypisana do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Misja nie jest przypisana do tego żołnierza';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie czy misja nie jest oddelegowana
            if($item->detached == '1' && (!isset($this->alerts['success']) || $this->alerts['success'] == '')){
                $this->alerts['danger'] = 'Żołnierz został oddelegowany od tej misji.';
                
                // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
                $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/misje";
                $this->tpl_values['title'] = "{$soldier->name} {$soldier->surname}: Misje: Oddelegowanie";
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Misja: Oddelegowanie";
            
            // skrypty
            $this->load_js_functions = true;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2missions'       => $item->id,
                'id_soldier'                => $soldier->id,
                'id_mission'                => $item->id_mission,
                'description_detach'        => $item->description_detach
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/missions-detach');
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        protected function actions($item = false){
            // sprawdzenie czy zostala wykonana jakas akcja zwiazana z formularzem
            if(!isset($_POST['form_action'])){
                return;
            }
            
            print_r($_POST);
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $this->setValuesTemplateByPost();
            
            switch($_POST['form_action']){
                case 'mission_add':
                    return $this->add($item); // dodawanie
                break;
                case 'mission_save':
                    return $this->edit(); // edytowanie
                break;
                case 'mission_detach':
                    return $this->detach(); // oddelegowanie
                break;
                case 'language_delete':
                    return $this->delete(); // usuwanie
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add($soldier)
        {
            $item = new ClassSoldier2Mission();
            $item->id_soldier_tmp = $soldier->id;
            $item->id_mission = ClassTools::getValue('form_mission');
            $item->description = ClassTools::getValue('form_description');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nową misję: <b>{$item->mission_name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassSoldierLanguage(ClassTools::getValue('id_language'));
            $item->id_soldier = ClassTools::getValue('id_soldier');
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class){
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto język: <b>{$item->name}</b>";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Język nie istnieje';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassSoldier2Mission(ClassTools::getValue('id_soldier2missions'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Misja żołnierza nie istnieje.";
            }
            
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_mission = ClassTools::getValue('id_mission');
            $item->description = ClassTools::getValue('form_description');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano misję żołnierza.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // oddelegowanie
        protected function detach()
        {
            // ladowanie klasy
            $item = new ClassSoldier2Mission(ClassTools::getValue('id_soldier2missions'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Misja żołnierza nie istnieje.";
            }
            
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_mission = ClassTools::getValue('id_mission');
            $item->description_detach = ClassTools::getValue('form_description_detach');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->detach()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie oddelegowano żołnierza z misji.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
