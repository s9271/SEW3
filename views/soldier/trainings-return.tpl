<div id="soldier_trainings_form" class="new_admin_tpl mar_custom">

    <?php $this->getAlerts(false); ?>
    
    <form method="post" class="form-horizontal">

        <div class="form-group">
            <div class="col-sm-12 require_text">
                * - wymagane pola
            </div>
        </div>
            
        <div class="form-group">
            <div class="col-sm-6">
                <label for="form_date">*Data odesłania:</label>
                <input name="form_date" type="text" class="form-control input-sm jsdatetime" id="form_date" placeholder="Data odesłania" required="required" value="<?php echo ((isset($this->tpl_values['form_date']) && $this->tpl_values['form_date'] != '') ? $this->tpl_values['form_date'] : ''); ?>" />
                <span class="sew_hint">Format: 00.00.0000 00:00</span>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-12">
                <label for="form_description_return">Powód odesłania (opcjonalnie):</label>
                <textarea name="form_description_return" class="form-control input-sm jstextareaautoheight" placeholder="Powód odesłania (opcjonalnie)"><?php echo ((isset($this->tpl_values['form_description_return']) && $this->tpl_values['form_description_return'] != '') ? $this->tpl_values['form_description_return'] : ''); ?></textarea>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-12">
                <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/szkolenia/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy szkoleń żołnierza</a>
                <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/szkolenia/podglad/<?php echo $this->tpl_values['id_soldier2trainings']; ?>" class="btn btn-primary mar_button">Podgląd</a>
                
                <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                <input type="hidden" name="id_soldier2trainings" value="<?php echo $this->tpl_values['id_soldier2trainings']; ?>" />
                <input type="hidden" name="id_training" value="<?php echo $this->tpl_values['id_training']; ?>" />
                <button class="btn btn-warning mar_button pull-right" type="submit" name="form_action" value="training_return"><span class="glyphicon glyphicon-floppy-disk"></span>Odeślij</button>
                
            </div>
        </div>
        
    </form>
</div>
