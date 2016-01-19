<div id="soldier_equipments_view" class="container mar_custom">
    <div class="jumbotron">
        <h1 class="controller_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <h4 class="page-header no_margin_top">Dane osoby</h4>
        
        <div class="row">
            <div class="col-sm-3 text-right row_title">Płeć:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['sex_name']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Imię:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_name']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Drugie Imię:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_second_name']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Nazwisko:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_surname']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Data urodzenia:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_date_birthday']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Miejsce urodzenia:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_place_birthday']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">PESEL:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_pesel']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Seria i Nr dowodu osobistego:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_identity_document']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Narodowość:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_nationality']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Obywatelstwo:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_citizenship']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Adres e-mail:</div>
            <div class="col-sm-9">
                <div class="row_content"><a href="mailto:<?php echo $this->tpl_values['form_mail']; ?>"><?php echo $this->tpl_values['form_mail']; ?></a></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Telefon:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_phone']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Wykształcenie:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['education_type_name']; ?></div>
            </div>
        </div>
        
        
        
        <h4 class="page-header">Dane osoby - dodatkowe</h4>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Wzrost:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_height']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Waga:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_weight']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Nr buta:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_shoe_number']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Grupa krwi:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_blood_group']; ?></div>
            </div>
        </div>
        
        
        
        <h4 class="page-header">Dane osoby - rodzina</h4>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Imię Matki:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_name_mother']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Nazwisko panieńskie Matki:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_surname_mother']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Imię Ojca:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_name_father']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Nazwisko Ojca:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_surname_father']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Imię małżonki(a):</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_name_partner']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Nazwisko małżonki(a):</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_surname_partner']; ?></div>
            </div>
        </div>
        
        
        
        <h4 class="page-header">Dane osoby - dzieci</h4>
        
        <div class="row">
            <div class="col-sm-12">
        
                <?php if($this->tpl_values['soldier_child']){ ?>
            
                <table class="table table_child table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_name">Imię</th>
                            <th class="table_surname">Nazwisko</th>
                            <th class="table_birthday">Data urodzenia</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php foreach ($this->tpl_values['soldier_child'] as $key => $item){ ?>
                
                        <tr>
                            <td class="table_name"><?php echo $item['name']; ?></td>
                            <td class="table_surname"><?php echo $item['surname']; ?></td>
                            <td class="table_birthday"><?php echo $item['date_birthday']; ?></td>
                        </tr>
                    
                        <?php } ?>
                
                    </tbody>
                </table>
                
                <?php }else{ ?>
                
                Żołnierz nie posiada dzieci.
                
                <?php } ?>
        
            </div>
        </div>
        
        <h4 class="page-header">Dane osoby - adresy</h4>
        
        <div class="row">
            <div class="col-sm-12">
        
                <?php if($this->tpl_values['soldier_addresses']){ ?>
            
                <table class="table table_addresses table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_street">Ulica</th>
                            <th class="table_postcode">Kod pocztowy</th>
                            <th class="table_city">Miasto</th>
                            <th class="table_country">Kraj</th>
                            <th class="table_type">Typ</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php foreach ($this->tpl_values['soldier_addresses'] as $key => $item){ ?>
                
                        <tr>
                            <td class="table_street"><?php echo $item['street']; ?></td>
                            <td class="table_postcode"><?php echo $item['postcode']; ?></td>
                            <td class="table_city"><?php echo $item['city']; ?></td>
                            <td class="table_country"><?php echo $item['country']; ?></td>
                            <td class="table_type">
                            
                            <?php
                                if (isset($item['soldier_address_types']) && $item['soldier_address_types']){
                                    foreach ($item['soldier_address_types'] as $soldier_address_type){
                                        echo '<span class="center-block">'.htmlspecialchars($soldier_address_type['name']).'</span>';
                                    }
                                }
                            ?>
                            </td>
                        </tr>
                    
                        <?php } ?>
                
                    </tbody>
                </table>
                
                <?php }else{ ?>
                
                Żołnierz nie posiada adresow.
                
                <?php } ?>
        
            </div>
        </div>
        
        
        
        <h4 class="page-header purple">Wykształcenie - Szkoły wyższe</h4>
        
        <div class="row">
            <div class="col-sm-12">
        
                <?php if($this->tpl_values['soldier_schools']){ ?>
            
                <table class="table table_schools table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_name">Nazwa szkoły</th>
                            <th class="table_address">Adres szkoły</th>
                            <th class="table_specialization">Specjalizacja</th>
                            <th class="table_academic_degree">Tytuł</th>
                            <th class="table_date_start">Data rozpoczęcia</th>
                            <th class="table_date_end">Data zakończenia</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php foreach ($this->tpl_values['soldier_schools'] as $key => $item){ ?>
                
                        <tr>
                            <td class="table_name"><?php echo $item['name']; ?></td>
                            <td class="table_address"><?php echo $item['address']; ?></td>
                            <td class="table_specialization"><?php echo $item['specialization']; ?></td>
                            <td class="table_academic_degree"><?php echo $item['academic_degree_name']; ?></td>
                            <td class="table_date_start"><?php echo $item['date_start']; ?></td>
                            <td class="table_date_end"><?php echo $item['date_end']; ?></td>
                        </tr>
                    
                        <?php } ?>
                
                    </tbody>
                </table>
                
                <?php }else{ ?>
                
                Żołnierz nie posiada szkół wyższych.
                
                <?php } ?>
        
            </div>
        </div>
        
        
        
        <h4 class="page-header purple">Wykształcenie - Języki</h4>
        
        <div class="row">
            <div class="col-sm-12">
        
                <?php if($this->tpl_values['soldier_languages']){ ?>
            
                <table class="table table_languages table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_name">Nazwa</th>
                            <th class="table_level">Stopień zaawansowania</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php foreach ($this->tpl_values['soldier_languages'] as $key => $item){ ?>
                
                        <tr>
                            <td class="table_name"><?php echo $item['name']; ?></td>
                            <td class="table_level"><?php echo $item['language_level_name']; ?></td>
                        </tr>
                    
                        <?php } ?>
                
                    </tbody>
                </table>
                
                <?php }else{ ?>
                
                Żołnierz nie zna języków.
                
                <?php } ?>
        
            </div>
        </div>
        
        
        
        <h4 class="page-header purple">Wykształcenie - Prawo jazdy</h4>
        
        <div class="row">
            <div class="col-sm-12">
        
                <?php if($this->tpl_values['soldier_driver_licenses']){ ?>
            
                <table class="table table_driver_licenses table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_category">Kategoria</th>
                            <th class="table_date_start">Data ważności od</th>
                            <th class="table_date_end">Data ważności do</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php foreach ($this->tpl_values['soldier_driver_licenses'] as $key => $item){ ?>
                
                        <tr>
                            <td class="table_category"><?php echo $item['drive_category_name']; ?></td>
                            <td class="table_date_start"><?php echo $item['date_start']; ?></td>
                            <td class="table_date_end"><?php echo $item['date_end']; ?></td>
                        </tr>
                    
                        <?php } ?>
                
                    </tbody>
                </table>
                
                <?php }else{ ?>
                
                Żołnierz nie posiada prawa jazdy.
                
                <?php } ?>
        
            </div>
        </div>
        
        
        
        <h4 class="page-header danger">Informacje wojskowe</h4>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">WKU - Wojskowa Komenda Uzupełnień:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_wku']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Kategoria zdrowia:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_health_category']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Wypadki i urazy:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['form_injuries']; ?></div>
            </div>
        </div>
        
        <?php if($this->tpl_values['soldier_actually_rank']){ ?>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Aktualny stopień wojskowy:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['soldier_actually_rank']['name']; ?></div>
            </div>
        </div>
        
        <?php } ?>
        
        
        
        <h4 class="page-header danger">Informacje wojskowe - Stopień wojskowy</h4>
        
        <div class="row">
            <div class="col-sm-12">
        
                <?php if($this->tpl_values['soldier_ranks']){ ?>
            
                <table class="table table_ranks table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_name">Nazwa stopnia wojskowego</th>
                            <th class="table_date">Data przyznania</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php foreach ($this->tpl_values['soldier_ranks'] as $key => $item){ ?>
                
                        <tr>
                            <td class="table_name"><?php echo $item['name']; ?></td>
                            <td class="table_date"><?php echo $item['date_add_rank']; ?></td>
                        </tr>
                    
                        <?php } ?>
                
                    </tbody>
                </table>
                
                <?php }else{ ?>
                
                Żołnierz nie posiada stopnia wojskowego.
                
                <?php } ?>
        
            </div>
        </div>
        
        
        
        <h4 class="page-header danger">Informacje wojskowe - Aktywne odznaczenia</h4>
        
        <div class="row">
            <div class="col-sm-12">
        
                <?php if($this->tpl_values['soldier_badges']){ ?>
            
                <table class="table table_badges table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_name">Nazwa odznaczenia</th>
                            <th class="table_rank">Ilość stopni odznaczenia</th>
                            <th class="table_type">Typ odznaczenia</th>
                            <th class="table_date_grant">Data przyznania</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php foreach ($this->tpl_values['soldier_badges'] as $key => $item){ ?>
                
                        <tr>
                            <td class="table_name"><?php echo $item['badge_name']; ?></td>
                            <td class="table_rank"><?php echo $item['badge_rank_name']; ?></td>
                            <td class="table_type"><?php echo $item['badge_type']; ?></td>
                            <td class="table_date_grant"><?php echo $item['date_grant']; ?></td>
                        </tr>
                    
                        <?php } ?>
                
                    </tbody>
                </table>
                
                <?php }else{ ?>
                
                Żołnierz nie posiada aktywnych odznaczeń.
                
                <?php } ?>
        
            </div>
        </div>
        
        
        
        <h4 class="page-header danger">Informacje wojskowe - Posiadane wyposażenie</h4>
        
        <div class="row">
            <div class="col-sm-12">
        
                <?php if($this->tpl_values['soldier_equipments']){ ?>
            
                <table class="table table_equipments table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_name">Nazwa wyposażenia</th>
                            <th class="table_name_type">Typ wyposażenia</th>
                            <th class="table_count">Ilość</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php foreach ($this->tpl_values['soldier_equipments'] as $key => $item){ ?>
                
                        <tr>
                            <td class="table_name"><?php echo $item['equipment_name']; ?></td>
                            <td class="table_name_type"><?php echo $item['equipment_name_type']; ?></td>
                            <td class="table_count"><?php echo $item['equipment_count']; ?></td>
                        </tr>
                    
                        <?php } ?>
                
                    </tbody>
                </table>
                
                <?php }else{ ?>
                
                Żołnierz nie posiada wyposażenia.
                
                <?php } ?>
        
            </div>
        </div>
        
        
        
        <h4 class="page-header danger">Informacje wojskowe - Aktualne i przyszłe misje</h4>
        
        <div class="row">
            <div class="col-sm-12">
        
                <?php if($this->tpl_values['soldier_missions']){ ?>
            
                <table class="table table_missions table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_name">Nazwa misji</th>
                            <th class="table_date_start">Data rozpoczęcia</th>
                            <th class="table_date_end">Data zakończenia</th>
                            <th class="table_status">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php foreach ($this->tpl_values['soldier_missions'] as $key => $item){ ?>
                
                        <tr>
                            <td class="table_name"><?php echo $item['name']; ?></td>
                            <td class="table_date_start"><?php echo $item['date_start']; ?></td>
                            <td class="table_date_end"><?php echo $item['date_end']; ?></td>
                            <td class="table_status"><?php echo $item['status_name']; ?></td>
                        </tr>
                    
                        <?php } ?>
                
                    </tbody>
                </table>
                
                <?php }else{ ?>
                
                Żołnierz nie jest ani nie będzie miał w chwili obecnej misji.
                
                <?php } ?>
        
            </div>
        </div>
        
        
        
        <h4 class="page-header danger">Informacje wojskowe - Aktualne i przyszłe szkolenia</h4>
        
        <div class="row">
            <div class="col-sm-12">
        
                <?php if($this->tpl_values['soldier_trainings']){ ?>
            
                <table class="table table_trainings table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_name">Nazwa szkolenia</th>
                            <th class="table_date_start">Data rozpoczęcia</th>
                            <th class="table_date_end">Data zakończenia</th>
                            <th class="table_status">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                
                        <?php foreach ($this->tpl_values['soldier_trainings'] as $key => $item){ ?>
                
                        <tr>
                            <td class="table_name"><?php echo $item['name']; ?></td>
                            <td class="table_date_start"><?php echo $item['date_start']; ?></td>
                            <td class="table_date_end"><?php echo $item['date_end']; ?></td>
                            <td class="table_status"><?php echo $item['status_name']; ?></td>
                        </tr>
                    
                        <?php } ?>
                
                    </tbody>
                </table>
                
                <?php }else{ ?>
                
                Żołnierz nie jest ani nie będzie miał w chwili obecnej szkolenia.
                
                <?php } ?>
        
            </div>
        </div>
        
        
        
        <h4 class="page-header warning">Status żołnierza</h4>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Status żołnierza:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['status_name']; ?></div>
            </div>
        </div>
        
        
        
        <div class="row margin_top_50">
            <div class="col-sm-12">
                <a href="/zolnierze/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy żołnierzy</a>
                
                <a href="#" class="btn btn-primary pull-right mar_button"><span class="glyphicon glyphicon-print"></span>Drukuj</a>
            </div>
        </div>
        
    </div>
</div>
