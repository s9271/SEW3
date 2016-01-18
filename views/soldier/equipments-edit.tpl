<div id="soldier_equipments_form" class="container mar_custom">
    <div class="jumbotron">
        <h1 class="controller_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(true); ?>
    
        <form method="post" class="form-horizontal">

            <div class="form-group">
                <div class="col-sm-12 require_text">
                    * - wymagane pola
                </div>
            </div>
                
            <div class="form-group">
                <div class="col-sm-6">
                    <label for="form_count">*Ilość:</label>
                    <input name="form_count" type="text" class="form-control input-sm" id="form_count" placeholder="Ilość" value="<?php echo ((isset($this->tpl_values['form_count']) && $this->tpl_values['form_count'] != '') ? $this->tpl_values['form_count'] : ''); ?>" required="required" />
                </div>
                <div class="col-sm-6">
                    <label for="form_date">*Data przyznania:</label>
                    <input name="form_date" type="text" class="form-control input-sm jsdatetime" id="form_date" placeholder="Data przyznania" required="required" value="<?php echo ((isset($this->tpl_values['form_date']) && $this->tpl_values['form_date'] != '') ? $this->tpl_values['form_date'] : ''); ?>" />
                    <span class="sew_hint">Format: 00.00.0000 00:00</span>
                </div>
            </div>
                
            <div class="form-group">
                <div class="col-sm-12">
                    <label for="add_form_description">Dodatkowe informacje (opcjonalnie):</label>
                    <textarea name="form_description" class="form-control input-sm jstextareaautoheight" placeholder="Dodatkowe informacje (opcjonalnie)"><?php echo ((isset($this->tpl_values['form_description']) && $this->tpl_values['form_description'] != '') ? $this->tpl_values['form_description'] : ''); ?></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-12">
                    <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/wyposazenie/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy wyposażenia żołnierza</a>
                    
                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                    <input type="hidden" name="id_equipment" value="<?php echo $this->tpl_values['id_equipment']; ?>" />
                    <input type="hidden" name="id_soldier2equipments" value="<?php echo $this->tpl_values['id_soldier2equipments']; ?>" />
                    <button class="btn btn-success mar_button pull-right" type="submit" name="form_action" value="equipment_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                </div>
            </div>
        </form>
    </div>
</div>
