<?php
    class ControllerSoldier2Trainings extends ControllerModel{
        protected $using_top_title = true;
        protected $top_ico = 'graduation-cap';
        
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
            $this->top_title = 'Lista szkoleń żołnierza';
            
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
            $this->breadcroumb[] = array('name' => "Szkolenia", 'link' => "/zolnierze/{$item->id}/szkolenia");
            
            // sprawdzanie czy jest sie na podstronie
            if($page_action = ClassTools::getValue('page_action')){
                switch($page_action){
                    case 'edytuj':
                        // ladowanie strony z formularzem
                        return $this->getPageEdit($item);
                    break;
                    case 'odeslij':
                        // ladowanie strony z formularzem
                        return $this->getPageReturn($item);
                    break;
                    case 'podglad':
                        // ladowanie strony z formularzem
                        return $this->getPageView($item);
                    break;
                }
            }
            
            return $this->getPageList($item);
        }
        
        // strona lista
        protected function getPageList($item)
        {
            global $login;
            
            $this->actions($item);
            
            // strony
            $this->controller_name = 'szkolenia';
            $this->using_pages = true;
            $this->count_items = ClassSoldier2Training::sqlGetCountItems('', array('id_soldier' => $item->id));
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Szkolenia";
            
            // ladowanie funkcji
            $this->load_select2 = true;
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassSoldier2Training::sqlGetSoldierTrainings($item->id, $this->using_pages, $this->current_page, $this->items_on_page);
            
            $this->tpl_values['id_soldier'] = $item->id;
            
            // nazwa klasy i funkcji z ktorej bedzie pobierac opcje do selekta (w klasie musi istniec statyczna funkcja do obslugi tego ajaxa)
            $this->tpl_values['ajax_class'] = 'Soldier2Training';
            $this->tpl_values['ajax_function'] = 'sqlSearchTrainingsForSoldier';
            
            // Zaznaczona misja
            $this->tpl_values['training_selectes'] = '';
            
            if($id_training = ClassTools::getValue('form_training')){
                $training = new ClassTraining($id_training);
                
                if($training->load_class && $training->active == '1'){
                    $this->tpl_values['training_selectes'] = '<option value="'.$id_training.'" selected="selected">'.$training->name.'</option>';
                }
            }
            
            // prawa zalogowanego uzytkownika
            $this->tpl_values['id_login_permission'] = $login->auth_user['id_permission'];
            
            // ladowanie strony z lista
            return $this->loadTemplate('/soldier/trainings');
        }
        
        // strona edycji
        protected function getPageEdit($soldier)
        {
            // tylul na pasku
            $this->top_title = 'Edycja szkolenia żołnierza';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/szkolenia";
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_child_item = ClassTools::getValue('id_child_item')){
                $this->alerts['danger'] = 'Brak podanego id.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassSoldier2Training($id_child_item);
            $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/szkolenia";
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Przypisanie szkolenia do żołnierza nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie szkola jest przypisana do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Szkolenie nie jest przypisane do tego żołnierza.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie czy szkolenie nie jest odeslany
            if($item->returned == '1'){
                $this->alerts['danger'] = 'Żołnierz został odesłany od tego szkolenia.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Szkolenia: Edycja";
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->training_name), 'link' => "/zolnierze/{$soldier->id}/szkolenia/podglad/{$item->id}");
            $this->breadcroumb[] = array('name' => "Edytuj", 'link' => "/zolnierze/{$soldier->id}/szkolenia/edytuj/{$item->id}");
            
            // skrypty
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2trainings'      => $item->id,
                'id_soldier'                => $soldier->id,
                'id_training'               => $item->id_training,
                'form_date'                 => $item->date_training_add,
                'form_description'          => $item->description
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/trainings-form');
        }
        
        // strona podglądu
        protected function getPageView($soldier){
            global $login;
            
            // tylul na pasku
            $this->top_title = 'Podgląd szkolenia żołnierza';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = "/zolnierze/{$soldier->id}/szkolenia";
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_child_item = ClassTools::getValue('id_child_item')){
                $this->tpl_values['wstecz'] = $wstecz;
                $this->alerts['danger'] = 'Brak podanego id.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            // ladowanie klasy
            $item = new ClassSoldier2Training($id_child_item);
            
            $this->tpl_values['wstecz'] = $wstecz;
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Przypisanie szkolenia do żołnierza nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie szkola jest przypisana do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Szkolenie nie jest przypisane do tego żołnierza.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Szkolenia: Podgląd";
            
            // skrypty
            $this->load_js_functions = true;
            
            $this->tpl_values['status_name'] = $item->status_name;
            $this->tpl_values['returned'] = $item->returned;
            $this->tpl_values['description'] = ClassTools::nl2br($item->description);
            $this->tpl_values['description_return'] = ClassTools::nl2br($item->description_return);
            $this->tpl_values['user_return'] = ClassUser::sqlGetNameSurnameById($item->id_user);
            $this->tpl_values['date_update'] = date('d.m.Y H:i', strtotime($item->date_update));
            
            // ladowanie szkolenia
            $this->tpl_values['training'] = new ClassTraining($item->id_training);
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($this->tpl_values['training']->name), 'link' => "/zolnierze/{$soldier->id}/szkolenia/podglad/{$item->id}");
            
            
            // prawa zalogowanego uzytkownika
            $this->tpl_values['id_login_permission'] = $login->auth_user['id_permission'];
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2trainings'      => $item->id,
                'id_soldier'                => $soldier->id,
                'date_training_add'         => $item->date_training_add,
                'date_training_return'      => $item->date_training_return,
                'id_training'               => $item->id_training
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/trainings-view');
        }
        
        // strona odeslania
        protected function getPageReturn($soldier)
        {
            // tylul na pasku
            $this->top_title = 'Odeślij żołnierza ze szkolenia';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/szkolenia";
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_child_item = ClassTools::getValue('id_child_item')){
                $this->alerts['danger'] = 'Brak podanego id.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            
            $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/szkolenia";
            
            // ladowanie klasy
            $item = new ClassSoldier2Training($id_child_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Przypisanie szkolenia do żołnierza nie istnieje';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie szkola jest przypisana do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Szkolenie nie jest przypisane do tego żołnierza';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie czy misja nie jest oddelegowana
            if($item->returned == '1' && (!isset($this->alerts['success']) || $this->alerts['success'] == '')){
                $this->alerts['danger'] = 'Żołnierz został odesłany od tego szkolenia.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Szkolenia: Odesłanie";
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->training_name), 'link' => "/zolnierze/{$soldier->id}/szkolenia/podglad/{$item->id}");
            $this->breadcroumb[] = array('name' => "Odeślij", 'link' => "/zolnierze/{$soldier->id}/szkolenia/odeslij/{$item->id}");
            
            // skrypty
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2trainings'      => $item->id,
                'id_soldier'                => $soldier->id,
                'id_training'               => $item->id_training,
                'form_date'                 => $item->date_training_return,
                'description_return'        => $item->description_return
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/trainings-return');
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        protected function actions($item = false){
            // sprawdzenie czy zostala wykonana jakas akcja zwiazana z formularzem
            if(!isset($_POST['form_action'])){
                return;
            }
            
            // print_r($_POST);
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $this->setValuesTemplateByPost();
            
            switch($_POST['form_action']){
                case 'training_add':
                    return $this->add($item); // dodawanie
                break;
                case 'training_save':
                    return $this->edit(); // edytowanie
                break;
                case 'training_return':
                    return $this->trainingReturn(); // odeslanie
                break;
                case 'training_delete':
                    return $this->delete(); // usuwanie
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add($soldier)
        {
            $item = new ClassSoldier2Training();
            $item->id_soldier_tmp = $soldier->id;
            $item->id_training = ClassTools::getValue('form_training');
            $item->description = ClassTools::getValue('form_description');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->date_training_add = ClassTools::getValue('form_date');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowe szkolenie: <b>{$item->training_name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassSoldier2Training(ClassTools::getValue('id_soldier2trainings'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Szkolenie żołnierza nie istnieje.";
                return;
            }
            
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_training = ClassTools::getValue('id_training');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class){
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto szkolenie.";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Żołnierz nie jest powiązany z tym szkoleniem.';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassSoldier2Training(ClassTools::getValue('id_soldier2trainings'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Szkolenie żołnierza nie istnieje.";
                return;
            }
            
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_training = ClassTools::getValue('id_training');
            $item->date_training_add = ClassTools::getValue('form_date');
            $item->description = ClassTools::getValue('form_description');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano szkolenie żołnierza.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // oddelegowanie
        protected function trainingReturn()
        {
            // ladowanie klasy
            $item = new ClassSoldier2Training(ClassTools::getValue('id_soldier2trainings'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Szkolenie żołnierza nie istnieje.";
                return;
            }
            
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_training = ClassTools::getValue('id_training');
            $item->description_return = ClassTools::getValue('form_description_return');
            $item->date_training_return = ClassTools::getValue('form_date');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->trainingReturn()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie odesłano żołnierza ze szkolenia.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
