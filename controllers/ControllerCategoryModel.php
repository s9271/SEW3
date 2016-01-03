<?php
    class ControllerCategoryModel extends ControllerModel{
        
        // wyswietlanie stron
        protected function getPages(){
            if(!$this->using_pages || $this->count_items <= $this->items_on_page){
                return;
            }
            
            // aktualna strona kategorii
            $page = ClassTools::getValue('page') ? ClassTools::getValue('page').'/' : '';
                
            echo '<div class="sew-pages clearfix"><ul class="clearfix">';
            
            for($i = 1; $i <= ceil($this->count_items/$this->items_on_page); $i++){
                echo '<li><a href="/'.$this->controller_name.'/'.$page.'strona/'.$i.'" class="btn btn-'.(($this->current_page == $i) ? 'default" disabled="disabled"' : 'default"').'>'.$i.'</a></li>';
            }
            
            echo '</ul></div>';
            
            return;
        }
    }
?>
