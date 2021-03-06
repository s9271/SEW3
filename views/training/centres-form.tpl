<div id="training_center_form" class="new_admin_tpl">

    <?php $this->getAlerts(false); ?>

    <form method="post" class="form-horizontal">

        <div class="form-group">
            <div class="col-sm-12 require_text">
                * - wymagane pola
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_name" class="control-label">*Nazwa centrum:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_name" class="form-control input-sm" type="text" placeholder="Nazwa centrum" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_location" class="control-label">*Lokalizacja:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_location" class="form-control input-sm" type="text" placeholder="Lokalizacja" name="form_location" required="required" value="<?php echo ((isset($this->tpl_values['form_location']) && $this->tpl_values['form_location'] != '') ? $this->tpl_values['form_location'] : ''); ?>" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_active" class="control-label">Aktywna:</label>
            </div>
            <div class="col-sm-9">
                <label class="checkbox-inline">
                    <input type="checkbox" id="form_active" name="form_active" value="1"<?php echo ((isset($this->tpl_values['form_active']) && $this->tpl_values['form_active'] == '1') ? ' checked="checked"' : ''); ?> /> Gdy centrum szkolenia jest aktywne, można do niego dodać szkolenia.
                </label>
            </div>
        </div>
        
        <div class="form-group margin_top_50">
            <div class="col-sm-12 text-right">
                <a href="/centra-szkolen" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy centrów szkoleń</a>
                
                <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="training_centre_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                    
                <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                
                    <a href="/centra-szkolen/podglad/<?php echo $this->tpl_values['id_training_centre']; ?>" class="btn btn-primary mar_button">Podgląd centrum szkolenia</a>
                    <input type="hidden" name="id_training_centre" value="<?php echo $this->tpl_values['id_training_centre']; ?>" />
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="training_centre_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    
                <?php } ?>
                
            </div>
        </div>
    </form>
</div>
