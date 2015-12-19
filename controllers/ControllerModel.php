<?php
    class ControllerModel{
        // nazwa kontrolera dla linkow do stron
        protected $controller_name = '';
        
        // alerty, dostepne: success, info, warning, danger
        protected $alerts = array();
        
        // zaladowane skrypty
        protected $scripts = false;
        
        // funkcje js
        protected $load_jquery = true;
        
        // wyglad
        protected $load_bootstrap = true;
        
        // data i czas
        protected $load_datetimepicker = false;
        
        // select
        protected $load_select2 = false;
        
        // wlasne przygotowane funkcje
        protected $load_js_functions = false;
        
        // vartosci uzywane w tpl
        protected $tpl_values = array();
        
        // tytul strony
        protected $tpl_title = false;
        
        // opcja, czy kontroler ma uzywac strony
        protected $using_pages = false;
        
        // ilosc rekordow
        protected $count_items = '0';
        
        // ilosc rekordow na strone
        protected $items_on_page = '5';
        
        // aktualna strona
        protected $current_page = '1';
        
        // ladowanie template
        protected function loadTemplate($page_name){
            if($page_name && file_exists($_SERVER['DOCUMENT_ROOT'].'/views/'.$page_name.'.tpl')){
                $this->loadScripts();
                
                ob_start();
                    include_once 'views/partial/header.tpl';
                    include_once 'views/partial/top-nav.php';
                    include_once 'views/'.$page_name.'.tpl';
                    include_once 'views/partial/footer.php';
                    $content = ob_get_contents();
                ob_end_clean();
                return $content;
            }
            
            return false;
        }
        
        // ladowanie skryptow
        protected function loadScripts(){
            if($this->load_jquery){
                $this->scripts[] = '<!-- jQuery 1.11.3 -->';
                $this->scripts[] = '<script src="/asset/jquery/jquery-1.11.3.min.js"></script>';
                $this->scripts[] = '';
            }
            
            if($this->load_bootstrap){
                $this->scripts[] = '<!-- Bootstrap 3.3.5 -->';
                $this->scripts[] = '<link href="/asset/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" />';
                $this->scripts[] = '<script src="/asset/bootstrap/3.3.5/js/bootstrap.min.js"></script>';
                $this->scripts[] = '';
            }
            
            if($this->load_datetimepicker){
                $this->scripts[] = '<!-- Bootstrap 3 Datepicker v4 -->';
                $this->scripts[] = '<script src="/asset/moment/2.10.6/moment.min.js"></script>';
                $this->scripts[] = '<script src="/asset/moment/2.10.6/locale/pl.js"></script>';
                $this->scripts[] = '<script src="/asset/bootstrap-datetimepicker/4.17.37/js/bootstrap-datetimepicker.min.js"></script>';
                $this->scripts[] = '<link href="/asset/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />';
                $this->scripts[] = '';
            }
            
            if($this->load_select2){
                $this->scripts[] = '<!-- Select2 4.0.1 -->';
                $this->scripts[] = '<link href="/asset/select2/4.0.1/css/select2.min.css" rel="stylesheet" />';
                $this->scripts[] = '<script src="/asset/select2/4.0.1/js/select2.min.js"></script>';
                $this->scripts[] = '<script src="/asset/select2/4.0.1/js/i18n/pl.js"></script>';
                $this->scripts[] = '';
            }
            
            if($this->load_js_functions){
                $this->scripts[] = '<!-- Własne funkcje -->';
                $this->scripts[] = '<script src="/asset/js/funkcje.js"></script>';
                $this->scripts[] = '';
            }
            
            return;
        }
        
        // wyswietlanie alertow w template
        protected function getAlerts($small = false){
            if (!is_array($this->alerts) && count($this->alerts) < 1){
                return;
            }
            
            foreach ($this->alerts as $key => $alerts){
                if(is_array($alerts)){
                    $o = '<ul>';
                        foreach ($alerts as $alert){
                            $o .= "<li>{$alert}</li>";
                        }
                    $o .= '</ul>';
                }else{
                    $o = $alerts;
                }
                
                echo '<div class="sew-alert clearfix"><div class="alert alert-'.$key.($small ? ' col-sm-offset-2 col-sm-8 sew_alert_small' : '').'" role="alert">'.$o.'</div></div>';
            }
            
            return;
        }
        
        // wyswietlanie stron
        private function getPages(){
            if(!$this->using_pages || $this->count_items <= $this->items_on_page){
                return;
            }
                
            echo '<div class="sew-pages clearfix"><ul class="clearfix">';
            
            for($i = 1; $i <= ceil($this->count_items/$this->items_on_page); $i++){
                echo '<li><a href="/'.$this->controller_name.'/strona/'.$i.'" class="btn btn-'.(($this->current_page == $i) ? 'default" disabled="disabled"' : 'default"').'>'.$i.'</a></li>';
            }
            
            echo '</ul></div>';
            
            return;
        }
        
        // przypisanie zmiennych posta do zmiennych template
        protected function setValuesTemplateByPost(){
            if(!$_POST || !is_array($_POST) || count($_POST) < 1){
                return '';
            }
            
            $values = array();
            
            foreach($_POST as $key => $val){
                $values[$key] = ClassTools::getValue($key, false);
            }
            
            return $values;
        }
        
        // przypisywanieszych zmiennych do zmiennych formularza
        protected function setValuesTemplateByArrayPost(array $array){
            $array = array_map("htmlspecialchars", $array);
            
            if(!$_POST || !is_array($_POST) || count($_POST) < 1)
            {
                if($this->tpl_values && is_array($this->tpl_values)){
                    $this->tpl_values = array_merge($array, $this->tpl_values);
                }else{
                    $this->tpl_values = $array;
                }
                return;
            }
            
            foreach($array as $key => $valClass){
                $this->tpl_values[$key] = (ClassTools::getValue($key) ? ClassTools::getValue($key) : '');
            }
            
            return;
        }
        
        // pobieranie stron bez praw
        public function getPageNoPermissions(){
            return $this->loadTemplate('no_permissions');
        }
    }
?>