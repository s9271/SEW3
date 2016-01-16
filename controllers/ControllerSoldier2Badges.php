<?php
    class ControllerSoldier2Badges extends ControllerModel{
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
                    case 'odbierz':
                        // ladowanie strony z formularzem
                        return $this->getPageReceive($item);
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
        protected function getPageList($item){
            global $login;
            $this->actions();
            
            // strony
            $this->controller_name = 'odznaczenia';
            $this->using_pages = true;
            $this->count_items = ClassSoldier2Badge::sqlGetCountItems('', array('id_soldier' => $item->id));
            $this->current_page = ClassTools::getValue('number_page') ? ClassTools::getValue('number_page') : '1';
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Odznaczenia";
            
            // ladowanie funkcji
            $this->load_js_functions = true;
            
            // pobieranie wszystkich rekordow
            $this->tpl_values['items'] = ClassSoldier2Badge::sqlGetSoldierBadges($item->id);
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $item->id;
            
            // prawa zalogowanego uzytkownika
            $this->tpl_values['id_login_permission'] = $login->auth_user['id_permission'];
            
            // ladowanie strony z lista
            return $this->loadTemplate('/soldier/badges');
        }
        
        // strona dodawania
        protected function getPageAdd($item){
            $this->actions($item);
            
            // tytul strony
            $this->tpl_title = "{$item->name} {$item->surname}: Odznaczenia: Dodaj";
            
            // ladowanie pluginow
            $this->load_select2 = true;
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // pobieranie odznaczen
            $this->tpl_values['form_badges'] = ClassBadge::sqlGetBadgesWithRanks();
            
            // id zolnierza
            $this->tpl_values['id_soldier'] = $item->id;
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/badges-add');
        }
        
        // strona edycji
        protected function getPageEdit($soldier){
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = "/zolnierze/{$soldier->id}/odznaczenia";
            $title = "{$soldier->name} {$soldier->surname}: Odznaczenia: Edycja";
            
            // sprawdzanie czy id istnieje w linku
            if(!$id_child_item = ClassTools::getValue('id_child_item')){
                $this->tpl_values['wstecz'] = $wstecz;
                $this->tpl_values['title'] = $title;
                $this->alerts['danger'] = 'Brak podanego id';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            $this->actions();
            $this->tpl_values['wstecz'] = $wstecz;
            $this->tpl_values['title'] = $title;
            
            // ladowanie klasy
            $item = new ClassSoldier2Badge($id_child_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Odzneczenie nie jest powiazane z żołnierzem';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie odznaczenie jest przypisane do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Odzneczenie nie jest powiazane z żołnierzem';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Odznaczenia: Edycja";
            
            // skrypty
            $this->load_select2 = true;
            $this->load_datetimepicker = true;
            $this->load_js_functions = true;
            
            // pobieranie odznaczen
            $this->tpl_values['form_badges'] = ClassBadge::sqlGetBadgesWithRanks();
            
            $this->tpl_values['received'] = $item->received;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2badges'         => $item->id,
                'id_badge'                  => $item->id_badge,
                'id_soldier'                => $soldier->id,
                'form_badge_type'           => $item->badge_type,
                'form_date_grant'           => $item->date_grant,
                'form_description'          => $item->description
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            // print_r($this->tpl_values);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/badges-edit');
        }
        
        // strona odeslania
        protected function getPageReceive($soldier){
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = "/zolnierze/{$soldier->id}/odznaczenia";
            $title = "{$soldier->name} {$soldier->surname}: Odznaczenia: Edycja";
            
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
            $item = new ClassSoldier2Badge($id_child_item);
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Odzneczenie nie jest powiazane z żołnierzem';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie odznaczenie jest przypisane do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Odzneczenie nie jest powiazane z żołnierzem';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            
            // sprawdzanie czy misja nie jest oddelegowana
            if($item->received == '1' && (!isset($this->alerts['success']) || $this->alerts['success'] == '')){
                $this->alerts['danger'] = 'Żołnierzowi odebrano odzaczenie.';
                
                // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
                // $this->tpl_values['wstecz'] = "/zolnierze/{$soldier->id}/szkolenia";
                // $this->tpl_values['title'] = "{$soldier->name} {$soldier->surname}: Szkolenia: Odesłanie";
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Odznaczenia: Odbierz";
            
            // skrypty
            $this->load_js_functions = true;
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2badges'         => $item->id,
                'id_soldier'                => $soldier->id,
                'id_badge'                  => $item->id_badge,
                'description_receive'       => $item->description_receive
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/badges-receive');
        }
        
        // strona podglądu
        protected function getPageView($soldier){
            global $login;
            
            // zmienne wyswietlania na wypadek gdy strona z odznaczeniem nie istnieje
            $wstecz = "/zolnierze/{$soldier->id}/odznaczenia";
            $title = "{$soldier->name} {$soldier->surname}: Odznaczenia: Edycja";
            
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
            
            // ladowanie klasy
            $item = new ClassSoldier2Badge($id_child_item);
            
            $this->tpl_values['wstecz'] = $wstecz;
            $this->tpl_values['title'] = $title;
            
            // sprawdzanie czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = 'Odzneczenie nie jest powiazane z żołnierzem.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // sprawdzanie szkola jest przypisana do tego zolnierza
            if($soldier->id != $item->id_soldier){
                $this->alerts['danger'] = 'Odzneczenie nie jest powiazane z żołnierzem.';
                
                // ladowanie strony do wyswietlania bledow
                // zmienne ktore mozna uzyc: wstecz, title oraz alertow
                return $this->loadTemplate('alert');
            }
            
            // tytul
            $this->tpl_title = "{$soldier->name} {$soldier->surname}: Odznaczenia: Podgląd";
            
            // skrypty
            $this->load_js_functions = true;
            
            $this->tpl_values['status_name'] = $item->status_name;
            $this->tpl_values['received'] = $item->received;
            $this->tpl_values['badge_type'] = $item->badge_type;
            $this->tpl_values['date_grant'] = $item->date_grant;
            $this->tpl_values['description'] = ClassTools::nl2br($item->description);
            $this->tpl_values['description_receive'] = ClassTools::nl2br($item->description_receive);
            $this->tpl_values['user_receive'] = ClassUser::sqlGetNameSurnameById($item->id_user);
            $this->tpl_values['date_update'] = date('d.m.Y H:i', strtotime($item->date_update));
            
            // ladowanie szkolenia
            $this->tpl_values['badge'] = new ClassBadge($item->id_badge);
            
            // prawa zalogowanego uzytkownika
            $this->tpl_values['id_login_permission'] = $login->auth_user['id_permission'];
            
            // przypisanie zmiennych formularza do zmiennych klasy
            $array_form_class = array(
                'id_soldier2badges'         => $item->id,
                'id_soldier'                => $soldier->id,
                'id_badge'                  => $item->id_badge
            );
            
            // przypisywanieszych zmiennych do zmiennych formularza
            $this->setValuesTemplateByArrayPost($array_form_class);
            
            // ladowanie strony z formularzem
            return $this->loadTemplate('/soldier/badges-view');
        }
        
        /* *************** AKCJE ************** */
        /* ************************************ */
        
        protected function actions($item = false){
            // sprawdzenie czy zostala wykonana jakas akcja zwiazana z formularzem
            if(!isset($_POST['form_action'])){
                return;
            }
            
            print_r($_POST);
            
            // przypisanie zmiennych posta do zmiennych template
            $this->tpl_values = $this->setValuesTemplateByPost();
            
            switch($_POST['form_action']){
                case 'badge_add':
                    return $this->add($item); // dodawanie
                break;
                case 'badge_delete':
                    return $this->delete(); // usuwanie
                break;
                case 'badge_save':
                    return $this->edit(); // zapis
                break;
                case 'badge_receive':
                    return $this->receive(); // odebranie
                break;
            }
            
            return;
        }
        
        // dodawanie
        protected function add($soldier)
        {
            $item = new ClassSoldier2Badge();
            
            $item->id_soldier_tmp = $soldier->id;
            $item->id_badge = ClassTools::getValue('form_badge');
            $item->badge_type = ClassTools::getValue('form_badge_type');
            $item->date_grant = ClassTools::getValue('form_date_grant');
            $item->description = ClassTools::getValue('form_description');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->add()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat sukcesu
            $this->alerts['success'] = "Poprawnie dodano nowe odznaczenie.";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // usuwanie
        protected function delete()
        {
            // ladowanie klasy
            $item = new ClassSoldier2Badge(ClassTools::getValue('id_soldier2badges'));
            $item->id_badge = ClassTools::getValue('id_badge');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if($item->load_class){
                // usuwanie
                if($item->delete()){
                    // komunikat
                    $this->alerts['success'] = "Poprawnie usunięto odznaczenie: <b>{$item->badge_name}</b>";
                    return;
                }else{
                    // bledy w przypadku problemow z usunieciem
                    $this->alerts['danger'] = $item->errors;
                    return;
                }
            }
            
            $this->alerts['danger'] = 'Żołnierz nie jest powiązany z odznaczeniem.';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function edit()
        {
            // ladowanie klasy
            $item = new ClassSoldier2Badge(ClassTools::getValue('id_soldier2badges'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Odznaczenie żołnierza nie istnieje.";
            }
            
            $item->id_badge = ClassTools::getValue('id_badge');
            $item->badge_type = ClassTools::getValue('form_badge_type');
            $item->date_grant = ClassTools::getValue('form_date_grant');
            $item->description = ClassTools::getValue('form_description');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->update()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie zaktualizowano odznaczenie żołnierza: <b>{$item->badge_name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
        
        // edycja
        protected function receive()
        {
            // ladowanie klasy
            $item = new ClassSoldier2Badge(ClassTools::getValue('id_soldier2badges'));
            
            // sprawdza czy klasa zostala poprawnie zaladowana
            if(!$item->load_class){
                $this->alerts['danger'] = "Odznaczenie żołnierza nie istnieje.";
            }
            
            $item->description_receive = ClassTools::getValue('form_description_receive');
            $item->id_badge = ClassTools::getValue('id_badge');
            $item->id_soldier = ClassTools::getValue('id_soldier');
            $item->id_user = ClassAuth::getCurrentUserId();
            
            // komunikaty bledu
            if(!$item->receive()){
                $this->alerts['danger'] = $item->errors;
                return;
            }
            
            // komunikat
            $this->alerts['success'] = "Poprawnie odebrano odznaczenie żołnierza: <b>{$item->badge_name}</b>";
            
            // czyszczeie zmiennych wyswietlania
            $this->tpl_values = '';
            $_POST = array();
            
            return;
        }
    }
?>
