<?php
    class ControllerSoldiers extends ControllerModel{
        protected $search_controller = 'soldiers';
        
        public function __construct(){
            // potrzebne do ajaxa i stronnicowania
            $this->controller_name = 'zolnierze';
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
                        // ladowanie strony z podgladem misji
                        return $this->getPageView();
                    break;
                }
            }
            
            return $this->getPageList();
        }
        
        // strona lista
        protected function getPageList(){
            $this->searchactions();
            $this->actions();
            
            // strony
            $this->controller_name = 'zolnierze';
            $this->using_pages = true;
            $this->count_items = ClassSoldier::sqlGetCountItems($this->search_controller);
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = 'Żołnierze: Lista';
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassSoldier::sqlGetAllItems($this->using_pages, $this->current_page, $this->items_on_page, $this->search_controller);
            
            // ladowanie strony z lista
            return $this->loadTemplate('/soldier/list');
        }
        
        // strona dodawania
        protected function getPageAdd(){
            $this->actions();
            
            // tytul strony
            $this->tpl_title = 'Żołnierz: Dodaj';
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // typow edukacji
            $this->tpl_values['education_types'] = ClassEducationType::sqlGetAllItemsNameById(NULL, false, true);
            
            // ladowanie statusow
            $this->tpl_values['soldier_statuses'] = ClassSoldierStatus::sqlGetAllItemsNameById(NULL, false, true);
            
            // ladowanie jednostek
            $this->tpl_values['form_militaries'] = ClassMilitary::getMilitariesWithGroups();
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'add';
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/form');
        }
        
        // strona edycji
        protected function getPageEdit(){
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $this->tpl_values['wstecz'] = '/zolnierze';
            $this->tpl_values['title'] = 'Edycja Żołnierza';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassSoldier($id_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Żołnierz nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = 'Żołnierze: Edycja';
            
            // skrypty
            $this->load_select2 = true;
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // ladowanie typow edukacji
            $this->tpl_values['education_types'] = ClassEducationType::sqlGetAllItemsNameById(NULL, false, true);
            
            // ladowanie statusow
            $this->tpl_values['soldier_statuses'] = ClassSoldierStatus::sqlGetAllItemsNameById(NULL, false, true);
            
            // ladowanie jednostek
            $this->tpl_values['form_militaries'] = ClassMilitary::getMilitariesWithGroups();
            
            // zmienna ktora decyduje co formularz ma robic
            $this->tpl_values['sew_action'] = 'edit';
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier'                => $item->id,
                'form_sex'                  => $item->sex,
                'form_name'                 => $item->name,
                'form_second_name'          => $item->second_name,
                'form_surname'              => $item->surname,
                'form_date_birthday'        => $item->date_birthday,
                'form_place_birthday'       => $item->place_birthday,
                'form_citizenship'          => $item->citizenship,
                'form_nationality'          => $item->nationality,
                'form_pesel'                => $item->pesel,
                'form_identity_document'    => $item->identity_document,
                'form_mail'                 => $item->mail,
                'form_phone'                => $item->phone,
                'form_height'               => $item->height,
                'form_weight'               => $item->weight,
                'form_shoe_number'          => $item->shoe_number,
                'form_blood_group'          => $item->blood_group,
                'form_name_mother'          => $item->name_mother,
                'form_surname_mother'       => $item->surname_mother,
                'form_name_father'          => $item->name_father,
                'form_surname_father'       => $item->surname_father,
                'form_name_partner'         => $item->name_partner,
                'form_surname_partner'      => $item->surname_partner,
                'form_education_type'       => $item->id_education_type,
                'form_wku'                  => $item->wku,
                'form_health_category'      => $item->health_category,
                'form_military'             => $item->id_military,
                'form_injuries'             => $item->injuries,
                'form_status'               => $item->id_status
            );
            
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/form');
        }
        
        // strona podglądu
        protected function getPageView(){
            global $login;
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $this->tpl_values['wstecz'] = '/zolnierze';
            $this->tpl_values['title'] = 'Podgląd Żołnierza';
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_item = ClassTools::getValue('id_item')){
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassSoldier($id_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Żołnierz nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // css soldier print
            $this->load_soldier_print = true;
            
            // tytul
            $this->tpl_title = 'Żołnierz: Podgląd';
            
            // ladowanie dzieci
            $this->tpl_values['soldier_child'] = $item->getChildrens();
            
            // ladowanie adresow
            $this->tpl_values['soldier_addresses'] = $item->getAddresses();
            
            // ladowanie szkol wyzszych
            $this->tpl_values['soldier_schools'] = $item->getSchools();
            
            // ladowanie jezykow
            $this->tpl_values['soldier_languages'] = $item->getLanguages();
            
            // ladowanie praw jazdy
            $this->tpl_values['soldier_driver_licenses'] = $item->getDriverLicenses();
            
            // ladowanie stopni wojskowych
            $this->tpl_values['soldier_ranks'] = $item->getRanks();
            
            // ladowanie aktualnego stopnia wojskowego
            $this->tpl_values['soldier_actually_rank'] = ClassSoldierRank::getActuallyRank($item->id);
            
            // ladowanie odznaczen
            $this->tpl_values['soldier_badges'] = $item->getBadges();
            
            // ladowanie wyposazenia
            $this->tpl_values['soldier_equipments'] = $item->getEquipments();
            
            // ladowanie misji
            $this->tpl_values['soldier_missions'] = $item->getMissions();
            
            // ladowanie szkolen
            $this->tpl_values['soldier_trainings'] = $item->getTrainings();
            
            // Wypadki i urazy
            $this->tpl_values['form_injuries'] = ClassTools::nl2br($item->injuries);
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier'                => $item->id,
                'form_sex'                  => $item->sex,
                'sex_name'                  => $item->sex_name,
                'form_name'                 => $item->name,
                'form_second_name'          => $item->second_name,
                'form_surname'              => $item->surname,
                'form_date_birthday'        => $item->date_birthday,
                'form_place_birthday'       => $item->place_birthday,
                'form_citizenship'          => $item->citizenship,
                'form_nationality'          => $item->nationality,
                'form_pesel'                => $item->pesel,
                'form_identity_document'    => $item->identity_document,
                'form_mail'                 => $item->mail,
                'form_phone'                => $item->phone,
                'education_type_name'       => $item->education_type_name,
                'form_height'               => $item->height != '' ? $item->height.'cm' : '',
                'form_weight'               => $item->weight != '' ? $item->weight.'kg' : '',
                'form_shoe_number'          => $item->shoe_number,
                'form_blood_group'          => $item->blood_group,
                'form_name_mother'          => $item->name_mother,
                'form_surname_mother'       => $item->surname_mother,
                'form_name_father'          => $item->name_father,
                'form_surname_father'       => $item->surname_father,
                'form_name_partner'         => $item->name_partner,
                'form_surname_partner'      => $item->surname_partner,
                'form_education_type'       => $item->id_education_type,
                'form_wku'                  => $item->wku,
                'form_health_category'      => $item->health_category,
                'form_military'             => $item->id_military,
                'military_name'             => $item->military_name,
                'form_status'               => $item->id_status,
                'status_name'               => $item->status_name
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/view');
        }
        
        /* ************ WYSZUKIWARKA *********** */
        /* ************************************* */
        
        protected function getSearchDefinition(){
            $form_values = array(
                'class' => 'ClassSoldier',
                'controller' => $this->search_controller,
                'form' => array(
                    'id_soldier' => array(
                        'class' => 'table_id',
                        'type' => 'text'
                    ),
                    'name' => array(
                        'class' => 'table_name',
                        'type' => 'text'
                    ),
                    'surname' => array(
                        'class' => 'table_surname',
                        'type' => 'text'
                    ),
                    'mail' => array(
                        'class' => 'table_mail',
                        'type' => 'text'
                    ),
                    'phone' => array(
                        'class' => 'table_phone',
                        'type' => 'text'
                    ),
                    'pesel' => array(
                        'class' => 'table_pesel',
                        'type' => 'text'
                    ),
                    'id_status' => array(
                        'class' => 'table_status',
                        'type' => 'select',
                        'options' => ClassSoldierStatus::sqlGetAllItemsNameById(NULL, false, true)
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
            
            print_r($_POST);
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $this->setValuesTemplateByPost();
            
            switch($_POST['form_action']){
                case 'soldier_add':
                    return $this->add(); // dodawanie
                break;
                case 'soldier_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'soldier_save':
                    return $this->edit(); // edycja
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add()
        {
            $form_height = ClassTools::getValue('form_height');
            $form_weight = ClassTools::getValue('form_weight');
            $form_shoe_number = ClassTools::getValue('form_shoe_number');
            
            $item = new ClassSoldier();
            $item->sex = ClassTools::getValue('form_sex');
            $item->name = ClassTools::getValue('form_name');
            $item->second_name = ClassTools::getValue('form_second_name');
            $item->surname = ClassTools::getValue('form_surname');
            $item->date_birthday = ClassTools::getValue('form_date_birthday');
            $item->place_birthday = ClassTools::getValue('form_place_birthday');
            $item->citizenship = ClassTools::getValue('form_citizenship');
            $item->nationality = ClassTools::getValue('form_nationality');
            $item->pesel = ClassTools::getValue('form_pesel');
            $item->identity_document = ClassTools::getValue('form_identity_document');
            $item->mail = ClassTools::getValue('form_mail');
            $item->phone = ClassTools::getValue('form_phone');
            $item->height = ($form_height != '' && $form_height != '0') ? $form_height : NULL;
            $item->weight = ($form_weight != '' && $form_weight != '0') ? $form_weight : NULL;
            $item->shoe_number = ($form_shoe_number != '' && $form_shoe_number != '0') ? $form_shoe_number : NULL;
            $item->blood_group = ClassTools::getValue('form_blood_group');
            $item->name_mother = ClassTools::getValue('form_name_mother');
            $item->surname_mother = ClassTools::getValue('form_surname_mother');
            $item->name_father = ClassTools::getValue('form_name_father');
            $item->surname_father = ClassTools::getValue('form_surname_father');
            $item->name_partner = ClassTools::getValue('form_name_partner');
            $item->surname_partner = ClassTools::getValue('form_surname_partner');
            $item->id_education_type = ClassTools::getValue('form_education_type');
            $item->wku = ClassTools::getValue('form_wku');
            $item->health_category = ClassTools::getValue('form_health_category');
            $item->id_military = ClassTools::getValue('form_military');
            $item->injuries = ClassTools::getValue('form_injuries');
            $item->id_status = ClassTools::getValue('form_status');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowgo żołnierza: <b>{$item->name} {$item->surname}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassSoldier(ClassTools::getValue('id_soldier'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class)
            {
                // usuwanie
                if($item->delete())
                {
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto żołnierza: <b>{$item->name} {$item->surname}</b>.";
                    return;
                }
                else
                {
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Żołnierz nie istnieje.';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassSoldier(ClassTools::getValue('id_soldier'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Żołnierz nie istnieje.";
            }
            
            $form_height = ClassTools::getValue('form_height');
            $form_weight = ClassTools::getValue('form_weight');
            $form_shoe_number = ClassTools::getValue('form_shoe_number');
            
            $item->sex = ClassTools::getValue('form_sex');
            $item->name = ClassTools::getValue('form_name');
            $item->second_name = ClassTools::getValue('form_second_name');
            $item->surname = ClassTools::getValue('form_surname');
            $item->date_birthday = ClassTools::getValue('form_date_birthday');
            $item->place_birthday = ClassTools::getValue('form_place_birthday');
            $item->citizenship = ClassTools::getValue('form_citizenship');
            $item->nationality = ClassTools::getValue('form_nationality');
            $item->pesel = ClassTools::getValue('form_pesel');
            $item->identity_document = ClassTools::getValue('form_identity_document');
            $item->mail = ClassTools::getValue('form_mail');
            $item->phone = ClassTools::getValue('form_phone');
            $item->height = ($form_height != '' && $form_height != '0') ? $form_height : NULL;
            $item->weight = ($form_weight != '' && $form_weight != '0') ? $form_weight : NULL;
            $item->shoe_number = ($form_shoe_number != '' && $form_shoe_number != '0') ? $form_shoe_number : NULL;
            $item->blood_group = ClassTools::getValue('form_blood_group');
            $item->name_mother = ClassTools::getValue('form_name_mother');
            $item->surname_mother = ClassTools::getValue('form_surname_mother');
            $item->name_father = ClassTools::getValue('form_name_father');
            $item->surname_father = ClassTools::getValue('form_surname_father');
            $item->name_partner = ClassTools::getValue('form_name_partner');
            $item->surname_partner = ClassTools::getValue('form_surname_partner');
            $item->id_education_type = ClassTools::getValue('form_education_type');
            $item->wku = ClassTools::getValue('form_wku');
            $item->health_category = ClassTools::getValue('form_health_category');
            $item->id_military = ClassTools::getValue('form_military');
            $item->injuries = ClassTools::getValue('form_injuries');
            $item->id_status = ClassTools::getValue('form_status');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano żołnierza: <b>{$item->name} {$item->surname}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
