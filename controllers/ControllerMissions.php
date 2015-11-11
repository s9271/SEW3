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
            
            $this->tpl_title = 'Misja: Lista';
            $this->load_js_functions = true;
            $this->tpl_values = ClassMission::sqlGetAllItems();
            // print_r($this->tpl_values);
            
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
            $this->actions();
            
            // $id_current_type = false;
            
            // if(isset($_POST['form_type']) && $_POST['form_type'] != ''){
                // $id_current_type = $_POST['form_type'];
            // }
            
            // $this->tpl_title = 'Misja: Edycja';
            // $this->load_datetimepicker = true;
            // $this->load_select2 = true;
            // $this->load_js_functions = true;
            // $this->tpl_values['form_type'] = ClassMission::getTypes($id_current_type);
            // $this->tpl_values['sew_action'] = 'add';
            
            // return $this->loadTemplate('/mission/form');
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
        
        
    }
?>
