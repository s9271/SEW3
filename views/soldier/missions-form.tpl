<div id="soldier_missions_form" class="new_admin_tpl mar_custom">

    <?php $this->getAlerts(false); ?>
    
    <form method="post" class="form-horizontal">

        <div class="form-group">
            <div class="col-sm-12 require_text">
                * - wymagane pola
            </div>
        </div>
            
        <div class="form-group">
            <div class="col-sm-6">
                <label for="form_date">*Data przypisania do misji:</label>
                <input name="form_date" type="text" class="form-control input-sm jsdatetime" id="form_date" placeholder="Data przypisania do misji" required="required" value="<?php echo ((isset($this->tpl_values['form_date']) && $this->tpl_values['form_date'] != '') ? $this->tpl_values['form_date'] : ''); ?>" />
                <span class="sew_hint">Format: 00.00.0000 00:00</span>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-12">
                <label for="form_description">Dodatkowe informacje (opcjonalnie):</label>
                <textarea name="form_description" class="form-control input-sm jstextareaautoheight" placeholder="Dodatkowe informacje (opcjonalnie)"><?php echo ((isset($this->tpl_values['form_description']) && $this->tpl_values['form_description'] != '') ? $this->tpl_values['form_description'] : ''); ?></textarea>
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-12">
                <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/misje/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy misji żołnierza</a>
                <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/misje/podglad/<?php echo $this->tpl_values['id_soldier2missions']; ?>" class="btn btn-primary mar_button">Podgląd</a>
                
                <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                <input type="hidden" name="id_soldier2missions" value="<?php echo $this->tpl_values['id_soldier2missions']; ?>" />
                <input type="hidden" name="id_mission" value="<?php echo $this->tpl_values['id_mission']; ?>" />
                <button class="btn btn-success mar_button pull-right" type="submit" name="form_action" value="mission_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                
            </div>
        </div>
        
    </form>
</div>
