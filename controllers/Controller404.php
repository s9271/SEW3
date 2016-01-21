<?php
    class Controller404 extends ControllerModel{
        public function getContent($with_panel = true){
            if(!$with_panel){
                return $this->loadTemplate2('404');
            }else{
                return $this->loadTemplate('404_panel');
                // return ClassTools::loadTemplate('404_panel');
            }
        }
        
        // ladowanie template
        protected function loadTemplate2($page_name){
            if($page_name && file_exists($_SERVER['DOCUMENT_ROOT'].'/views/'.$page_name.'.tpl')){
                
                ob_start();
                    include_once 'views/'.$page_name.'.tpl';
                    $content = ob_get_contents();
                ob_end_clean();
                return $content;
            }
            
            return false;
        }
    }
?>
