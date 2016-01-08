<div id="soldier_form" class="container mar_custom">
    <div class="jumbotron">
        <h1 class="controller_title"><?php echo ((isset($this->tpl_values['sew_action']) && $this->tpl_values['sew_action'] == 'add') ? 'Dodaj żołnierza' : 'Edytuj żołnierza'); ?></h1>
        
        <?php $this->getAlerts(true); ?>
    
        <form method="post" class="form-horizontal">

            <div class="form-group">
                <div class="col-sm-12 require_text">
                    * - wymagane pola
                </div>
            </div>
            
            <h4 class="page-header no_margin_top">Dane osoby</h4>
                
            <div class="form-group">
                <div class="col-sm-12">
                    <label>*Płeć</label>
                    <label class="radio-inline clear">
                        <input type="radio" required="required" name="form_sex" value="0" <?php echo ((isset($this->tpl_values['form_sex']) && $this->tpl_values['form_sex'] == '0') ? 'checked="checked" ' : ''); ?>/> Mężczyzna
                    </label>
                    <label class="radio-inline">
                        <input type="radio" required="required" name="form_sex" value="1" <?php echo ((isset($this->tpl_values['form_sex']) && $this->tpl_values['form_sex'] == '1') ? 'checked="checked" ' : ''); ?>/> Kobieta
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4">
                    <label for="form_name">*Imię:</label>
                    <input name="form_name" type="text" class="form-control input-sm" id="form_name" placeholder="Imię" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_second_name">Drugie Imię:</label>
                    <input name="form_second_name" type="text" class="form-control input-sm" id="form_second_name" placeholder="Drugie Imię" value="<?php echo ((isset($this->tpl_values['form_second_name']) && $this->tpl_values['form_second_name'] != '') ? $this->tpl_values['form_second_name'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_surname">*Nazwisko:</label>
                    <input name="form_surname" type="text" class="form-control input-sm" id="form_surname" placeholder="Nazwisko" required="required" value="<?php echo ((isset($this->tpl_values['form_surname']) && $this->tpl_values['form_surname'] != '') ? $this->tpl_values['form_surname'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4">
                    <label for="form_date_birthday">*Data urodzenia:</label>
                    <input name="form_date_birthday" type="text" class="form-control input-sm jsdate" id="form_date_birthday" placeholder="Data urodzenia" required="required" value="<?php echo ((isset($this->tpl_values['form_date_birthday']) && $this->tpl_values['form_date_birthday'] != '') ? $this->tpl_values['form_date_birthday'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_place_birthday">Miejsce urodzenia:</label>
                    <input name="form_place_birthday" type="text" class="form-control input-sm" id="form_place_birthday" placeholder="Miejsce urodzenia" value="<?php echo ((isset($this->tpl_values['form_place_birthday']) && $this->tpl_values['form_place_birthday'] != '') ? $this->tpl_values['form_place_birthday'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_pesel">*PESEL:</label>
                    <input name="form_pesel" type="text" class="form-control input-sm" id="form_pesel" required="required" placeholder="PESEL" value="<?php echo ((isset($this->tpl_values['form_pesel']) && $this->tpl_values['form_pesel'] != '') ? $this->tpl_values['form_pesel'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4">
                    <label for="form_identity_document">*Seria i Nr dowodu osobistego:</label>
                    <input name="form_identity_document" type="text" class="form-control input-sm" id="form_identity_document" placeholder="Seria i Nr dowodu osobistego" required="required" value="<?php echo ((isset($this->tpl_values['form_identity_document']) && $this->tpl_values['form_identity_document'] != '') ? $this->tpl_values['form_identity_document'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_nationality">*Narodowość:</label>
                    <input name="form_nationality" type="text" class="form-control input-sm" id="form_nationality" placeholder="Narodowość" required="required" value="<?php echo ((isset($this->tpl_values['form_nationality']) && $this->tpl_values['form_nationality'] != '') ? $this->tpl_values['form_nationality'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_citizenship">*Obywatelstwo:</label>
                    <input name="form_citizenship" type="text" class="form-control input-sm" id="form_citizenship" placeholder="Obywatelstwo" required="required" value="<?php echo ((isset($this->tpl_values['form_citizenship']) && $this->tpl_values['form_citizenship'] != '') ? $this->tpl_values['form_citizenship'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4">
                    <label for="form_mail">*Adres e-mail:</label>
                    <input name="form_mail" type="text" class="form-control input-sm" id="form_mail" placeholder="Adres e-mail" required="required" value="<?php echo ((isset($this->tpl_values['form_mail']) && $this->tpl_values['form_mail'] != '') ? $this->tpl_values['form_mail'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_phone">*Telefon:</label>
                    <input name="form_phone" type="text" class="form-control input-sm" id="form_phone" placeholder="Telefon" required="required" value="<?php echo ((isset($this->tpl_values['form_phone']) && $this->tpl_values['form_phone'] != '') ? $this->tpl_values['form_phone'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_education_type">*Wykształcenie:</label>
                    
                    <select class="form-control input-sm jsselect" id="form_education_type" name="form_education_type" required="required">
                        <option value="">Wybierz</option>
                        <?php
                            if (isset($this->tpl_values['education_types']) && $this->tpl_values['education_types']){
                                foreach ($this->tpl_values['education_types'] as $key_education_type => $education_type) {
                                    echo '<option value="'.$key_education_type.'"'.((isset($this->tpl_values['form_education_type']) && $this->tpl_values['form_education_type'] == $key_education_type) ? ' selected="selected"' : '').'>'.$education_type.'</option>';
                                }
                            }
                        ?>
                    </select>
                    
                </div>
            </div>

            <h4 class="page-header">Dane osoby - dodatkowe</h4>

            <div class="form-group">
                <div class="col-sm-3">
                    <label for="form_height">Wzrost:</label>
                    <div class="input-group">
                        <input name="form_height" type="text" class="form-control input-sm" id="form_height" placeholder="Wzrost" value="<?php echo ((isset($this->tpl_values['form_height']) && $this->tpl_values['form_height'] != '') ? $this->tpl_values['form_height'] : ''); ?>" />
                        <span class="input-group-addon"> cm </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="form_weight">Waga:</label>
                    <div class="input-group">
                        <input name="form_weight" type="text" class="form-control input-sm" id="form_weight" placeholder="Waga" value="<?php echo ((isset($this->tpl_values['form_weight']) && $this->tpl_values['form_weight'] != '') ? $this->tpl_values['form_weight'] : ''); ?>" />
                        <span class="input-group-addon"> kg </span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <label for="form_shoe_number">Nr buta:</label>
                    <input name="form_shoe_number" type="text" class="form-control input-sm" id="form_shoe_number" placeholder="Nr buta" value="<?php echo ((isset($this->tpl_values['form_shoe_number']) && $this->tpl_values['form_shoe_number'] != '') ? $this->tpl_values['form_shoe_number'] : ''); ?>" />
                </div>
                <div class="col-sm-3">
                    <label for="form_blood_group">Grupa krwi:</label>
                    <input name="form_blood_group" type="text" class="form-control input-sm" id="form_blood_group" placeholder="Grupa krwi" value="<?php echo ((isset($this->tpl_values['form_blood_group']) && $this->tpl_values['form_blood_group'] != '') ? $this->tpl_values['form_blood_group'] : ''); ?>" />
                </div>
            </div>

            <h4 class="page-header">Dane osoby - rodzina</h4>

            <div class="form-group">
                <div class="col-sm-4">
                    <label for="form_name_mother">Imię Matki:</label>
                    <input name="form_name_mother" type="text" class="form-control input-sm" id="form_name_mother" placeholder="Imię Matki" value="<?php echo ((isset($this->tpl_values['form_name_mother']) && $this->tpl_values['form_name_mother'] != '') ? $this->tpl_values['form_name_mother'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_surname_mother">Nazwisko panieńskie Matki:</label>
                    <input name="form_surname_mother" type="text" class="form-control input-sm" id="form_surname_mother" placeholder="Nazwisko panieńskie Matki" value="<?php echo ((isset($this->tpl_values['form_surname_mother']) && $this->tpl_values['form_surname_mother'] != '') ? $this->tpl_values['form_surname_mother'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4">
                    <label for="form_name_father">Imię Ojca:</label>
                    <input name="form_name_father" type="text" class="form-control input-sm" id="form_name_father" placeholder="Imię Ojca" value="<?php echo ((isset($this->tpl_values['form_name_father']) && $this->tpl_values['form_name_father'] != '') ? $this->tpl_values['form_name_father'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_surname_father">Nazwisko Ojca:</label>
                    <input name="form_surname_father" type="text" class="form-control input-sm" id="form_surname_father" placeholder="Nazwisko Ojca" value="<?php echo ((isset($this->tpl_values['form_surname_father']) && $this->tpl_values['form_surname_father'] != '') ? $this->tpl_values['form_surname_father'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-4">
                    <label for="form_name_partner">Imię małżonki(a):</label>
                    <input name="form_name_partner" type="text" class="form-control input-sm" id="form_name_partner" placeholder="Imię małżonki(a)" value="<?php echo ((isset($this->tpl_values['form_name_partner']) && $this->tpl_values['form_name_partner'] != '') ? $this->tpl_values['form_name_partner'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_surname_partner">Nazwisko małżonki(a):</label>
                    <input name="form_surname_partner" type="text" class="form-control input-sm" id="form_surname_partner" placeholder="Nazwisko małżonki(a)" value="<?php echo ((isset($this->tpl_values['form_surname_partner']) && $this->tpl_values['form_surname_partner'] != '') ? $this->tpl_values['form_surname_partner'] : ''); ?>" />
                </div>
            </div>

            <h4 class="page-header danger">Informacje wojskowe</h4>

            <div class="form-group">
                <div class="col-sm-4">
                    <label for="form_wku">WKU - Wojskowa Komenda Uzupełnień:</label>
                    <input name="form_wku" type="text" class="form-control input-sm" id="form_wku" placeholder="WKU - Wojskowa Komenda Uzupełnień" value="<?php echo ((isset($this->tpl_values['form_wku']) && $this->tpl_values['form_wku'] != '') ? $this->tpl_values['form_wku'] : ''); ?>" />
                </div>
                <div class="col-sm-4">
                    <label for="form_health_category">Kategoria zdrowia:</label>
                    <input name="form_health_category" type="text" class="form-control input-sm" id="form_health_category" placeholder="Kategoria zdrowia" value="<?php echo ((isset($this->tpl_values['form_health_category']) && $this->tpl_values['form_health_category'] != '') ? $this->tpl_values['form_health_category'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-12">
                    <label for="form_injuries">Wypadki i urazy <small>(rodzaj, gdzie i kiedy)</small>:</label>
                    <textarea name="form_injuries" class="form-control input-sm jstextareaautoheight" id="form_injuries" placeholder="Wypadki i urazy"><?php echo ((isset($this->tpl_values['form_injuries']) && $this->tpl_values['form_injuries'] != '') ? $this->tpl_values['form_injuries'] : ''); ?></textarea>
                </div>
            </div>

            <h4 class="page-header warning">Status żołnierza</h4>
            
            <div class="form-group">
                <div class="col-sm-4">
                    <label for="form_status">*Status żołnierza:</label>
                    
                    <select class="form-control input-sm jsselect" id="form_status" name="form_status" required="required">
                        <option value="">Wybierz</option>
                        <?php
                            if (isset($this->tpl_values['soldier_statuses']) && $this->tpl_values['soldier_statuses']){
                                foreach ($this->tpl_values['soldier_statuses'] as $key_status => $status) {
                                    echo '<option value="'.$key_status.'"'.((isset($this->tpl_values['form_status']) && $this->tpl_values['form_status'] == $key_status) ? ' selected="selected"' : '').'>'.$status.'</option>';
                                }
                            }
                        ?>
                    </select>
                    
                    <?php if($this->tpl_values['sew_action'] == 'edit'){ ?>
                    
                        <span class="sew_hint">Przy zmianie status na "W rezerwie", "W stanie spoczynku" lub "Zmarły", żołnierz zostaje automatycznie oddelegowany z misji oraz usunięty ze szkolenia na których obecnie przebywał.</span>
                        
                    <?php } ?>
                    
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-12 text-right">
                    <a href="/zolnierze" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Lista</a>
                    
                    <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                        <button class="btn btn-success mar_button" type="submit" name="form_action" value="soldier_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                        
                    <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                        <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                        <button class="btn btn-success mar_button" type="submit" name="form_action" value="soldier_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    <?php } ?>
                    
                </div>
            </div>
        </form>
    </div>
</div>
