<?php
    class ControllerIndex{
        public function getContent(){
            return ClassTools::loadTemplate('index');
        }
    }
?>
