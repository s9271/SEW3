<?php
    class ControllerMissions extends ControllerModel{
        public function getContent(){
            return $this->getPage();
        }
        
        /* ************** STRONY ************* */
        /* *********************************** */
        
        protected function getPage(){
            if($page_action = ClassTools::getValue('page_action')){
                switch($page_action){
                    case 'dodaj':
                        return $this->getPageAdd();
                    break;
                    case 'edytuj':
                        return $this->getPageEdit();
                    break;
                }
            }
            
            return $this->getPageList();
        }
        
        // strona lista misjii
        protected function getPageList(){
            $this->actions();
            
            // strony
            $this->controller_name = 'misje';
            $this->using_pages = true;
            $this->count_items = ClassMission::sqlGetCountItems();
            $this->current_page = ClassTools::getValue('page') ? ClassTools::getValue('page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Misja: Lista';
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values = ClassMission::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page);
            
            return $this->loadTemplate('/mission/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            $id_current_type = false;
            
            if(isset($_POST['form_type']) && $_POST['form_type'] != ''){
                $id_current_type = $_POST['form_type'];
            }
            
            $this->tpl_title = 'Misja: Dodaj';
            $this->load_datetimepicker = true;
            $this->load_select2 = true;
            $this->load_js_functions = true;
            $this->tpl_values['form_type'] = ClassMission::getTypes($id_current_type);
            $this->tpl_values['sew_action'] = 'add';
            
            return $this->loadTemplate('/mission/form');
        }
        
        // strona edycji
        protected function getPageEdit(){
            $this->tpl_values['wstecz'] = '/misje';
            $this->tpl_values['title'] = 'Edycja misji';
            
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            $mission = new ClassMission($id_item);
            
            if(!$mission->load_class){
                $this->alerts['danger'] = 'Misja nie istnieje';
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Misja: Edycja';
            
            // skrypty
            $this->load_datetimepicker = true;
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // rodzaje misji
            $this->tpl_values['form_type'] = ClassMission::getTypes((isset($_POST['form_type']) ? $_POST['form_type'] : $mission->id_mission_type));
            $this->tpl_values['sew_action'] = 'edit';
            
            // values
            $this->tpl_values['id_mission'] = $mission->id;
            $this->tpl_values['form_name'] = (isset($_POST['form_name']) ? $_POST['form_name'] : $mission->name);
            $this->tpl_values['form_location'] = (isset($_POST['form_location']) ? $_POST['form_location'] : $mission->location);
            $this->tpl_values['form_description'] = (isset($_POST['form_description']) ? $_POST['form_description'] : $mission->description);
            $this->tpl_values['form_date_start'] = (isset($_POST['form_date_start']) ? $_POST['form_date_start'] : ClassMission::getPlDate($mission->date_start));
            $this->tpl_values['form_date_end'] = (isset($_POST['form_date_end']) ? $_POST['form_date_end'] : ClassMission::getPlDate($mission->date_end));
            $this->tpl_values['form_active'] = (isset($_POST['form_active']) ? $_POST['form_active'] : $mission->active);
            
            return $this->loadTemplate('/mission/form');
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        protected function actions(){
            if(!isset($_POST['form_action'])){
                return;
            }
            
            print_r($_POST);
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $_POST;
            
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
        protected function add(){
            $mission = new ClassMission();
            $mission->id_mission_type = $_POST['form_type'];
            $mission->name = $_POST['form_name'];
            $mission->location = $_POST['form_location'];
            $mission->description = $_POST['form_description'];
            // $mission->id_user = ClassAuth::getCurrentUserId();
            $mission->id_user = '1';
            $mission->date_start = $_POST['form_date_start'];
            $mission->date_end = $_POST['form_date_end'] != '' ? $_POST['form_date_end'] : NULL;
            $mission->active = (isset($_POST['form_active']) && $_POST['form_active'] == '1') ? '1' : '0';
            $mission->deleted = '0';
            
            // custom - dodatkowy warunek odnosnie dat
            if($mission->date_end != NULL && ClassMission::validIsDateTime($mission->date_start) && ClassMission::validIsDateTime($mission->date_end)){
                $date_start = date('Y-m-d H:i:s', strtotime($mission->date_start));
                $date_end = date('Y-m-d H:i:s', strtotime($mission->date_end));
                
                if($date_start > $date_end){
                    $mission->errors[] = "Data rozpoczęcia misji jest większa od daty końca misji.";
                }
            }
            
            // komunikaty
            if(!$mission->add()){
                $this->alerts['danger'] = $mission->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie dodano nową misję: <b>{$mission->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            $mission = new ClassMission($_POST['id_mission']);
            
            if($mission->load_class){
                if($mission->delete()){
            
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto misję: <b>{$mission->name}</b>";
                    return;
                }else{
                    $this->alerts['danger'] = $mission->errors;
                }
            }
            
            $this->alerts['danger'] = 'Misja nie istnieje';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function edit(){
            $mission = new ClassMission($_POST['id_mission']);
            
            if(!$mission->load_class){
                $this->alerts['danger'] = "Misja nie istnieje.";
            }
            
            $mission->id_mission_type = $_POST['form_type'];
            $mission->name = $_POST['form_name'];
            $mission->location = $_POST['form_location'];
            $mission->description = $_POST['form_description'];
            // $mission->id_user = ClassAuth::getCurrentUserId();
            $mission->id_user = '999';
            $mission->date_start = $_POST['form_date_start'];
            $mission->date_end = $_POST['form_date_end'] != '' ? $_POST['form_date_end'] : NULL;
            $mission->active = (isset($_POST['form_active']) && $_POST['form_active'] == '1') ? '1' : '0';
            $mission->deleted = '0';
            
            // custom - dodatkowy warunek odnosnie dat
            if($mission->date_end != NULL && ClassMission::validIsDateTime($mission->date_start) && ClassMission::validIsDateTime($mission->date_end)){
                $date_start = date('Y-m-d H:i:s', strtotime($mission->date_start));
                $date_end = date('Y-m-d H:i:s', strtotime($mission->date_end));
                
                if($date_start > $date_end){
                    $mission->errors[] = "Data rozpoczęcia misji jest większa od daty końca misji.";
                }
            }
            
            // komunikaty
            if(!$mission->update()){
                $this->alerts['danger'] = $mission->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano misję: <b>{$mission->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        
    }
?>
