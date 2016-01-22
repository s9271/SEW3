<?php
    class ControllerSoldiersRanks extends ControllerModel{
        protected $using_top_title = true;
        protected $top_ico = 'angle-double-up';
        
        public function __construct(){
            $this->breadcroumb = array(
                array('name' => 'Żołnierze', 'link' => '/zolnierze')
            );
        }
        
        // funkcja ktora jest pobierana w indexie, jest wymagana w kazdym kontrolerze!!!!!
        public function getContent(){
            return $this->getPage();
        }
        
        /* ************** STRONY ************* */
        /* *********************************** */
        
        // pobieranie strony
        protected function getPage()
        {
            // tylul na pasku
            $this->top_title = 'Lista stopni wojskowych';
            
            // ladowanie klasy
            $item = new ClassSoldier(ClassTools::getValue('id_item'));
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->tpl_values['wstecz'] = '/zolnierze';
                $this->alerts['danger'] = 'Żołnierz nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->breadcroumb[] = array('name' => "{$item->name} {$item->surname}", 'link' => "/zolnierze/podglad/{$item->id}");
            $this->breadcroumb[] = array('name' => 'Stopnie wojskowe', 'link' => "/zolnierze/{$item->id}/stopien");
            
            return $this->getPageList($item);
        }
        
        // strona lista
        protected function getPageList($item){
            $this->actions();
            
            // strony
            $this->controller_name = 'stopien';
            $this->using_pages = true;
            $this->count_items = ClassSoldierRank::sqlGetCountItems('', array('id_soldier' => $item->id));
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Stopień wojskowy";
            
            // ladowanie funkcji
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassSoldierRank::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page, '', array('id_soldier' => $item->id));
            
            $this->tpl_values['id_soldier'] = $item->id;
            
            // pobieranie aktualnego stopnia
            $this->tpl_values['actually_rank'] = ClassSoldierRank::getActuallyRank($item->id);
            
            // ladowanie strony z lista
            return $this->loadTemplate('/soldier/ranks');
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        protected function actions(){
            // sprawdzenie czy zostala wykonana jakas akcja zwiazana z formularzem
            if(!isset($_POST['form_action'])){
                return;
            }
            
            // print_r($_POST);
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $this->setValuesTemplateByPost();
            
            switch($_POST['form_action']){
                case 'rank_add':
                    return $this->add(); // dodawanie
                break;
                case 'rank_delete':
                    return $this->delete(); // usuwanie
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $item = new ClassSoldierRank();
            $item->name = ClassTools::getValue('form_name');
            $item->date_add_rank = ClassTools::getValue('form_date');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowy stopień wojskowy.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete()
        {
            // ladowanie klasy
            $item = new ClassSoldierRank(ClassTools::getValue('id_rank'));
            $item->id_soldier = ClassTools::getValue('id_soldier');
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class)
            {
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto stopień wojskowy: <b>{$item->name}</b>.";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Stopień wojskowy nie istnieje.';
            $_POST = array();
            
            return;
        }
    }
?>
