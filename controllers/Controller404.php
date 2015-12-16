<?php
    class Controller404{
        public function getContent($with_panel = true){
            if(!$with_panel){
                return $this->loadTemplate('404');
            }else{
                return ClassTools::loadTemplate('404_panel');
            }
        }
        
        // ladowanie template
        protected function loadTemplate($page_name){
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
