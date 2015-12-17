<?php
    class ControllerIndex extends ControllerModel{
        public function getContent(){
            return $this->loadTemplate('index');
            // return ClassTools::loadTemplate('index');
        }
    }
?>
