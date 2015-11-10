<?php
    class Controller404{
        public function getContent(){
            return ClassTools::loadTemplate('404');
        }
    }
?>
