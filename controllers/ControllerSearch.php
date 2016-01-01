<?php
    class ControllerSearch{
        // zmienna do trzymania kolumn bazy i formularza
        protected $search_definition = array();
        
        // alerty, dostepne: success, info, warning, danger
        protected $alerts = array();
        
        // zmienna z nazwa kontrolera dla wyszukiwarki
        protected $search_controller;
        
        // pobieranie formularza
        protected function getSearchForm(){
            return $this->generateSearchForm();
        }
        
        // sprawdzanie poprawnosci zmiennej
        protected function checkSearchDefinition(){
            // print_r($this->search_definition);
            // sprawdzanie czy istnieja dane w zmiennej
            if(!is_array($this->search_definition) || count($this->search_definition) < 1){
                return false;
            }
            
            // sprawdzanie czy istnieja dane zmienne w array-u
            if(!isset($this->search_definition['class']) || !isset($this->search_definition['controller']) || !isset($this->search_definition['form'])){
                return false;
            }
            
            return true;
        }
        
        // robienie formularza wyszukiwania
        protected function generateSearchForm(){
            if(!$this->checkSearchDefinition()){
                return '';
            }
            
            $controller = $this->search_definition['controller'];
            $form = $this->search_definition['form'];
            $search_session = $this->getValuesSessionPost($controller);
            
            // print_r($_SESSION);
            // print_r($_POST);
            // var_dump($search_session);
            
            $o = '<tr class="form_search">';
            $o .= '<form method="post" action="'.ClassTools::getDomainLink().ClassTools::getValue('controller').'">';
            
            foreach($form as $column_name => $values){
                $class = isset($values['class']) ? ' class="'.$values['class'].'"' : '';
                $type = isset($values['type']) ? $values['type'] : false;
                
                $o .= "<td{$class}>";
                
                switch($type){
                    case 'text':
                        $o .= '<input type="text" value="'.($search_session && isset($search_session[$column_name]) ? $search_session[$column_name] : '').'" name="'.$column_name.'" />';
                    break;
                    case 'select':
                        if(isset($values['options']) && is_array($values['options']) && count($values['options']) > 0){
                            $o .= '<select name="'.$column_name.'" class="jsselectnosearch">';
                            $o .= '<option value="">---</option>';
                            
                            foreach($values['options'] as $option_key => $option_value){
                                $selected = ($search_session && isset($search_session[$column_name]) && $search_session[$column_name] != '' && $search_session[$column_name] == $option_key ? ' selected="selected"' : '');
                                $o .= '<option value="'.$option_key.'"'.$selected.'>'.$option_value.'</option>';
                            }
                            
                            $o .= '</select>';
                        }
                    break;
                    default:
                        if($column_name == 'actions'){
                            $o .= '<div class="btn-group btn-group-sm">
                                <button class="btn btn-primary" value="search" name="form_action_search" type="submit">Szukaj</button>
                                <button class="btn btn-warning" value="search_delete" name="form_action_search" type="submit">Wyczyść</button>
                            </div>
                            ';
                        }else{
                            $o .= '---';
                        }
                    break;
                }
                
                $o .= '</td>';
            }
            
            $o .= '</form>';
            $o .= '</tr>';
            
            return $o;
        }
        
        // pobieranie zmiennych sesji i posta
        protected function getValuesSessionPost($controller){
            // $array = array_map("htmlspecialchars", $array);
            $session = (isset($_SESSION['search']) && isset($_SESSION['search'][$controller])) ? $_SESSION['search'][$controller] : false;
            
            if(!$_POST || !is_array($_POST) || count($_POST) < 1)
            {
                return $session;
            }
            
            foreach($_POST as $key => $val){
                if($key != 'form_action_search'){
                    $session[$key] = (ClassTools::getValue($key) !== false ? ClassTools::getValue($key) : '');
                }
            }
            
            return $session;
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        protected function searchActions(){
            // sprawdzenie czy zostala wykonana jakas akcja zwiazana z formularzem
            if(!isset($_POST['form_action_search'])){
                return;
            }
            
            switch($_POST['form_action_search']){
                case 'search':
                    return $this->search(); // dodanie do sesji sprawdzonych zmiennych do wyszukania
                break;
                case 'search_delete':
                    return $this->clearSearch(); // czyszczenie wyszukiwania
                break;
            }
            
            return;
        }
        
        // czyszczenie wyszukiwania
        protected function clearSearch(){
            $_POST = array();
            
            if($this->checkSearchDefinition() && isset($_SESSION['search'][$this->search_definition['controller']])){
                $_SESSION['search'][$this->search_definition['controller']] = array();
            }
            
            return;
        }
        
        // dodanie do sesji sprawdzonych zmiennych do wyszukania
        protected function search(){
            if(!$this->checkSearchDefinition()){
                return;
            }
            
            $ClassModel = new ClassModel();
            $session_old = false;
            $session = array();
            $class = $this->search_definition['class'];
            $definition = $class::$definition['fields'];
            
            if(isset($_SESSION['search'][$this->search_definition['controller']])){
                $session_old = $_SESSION['search'][$this->search_definition['controller']];
            }
            
            foreach($_POST as $key => $val){
                if($key == 'form_action_search'){
                    continue;
                }
                
                $value = ClassTools::getValue($key) !== false && ClassTools::getValue($key) != '' ? ClassTools::getValue($key) : false;
                
                if($value === false){
                    continue;
                }
                
                if(isset($this->search_definition['form'][$key])){
                    if(isset($definition[$key]) && isset($definition[$key]['validate'])){
                        foreach($definition[$key]['validate'] as $validate_method){
                            $value = $ClassModel->validByMethod($validate_method, $value, $definition[$key]['name'], $key);
                            
                            if($ClassModel->errors && count($ClassModel->errors) > 0){
                                $this->alerts['danger search'][] = $ClassModel->errors['0'];
                                $ClassModel->errors = array();
                            }else{
                                $session[$key] = $value;
                            }
                        }
                    }else{
                        $session[$key] = $value;
                    }
                }
            }
            
            $_SESSION['search'][$this->search_definition['controller']] = $session;
            
            return;
        }
    }
?>
