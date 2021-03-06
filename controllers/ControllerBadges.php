<?php
    class ControllerBadges extends ControllerModel{
        protected $search_controller = 'badges';
        protected $using_top_title = true;
        protected $top_ico = 'trophy';
        protected $top_help_button = true;
        protected $top_help_file = 'badges';
        
        public function __construct(){
            $this->search_definition = $this->getSearchDefinition();
            
            $this->breadcroumb = array(
                array('name' => 'Odznaczenia', 'link' => '/odznaczenia')
            );
        }
        
        // funkcja ktora jest pobierana w indexie, jest wymagana w kazdym kontrolerze!!!!!
        public function getContent(){
            return $this->getPage();
        }
        
        /* ************** STRONY ************* */
        /* *********************************** */
        
        // pobieranie strony
        protected function getPage(){
            // sprawdzanie czy jest sie na podstronie
            if($page_action = ClassTools::getValue('page_action')){
                switch($page_action){
                    case 'dodaj':
                        // ladowanie strony z formularzem
                        return $this->getPageAdd();
                    break;
                    case 'edytuj':
                        // ladowanie strony z formularzem
                        return $this->getPageEdit();
                    break;
                    case 'podglad':
                        // ladowanie strony z podgladem
                        return $this->getPageView();
                    break;
                }
            }
            
            return $this->getPageList();
        }
        
        // strona lista
        protected function getPageList(){
            $this->searchActions();
            $this->actions();
            
            // strony
            $this->controller_name = 'odznaczenia';
            $this->using_pages = true;
            $this->count_items = ClassBadge::sqlGetCountItems($this->search_controller);
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Odznaczenia: Lista';
            
            // tylul na pasku
            $this->top_title = 'Lista odznaczeń';
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values = ClassBadge::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page, $this->search_controller);
            
            // ladowanie strony z lista
            return $this->loadTemplate('/badges/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Odznaczenia: Dodaj';
            
            // tylul na pasku
            $this->top_title = 'Dodaj odznaczenie';
            
            $this->breadcroumb[] = array('name' => 'Dodaj', 'link' => '/odznaczenia/dodaj');
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie rodzajow
            // $this->tpl_values['form_ranks'] = ClassBadge::getRanks();
            $this->tpl_values['form_ranks'] = ClassBadgeType::sqlGetAllItemsNameById(NULL, false, true);
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/badges/form');
        }
        
        // strona edycji
        protected function getPageEdit()
        {
            // tylul na pasku
            $this->top_title = 'Edytuj odznaczenia';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $this->tpl_values['wstecz'] = '/odznaczenia';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $badge = new ClassBadge($id_item);
            
            // sprawdzanie czy odznaczenie zostalo poprawnie zaladowane
            if(!$badge->load_class){
                $this->tpl_values['wstecz'] = '/odznaczenia';
                $this->alerts['danger'] = 'Odznaczenie nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Odznaczenie: Edycja';
            
            // skrypty
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie rodzajow
            // $this->tpl_values['form_ranks'] = ClassBadge::getRanks();
            $this->tpl_values['form_ranks'] = ClassBadgeType::sqlGetAllItemsNameById(NULL, false, true);
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($badge->name), 'link' => "/odznaczenia/podglad/{$badge->id}");
            $this->breadcroumb[] = array('name' => 'Edytuj', 'link' => "/odznaczenia/edytuj/{$badge->id}");
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_badge'      => $badge->id,
                'form_name'     => $badge->name,
                'form_rank'     => $badge->id_badge_type,
                'form_active'   => $badge->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/badges/form');
        }
        
        // strona podgladu
        protected function getPageView()
        {
            // tylul na pasku
            $this->top_title = 'Podgląd odznaczenia';
            
            // zmienne wyswietlania na wypadek gdy strona nie istnieje
            $this->tpl_values['wstecz'] = '/odznaczenia';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassBadge($id_item);
            
            // sprawdzanie czy item zostal poprawnie zaladowany
            if(!$item->load_class){
                $this->tpl_values['wstecz'] = '/odznaczenia';
                $this->alerts['danger'] = 'Odznaczenie nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->name), 'link' => "/odznaczenia/podglad/{$item->id}");
            
            // tytul
            $this->tpl_title = 'Odznaczenia: Podgląd';
            
            // skrypty
            $this->load_js_functions = true;
            
            $this->tpl_values['active_name'] = $item->active_name;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_badge'              => $item->id,
                'form_name'             => $item->name,
                'form_rank'             => $item->id_badge_type,
                'badge_rank_name'       => $item->badge_rank_name,
                'form_active'           => $item->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/badges/view');
        }
        
        /* ************ WYSZUKIWARKA *********** */
        /* ************************************* */
        
        protected function getSearchDefinition(){
            // $ranks = ClassBadge::getRanks();
            // $form_ranks = array();
            
            // foreach($ranks as $rank){
                // $form_ranks[$rank['id_badge_rank']] = $rank['name'];
            // }
            
            $form_ranks = ClassBadgeType::sqlGetAllItemsNameById(NULL);
            
            $form_values = array(
                'class' => 'ClassBadge',
                'controller' => $this->search_controller,
                'form' => array(
                    'id_badge' => array(
                        'class' => 'table_id',
                        'type' => 'text'
                    ),
                    'name' => array(
                        'class' => 'table_name',
                        'type' => 'text'
                    ),
                    'id_badge_type' => array(
                        'class' => 'table_rank',
                        'type' => 'select',
                        'options' => $form_ranks
                    ),
                    'active' => array(
                        'class' => 'table_status',
                        'type' => 'select',
                        'options' => array(
                            '0' => 'Wyłączony',
                            '1' => 'Włączony',
                        )
                    ),
                    'actions' => array(
                        'class' => 'table_akcje'
                    )
                )
            );
            
            return $form_values;
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
                case 'badge_add':
                    return $this->add(); // dodawanie
                break;
                case 'badge_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'badge_save':
                    return $this->edit(); // edycja
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $active = ClassTools::getValue('form_active');
            
            $badge = new ClassBadge();
            $badge->id_badge_type = ClassTools::getValue('form_rank');
            $badge->name = ClassTools::getValue('form_name');
            $badge->id_user = ClassAuth::getCurrentUserId();
            $badge->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$badge->add()){
                $this->alerts['danger'] = $badge->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowe odznaczenie: <b>{$badge->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $badge = new ClassBadge(ClassTools::getValue('id_badge'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($badge->load_class)
            {
                // usuwanie
                if($badge->delete())
                {
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto odznaczenie: <b>{$badge->name}</b>.";
                    return;
                }
                else
                {
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $badge->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Odznaczenie nie istnieje.';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $badge = new ClassBadge(ClassTools::getValue('id_badge'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$badge->load_class){
                $this->alerts['danger'] = "Odznaczenie nie istnieje.";
                return;
            }
            
            $active = ClassTools::getValue('form_active');
            
            $badge->id_badge_type = ClassTools::getValue('form_rank');
            $badge->name = ClassTools::getValue('form_name');
            $badge->id_user = ClassAuth::getCurrentUserId();
            $badge->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$badge->update()){
                $this->alerts['danger'] = $badge->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano odznaczenie: <b>{$badge->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
