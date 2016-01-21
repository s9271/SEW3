<div id="training_form" class="new_admin_tpl">

    <?php $this->getAlerts(false); ?>

    <form method="post" class="form-horizontal">

        <div class="form-group">
            <div class="col-sm-12 require_text">
                * - wymagane pola
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_name" class="control-label">*Nazwa szkolenia:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_name" class="form-control login_input input-sm" type="text" placeholder="Nazwa szkolenia" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_code" class="control-label">Kod szkolenia:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_code" class="form-control login_input input-sm" type="text" placeholder="Kod szkolenia" name="form_code" value="<?php echo ((isset($this->tpl_values['form_code']) && $this->tpl_values['form_code'] != '') ? $this->tpl_values['form_code'] : ''); ?>" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_training_center" class="control-label">*Ośrodek szkolenia:</label>
            </div>
            <div class="col-sm-9">
                <select class="form-control input-sm jsselect" id="form_training_center" name="form_training_center" required="required">
                    <option value="">Wybierz</option>
                    <?php
                        if (isset($this->tpl_values['training_centers']) && $this->tpl_values['training_centers']){
                            foreach ($this->tpl_values['training_centers'] as $key => $training_center) {
                                echo '<option value="'.$key.'"'.((isset($this->tpl_values['form_training_center']) && $this->tpl_values['form_training_center'] == $key) ? ' selected="selected"' : '').'>'.$training_center['name'].', '.$training_center['location'].'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_description" class="control-label">Opis:</label>
            </div>
            <div class="col-sm-9">
                <textarea id="form_description" class="form-control input-sm jstextareaautoheight" placeholder="Opis" name="form_description"><?php echo ((isset($this->tpl_values['form_description']) && $this->tpl_values['form_description'] != '') ? $this->tpl_values['form_description'] : ''); ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label class="control-label">Data:</label>
            </div>
            <div class="col-sm-4">
                <div class="input-group">
                    <span id="sizing-addon2" class="input-group-addon">
                        od*
                    </span>
                    <input class="form-control input-sm jsdatetime" type="text" placeholder="Data rozpoczęcia" name="form_date_start" required="required" value="<?php echo ((isset($this->tpl_values['form_date_start']) && $this->tpl_values['form_date_start'] != '') ? $this->tpl_values['form_date_start'] : ''); ?>" />
                </div>
                <span class="sew_hint">Format: 00.00.0000 00:00</span>
            </div>
            
            <div class="col-sm-4 col-sm-offset-1">
                <div class="input-group">
                    <span id="sizing-addon2" class="input-group-addon">
                        do
                    </span>
                    <input id="form_date_end" class="form-control input-sm jsdatetime" type="text" placeholder="Data zakończenia" name="form_date_end" value="<?php echo ((isset($this->tpl_values['form_date_end']) && $this->tpl_values['form_date_end'] != '') ? $this->tpl_values['form_date_end'] : ''); ?>" />
                </div>
                <span class="sew_hint">Format: 00.00.0000 00:00</span>
                <span class="sew_hint">Data zakończenia może pozostać pusta, gdy nie wiadomo do kiedy ma trwać szkolenie.</span>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label class="control-label">Aktywna:</label>
            </div>
            <div class="col-sm-9">
                <label class="checkbox-inline">
                    <input type="checkbox" id="form_active" name="form_active" value="1"<?php echo ((isset($this->tpl_values['form_active']) && $this->tpl_values['form_active'] == '1') ? ' checked="checked"' : ''); ?> /> Gdy szkolenie jest aktywne, można do niego dodać żołnierzy.
                </label>
            </div>
        </div>
        
        <div class="form-group margin_top_50">
            <div class="col-sm-12 text-right">
                <a href="/szkolenia" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy szkoleń</a>
                
                <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="training_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                    
                <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                
                    <a href="/szkolenia/podglad/<?php echo $this->tpl_values['id_training']; ?>" class="btn btn-primary mar_button">Podgląd szkolenia</a>
                    <input type="hidden" name="id_training" value="<?php echo $this->tpl_values['id_training']; ?>" />
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="training_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    
                <?php } ?>
                
            </div>
        </div>
    </form>
</div>
