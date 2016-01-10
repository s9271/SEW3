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
    }
?>
