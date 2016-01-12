<div id="soldier_missions_form" class="container mar_custom">
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
                <div class="col-sm-12">
                    <label for="form_description_detach">Powód oddelegowania (opcjonalnie):</label>
                    <textarea name="form_description_detach" class="form-control input-sm jstextareaautoheight" placeholder="Powód oddelegowania (opcjonalnie)"><?php echo ((isset($this->tpl_values['form_description_detach']) && $this->tpl_values['form_description_detach'] != '') ? $this->tpl_values['form_description_detach'] : ''); ?></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-12">
                    <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/misje/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy misji żołnierza</a>
                    <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/misje/podglad/<?php echo $this->tpl_values['id_soldier2missions']; ?>" class="btn btn-primary mar_button">Podgląd</a>
                    
                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                    <input type="hidden" name="id_soldier2missions" value="<?php echo $this->tpl_values['id_soldier2missions']; ?>" />
                    <input type="hidden" name="id_mission" value="<?php echo $this->tpl_values['id_mission']; ?>" />
                    <button class="btn btn-warning mar_button pull-right" type="submit" name="form_action" value="mission_detach"><span class="glyphicon glyphicon-floppy-disk"></span>Oddeleguj</button>
                    
                </div>
            </div>
            
        </form>
    
    </div>
</div>
