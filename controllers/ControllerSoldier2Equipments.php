<?php
    class ControllerSoldier2Equipments extends ControllerModel{
        protected $using_top_title = true;
        protected $top_ico = 'shield';
        
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
            $this->top_title = 'Lista wyposażenia żołnierza';
            
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
            
            // sprawdzanie czy jest sie na podstronie
            if($page_action = ClassTools::getValue('page_action')){
                switch($page_action){
                    case 'dodaj':
                        // ladowanie strony z formularzem
                        return $this->getPageAdd($item);
                    break;
                    case 'edytuj':
                        // ladowanie strony z formularzem
                        return $this->getPageEdit($item);
                    break;
                    case 'podglad':
                        // ladowanie strony z formularzem
                        return $this->getPageView($item);
                    break;
                    case 'zwroc':
                        // ladowanie strony z formularzem
                        return $this->getPageReturn($item);
                    break;
                }
            }
            
            return $this->getPageList($item);
        }
        
        // strona lista
        protected function getPageList($item){
            global $login;
            $this->actions();
            
            // strony
            $this->controller_name = 'wyposazenie';
            $this->using_pages = true;
            $this->count_items = ClassSoldier2Equipment::sqlGetCountItems('', array('id_soldier' => $item->id));
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Wyposażenie";
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassSoldier2Equipment::sqlGetSoldierEquipments($item->id);
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $item->id;
            
            // prawa zalogowanego uzytkownika
            $this->tpl_values['id_login_permission'] = $login->auth_user['id_permission'];
            
            // ladowanie strony z lista
            return $this->loadTemplate('/soldier/equipments');
        }
        
        // strona dodawania
        protected function getPageAdd($soldier){
            $this->actions($soldier);
            
            // tylul na pasku
            $this->top_title = 'Dodaj wyposażenie żołnierza';
            
            $this->breadcroumb[] = array('name' => "Dodaj", 'link' => "/zolnierze/{$soldier->id}/wyposazenie/dodaj");
            
            // tytul strony
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Wyposażenie: Dodaj";
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // nazwa klasy i funkcji z ktorej bedzie pobierac opcje do selekta (w klasie musi istniec statyczna funkcja do obslugi tego ajaxa)
            $this->tpl_values['ajax_class'] = 'Soldier2Equipment';
            $this->tpl_values['ajax_function'] = 'sqlSearchEquipmentsForSoldier';
            
            // Zaznaczona misja
            $this->tpl_values['equipments_selectes'] = '';
            
            if($id_equipment = ClassTools::getValue('form_equipment')){
                $item = new ClassEquipment($id_equipment);
                
                if($item->load_class && $item->active == '1'){
                    $this->tpl_values['equipments_selectes'] = '<option value="'.$id_equipment.'" selected="selected">'.$item->name.'</option>';
                }
            }
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $soldier->id;
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/equipments-add');
        }
        
        // strona edycji
        protected function getPageEdit($soldier)
        {
            // tylul na pasku
            $this->top_title = 'Edytuj wyposażenie żołnierza';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = "/zolnierze/{$soldier->id}/wyposazenie";
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_child_item = ClassTools::getValue('id_child_item')){
                $this->tpl_values['wstecz'] = $wstecz;
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            $this->tpl_values['wstecz'] = $wstecz;
            
            // ladowanie klasy
            $item = new ClassSoldier2Equipment($id_child_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Wyposażenie nie jest powiazane z żołnierzem';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie odznaczenie jest przypisane do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Wyposażenie nie jest powiazane z żołnierzem';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Wyposażenie: Edycja";
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->equipment_name), 'link' => "/zolnierze/{$soldier->id}/wyposazenie/podglad/{$item->id}");
            $this->breadcroumb[] = array('name' => "Edytuj", 'link' => "/zolnierze/{$soldier->id}/wyposazenie/edytuj/{$item->id}");
            
            // skrypty
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            $this->tpl_values['returned'] = $item->returned;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2equipments'     => $item->id,
                'id_equipment'              => $item->id_equipment,
                'id_soldier'                => $soldier->id,
                'form_count'                => $item->equipment_count,
                'form_date'                 => $item->date_equipment_add,
                'form_description'          => $item->description
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            // print_r($this->tpl_values);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/equipments-edit');
        }
        
        // strona odeslania
        protected function getPageReturn($soldier)
        {
            // tylul na pasku
            $this->top_title = 'Zwróć wyposażenie żołnierza';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = "/zolnierze/{$soldier->id}/wyposazenie";
            $title = "{$soldier->name} {$soldier->surname}: Wyposażenie: Zwróć";
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_child_item = ClassTools::getValue('id_child_item')){
                $this->tpl_values['wstecz'] = $wstecz;
                $this->tpl_values['title'] = $title;
                $this->alerts['danger'] = 'Brak podanego id.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            $this->tpl_values['wstecz'] = $wstecz;
            $this->tpl_values['title'] = $title;
            
            // ladowanie klasy
            $item = new ClassSoldier2Equipment($id_child_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Wyposażenie nie jest powiazane z żołnierzem';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie odznaczenie jest przypisane do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Wyposażenie nie jest powiazane z żołnierzem';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            
            // sprawdzanie czy zolnierz posiada to wyposazenie
            if($item->returned == '1' && (!isset($this->alerts['success']) || $this->alerts['success'] == '')){
                $this->alerts['danger'] = 'Żołnierzowi już zwrócił to wyposażenie.';
                
                // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
                // $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/szkolenia";
                // $this->tpl_values['title'] = "{$soldier->name} {$soldier->surname}: Szkolenia: Odesłanie";
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Wyposażenie: Zwróć";
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->equipment_name), 'link' => "/zolnierze/{$soldier->id}/wyposazenie/podglad/{$item->id}");
            $this->breadcroumb[] = array('name' => "Zwróć", 'link' => "/zolnierze/{$soldier->id}/wyposazenie/zwroc/{$item->id}");
            
            // skrypty
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2equipments'         => $item->id,
                'id_soldier'                    => $soldier->id,
                'id_equipment'                  => $item->id_equipment,
                'form_date'                     => $item->date_return,
                'form_description_return'       => $item->description_return
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/equipments-return');
        }
        
        // strona podglądu
        protected function getPageView($soldier){
            global $login;
            
            // tylul na pasku
            $this->top_title = 'Podgląd wyposażenia żołnierza';
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = "/zolnierze/{$soldier->id}/wyposażenie";
            
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
            $item = new ClassSoldier2Equipment($id_child_item);
            
            $this->tpl_values['wstecz'] = $wstecz;
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Wyposażenie nie jest powiazane z żołnierzem.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie szkola jest przypisana do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Wyposażenie nie jest powiazane z żołnierzem.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Wyposażenie: Podgląd";
            
            $this->breadcroumb[] = array('name' => htmlspecialchars($item->equipment_name), 'link' => "/zolnierze/{$soldier->id}/wyposazenie/podglad/{$item->id}");
            
            // skrypty
            $this->load_js_functions = true;
            
            $this->tpl_values['status_name'] = $item->status_name;
            $this->tpl_values['returned'] = $item->returned;
            $this->tpl_values['date_equipment_add'] = $item->date_equipment_add;
            $this->tpl_values['date_return'] = $item->date_return;
            $this->tpl_values['equipment_count'] = $item->equipment_count;
            $this->tpl_values['description'] = ClassTools::nl2br($item->description);
            $this->tpl_values['description_return'] = ClassTools::nl2br($item->description_return);
            $this->tpl_values['date_update'] = date('d.m.Y H:i', strtotime($item->date_update));
            
            // ladowanie szkolenia
            $this->tpl_values['equipment'] = new ClassEquipment($item->id_equipment);
            
            // prawa zalogowanego uzytkownika
            $this->tpl_values['id_login_permission'] = $login->auth_user['id_permission'];
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2equipments'         => $item->id,
                'id_soldier'                    => $soldier->id,
                'id_equipment'                  => $item->id_equipment
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/equipments-view');
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
                case 'equipment_add':
                    return $this->add($item); // dodawanie
                break;
                case 'equipment_save':
                    return $this->edit(); // edytowanie
                break;
                case 'equipment_return':
                    return $this->equipmentReturn(); // oddelegowanie
                break;
                case 'equipment_delete':
                    return $this->delete(); // usuwanie
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add($soldier)
        {
            $item = new ClassSoldier2Equipment();
            $item->id_soldier_tmp = $soldier->id;
            $item->id_equipment = ClassTools::getValue('form_equipment');
            $item->equipment_count = ClassTools::getValue('form_count');
            $item->date_equipment_add = ClassTools::getValue('form_date');
            $item->description = ClassTools::getValue('form_description');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowe wyposażenie: <b>{$item->equipment_name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete(){
            // ladowanie klasy
            $item = new ClassSoldier2Equipment(ClassTools::getValue('id_soldier2equipments'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Wyposażenie żołnierza nie istnieje.";
            }
            
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_equipment = ClassTools::getValue('id_equipment');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class){
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto wyposażenie.";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Żołnierz nie jest powiązany z tym wyposażeniem.';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassSoldier2Equipment(ClassTools::getValue('id_soldier2equipments'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Wyposażenie żołnierza nie istnieje.";
                return;
            }
            
            $item->id_equipment = ClassTools::getValue('id_equipment');
            $item->equipment_count = ClassTools::getValue('form_count');
            $item->date_equipment_add = ClassTools::getValue('form_date');
            $item->description = ClassTools::getValue('form_description');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano wyposażenie żołnierza: <b>{$item->equipment_name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // zwracanie
        protected function equipmentReturn()
        {
            // ladowanie klasy
            $item = new ClassSoldier2Equipment(ClassTools::getValue('id_soldier2equipments'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Wyposażenie żołnierza nie istnieje.";
                return;
            }
            
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_equipment = ClassTools::getValue('id_equipment');
            $item->description_return = ClassTools::getValue('form_description_return');
            $item->date_return = ClassTools::getValue('form_date');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->equipmentReturn()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zwrócono wyposazenie żołnierza.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
