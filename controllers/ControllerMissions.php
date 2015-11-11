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
                }
            }
            
            return $this->getPageList();
        }
        
        // strona lista misjii
        protected function getPageList(){
            $this->tpl_title = 'Misja: Lista';
            $this->tpl_values = ClassMission::sqlGetAllItems();
            
            return $this->loadTemplate('/mission/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->tpl_title = 'Misja: Dodaj';
            $this->tpl_values['form_type'] = ClassMission::getTypes();
            return $this->loadTemplate('/mission/form');
        }
    }
?>
