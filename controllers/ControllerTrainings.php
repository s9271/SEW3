<?php
    class ControllerTrainings extends ControllerModel{
        protected $search_controller = 'szkolenia';
        
        public function __construct(){
            $this->search_definition = $this->getSearchDefinition();
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
            $this->controller_name = 'szkolenia';
            $this->using_pages = true;
            $this->count_items = ClassTraining::sqlGetCountItems($this->search_controller);
            $this->current_page = ClassTools::getValue('page') ? ClassTools::getValue('page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Szkolenia: Lista';
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values = ClassTraining::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page, $this->search_controller);
            
            // ladowanie strony z lista
            return $this->loadTemplate('/training/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Szkolenie: Dodaj';
            
            // ladowanie pluginow
            $this->load_datetimepicker = true;
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // ladowanie centrow szkolen
            $this->tpl_values['training_centers'] = ClassTrainingCenter::sqlGetAllActiveItems();
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/training/form');
        }
        
        // strona edycji
        protected function getPageEdit(){
            // zmienne wyswietlania na wypadek gdy strona nie istnieje
            $this->tpl_values['wstecz'] = '/szkolenia';
            $this->tpl_values['title'] = 'Edycja szkolenia';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassTraining($id_item);
            
            // sprawdzanie czy item zostal poprawnie zaladowany
            if(!$item->load_class){
                $this->alerts['danger'] = 'Szkolenie nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Szkolenie: Edycja';
            
            // skrypty
            $this->load_datetimepicker = true;
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            // centra szkolen
            $this->tpl_values['training_centers'] = ClassTrainingCenter::sqlGetAllActiveItems();
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_training'           => $item->id,
                'form_name'             => $item->name,
                'form_code'             => $item->code,
                'form_training_center'  => $item->id_training_centre,
                'form_description'      => $item->description,
                'form_date_start'       => ClassTraining::getPlDate($item->date_start),
                'form_date_end'         => ClassTraining::getPlDate($item->date_end),
                'form_active'           => $item->active
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/training/form');
        }
        
        // strona podgladu
        protected function getPageView(){
            // zmienne wyswietlania na wypadek gdy strona nie istnieje
            $this->tpl_values['wstecz'] = '/szkolenia';
            $this->tpl_values['title'] = 'Podgląd szkolenia';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassTraining($id_item);
            
            // sprawdzanie czy item zostal poprawnie zaladowany
            if(!$item->load_class){
                $this->alerts['danger'] = 'Szkolenie nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Szkolenie: Podgląd';
            
            // skrypty
            $this->load_js_functions = true;
            
            // values
            $this->tpl_values['id_training'] = $item->id;
            $this->tpl_values['form_name'] = $item->name;
            $this->tpl_values['form_code'] = $item->code;
            $this->tpl_values['form_training_center'] = $item->training_center_name;
            $this->tpl_values['form_description'] = ClassTools::nl2br($item->description);
            $this->tpl_values['form_date_start'] = ClassTraining::getPlDate($item->date_start);
            $this->tpl_values['form_date_end'] = ClassTraining::getPlDate($item->date_end);
            $this->tpl_values['form_active'] = $item->active;
            $this->tpl_values['status'] = $item->status;
            $this->tpl_values['date_update'] = $item->date_update;
            
            $this->tpl_values['log'] = $item->sqlGetLogItem();
            $this->tpl_values['user'] = ClassUser::sqlGetNameSurnameById($item->id_user);
            
            // print_r($this->tpl_values['log']);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/training/view');
        }
        
        /* ************ WYSZUKIWARKA *********** */
        /* ************************************* */
        
        protected function getSearchDefinition(){
            $training_centers = ClassTrainingCenter::sqlGetAllActiveItems();
            $form_training_centers = array();
            
            foreach($training_centers as $key => $training_center){
                $form_training_centers[$key] = $training_center['name'].', '.$training_center['location'];
            }
            
            $form_values = array(
                'class' => 'ClassTraining',
                'controller' => $this->search_controller,
                'form' => array(
                    'id_training' => array(
                        'class' => 'table_id',
                        'type' => 'text'
                    ),
                    'code' => array(
                        'class' => 'table_code',
                        'type' => 'text'
                    ),
                    'name' => array(
                        'class' => 'table_name',
                        'type' => 'text'
                    ),
                    'id_training_centre' => array(
                        'class' => 'table_traning_center',
                        'type' => 'select',
                        'search' => true,
                        'options' => $form_training_centers
                    ),
                    'date_start' => array(
                        'class' => 'table_date_start',
                    ),
                    'date_end' => array(
                        'class' => 'table_date_end',
                    ),
                    'status' => array(
                        'class' => 'table_status',
                        // 'type' => 'select',
                        // 'options' => array(
                            // '0' => 'Usunięta',
                            // '1' => 'Aktywna',
                            // '2' => 'Nieaktywna',
                            // '3' => 'Zakończona',
                        // )
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
                case 'training_add':
                    return $this->add(); // dodawanie
                break;
                case 'training_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'training_save':
                    return $this->edit(); // edycja
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $active = ClassTools::getValue('form_active');
            $form_date_end = ClassTools::getValue('form_date_end');
            $form_code = ClassTools::getValue('form_code');
            
            $item = new ClassTraining();
            $item->name = ClassTools::getValue('form_name');
            $item->code = $form_code != '' ? $form_code : NULL;
            $item->id_training_centre = ClassTools::getValue('form_training_center');
            $item->description = ClassTools::getValue('form_description');
            $item->date_start = ClassTools::getValue('form_date_start');
            $item->date_end = $form_date_end != '' ? $form_date_end : NULL;
            $item->id_user = ClassAuth::getCurrentUserId();
            $item->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowe szkolenie: <b>{$item->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassTraining(ClassTools::getValue('id_training'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class)
            {
                // usuwanie
                if($item->delete())
                {
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto szkolenie: <b>{$item->name}</b>.";
                    return;
                }
                else
                {
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Szkolenie nie istnieje.';
            $_POST = array();
            
            return;
        }
        
        // edytowanie
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassTraining(ClassTools::getValue('id_training'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Szkolenie nie istnieje.";
            }
            
            $active = ClassTools::getValue('form_active');
            $form_date_end = ClassTools::getValue('form_date_end');
            $form_code = ClassTools::getValue('form_code');
            
            $item->name = ClassTools::getValue('form_name');
            $item->code = $form_code != '' ? $form_code : NULL;
            $item->id_training_centre = ClassTools::getValue('form_training_center');
            $item->description = ClassTools::getValue('form_description');
            $item->date_start = ClassTools::getValue('form_date_start');
            $item->date_end = $form_date_end != '' ? $form_date_end : NULL;
            $item->id_user = ClassAuth::getCurrentUserId();
            $item->active = ($active && $active == '1') ? '1' : '0';
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano szkolenie: <b>{$item->name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
