<div id="soldier_badges_form" class="container mar_custom">
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
                    <label for="form_badge_type">Typ:</label>
                    <input name="form_badge_type" type="text" class="form-control input-sm" id="form_badge_type" placeholder="Typ" value="<?php echo ((isset($this->tpl_values['form_badge_type']) && $this->tpl_values['form_badge_type'] != '') ? $this->tpl_values['form_badge_type'] : ''); ?>" />
                    <span class="sew_hint">Np. Złoto, Srebro, itp.</span>
                </div>
                <div class="col-sm-6">
                    <label for="form_date_grant">*Data przyznania:</label>
                    <input name="form_date_grant" type="text" class="form-control input-sm jsdate" id="form_date_grant" placeholder="Data przyznania" required="required" value="<?php echo ((isset($this->tpl_values['form_date_grant']) && $this->tpl_values['form_date_grant'] != '') ? $this->tpl_values['form_date_grant'] : ''); ?>" />
                    <span class="sew_hint">Format: 00.00.0000</span>
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
                    <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/odznaczenia/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy odznaczeń żołnierza</a>
                    
                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                    <input type="hidden" name="id_badge" value="<?php echo $this->tpl_values['id_badge']; ?>" />
                    <input type="hidden" name="id_soldier2badges" value="<?php echo $this->tpl_values['id_soldier2badges']; ?>" />
                    <button class="btn btn-success mar_button pull-right" type="submit" name="form_action" value="badge_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                </div>
            </div>
        </form>
    </div>
</div>
