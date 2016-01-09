<?php
    class ControllerSoldierChildrens extends ControllerModel{
        // funkcja ktora jest pobierana w indexie, jest wymagana w kazdym kontrolerze!!!!!
        public function getContent(){
            return $this->getPage();
        }
        
        /* ************** STRONY ************* */
        /* *********************************** */
        
        // pobieranie strony
        protected function getPage()
        {
            // ladowanie klasy
            $item = new ClassSoldier(ClassTools::getValue('id_item'));
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Żołnierz nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            return $this->getPageList($item);
        }
        
        // strona lista
        protected function getPageList($item){
            $this->actions();
            
            // strony
            $this->controller_name = 'dzieci';
            $this->using_pages = true;
            $this->count_items = ClassSoldierChildren::sqlGetCountItems();
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Dzieci";
            
            // ladowanie funkcji
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassSoldierChildren::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page);
            
            $this->tpl_values['id_soldier'] = $item->id;
            
            // ladowanie strony z lista
            return $this->loadTemplate('/soldier/child');
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        protected function actions(){
            // sprawdzenie czy zostala wykonana jakas akcja zwiazana z formularzem
            if(!isset($_POST['form_action'])){
                return;
            }
            
            print_r($_POST);
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $this->setValuesTemplateByPost();
            
            switch($_POST['form_action']){
                case 'children_add':
                    return $this->add(); // dodawanie
                break;
                case 'children_delete':
                    return $this->delete(); // usuwanie
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $item = new ClassSoldierChildren();
            $item->name = ClassTools::getValue('form_name');
            $item->surname = ClassTools::getValue('form_surname');
            $item->date_birthday = ClassTools::getValue('form_birthday');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowe dziecko: <b>{$item->name} {$item->surname}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassSoldierChildren(ClassTools::getValue('id_children'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class){
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto dziecko: <b>{$item->name} {$item->surname}</b>";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Dziecko nie istnieje';
            $_POST = array();
            
            return;
        }
    }
?>
