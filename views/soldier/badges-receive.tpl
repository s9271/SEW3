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
                <div class="col-sm-12">
                    <label for="form_description_receive">Powód odebrania (opcjonalnie):</label>
                    <textarea name="form_description_receive" class="form-control input-sm jstextareaautoheight" placeholder="Powód odebrania (opcjonalnie)"><?php echo ((isset($this->tpl_values['form_description_receive']) && $this->tpl_values['form_description_receive'] != '') ? $this->tpl_values['form_description_receive'] : ''); ?></textarea>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-12">
                    <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/odznaczenia/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy odznaczeń żołnierza</a>
                    <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/odznaczenia/podglad/<?php echo $this->tpl_values['id_soldier2badges']; ?>" class="btn btn-primary mar_button">Podgląd</a>
                    
                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                    <input type="hidden" name="id_soldier2badges" value="<?php echo $this->tpl_values['id_soldier2badges']; ?>" />
                    <input type="hidden" name="id_badge" value="<?php echo $this->tpl_values['id_badge']; ?>" />
                    <button class="btn btn-warning mar_button pull-right" type="submit" name="form_action" value="badge_receive"><span class="glyphicon glyphicon-floppy-disk"></span>Odbierz odznaczenie</button>
                    
                </div>
            </div>
            
        </form>
    
    </div>
</div>
