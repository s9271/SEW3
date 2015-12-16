<div id="login_password" class="clearfix">
    <div id="center_block" class="clearfix">
        <h1>SEW - System Ewidencji Wojskowej</h1>
        
        <div class="block clearfix">
            <h2>Przywracanie hasła</h2>
            
            <?php $this->getAlerts(true); ?>
            
            <form method="post" action="" class="form-horizontal">

                <div class="form-group form-group-lg">
                    <div class="col-sm-3 text-right">
                        <label for="form_login" class="control-label">Użytkownik:</label>
                    </div>
                    <div class="col-sm-9">
                        <input id="form_login" class="form-control input-lg" type="text" placeholder="Użytkownik" name="form_login" required="required" value="<?php echo ((isset($this->tpl_values['form_login']) && $this->tpl_values['form_login'] != '') ? $this->tpl_values['form_login'] : ''); ?>" />
                    </div>
                </div>
                
                <div class="form-group buttons">
                    <div class="control-label col-sm-12">
                        <a href="/login" class="btn btn-info btn-lg pull-left" title="Wróć">Wróć</a>
                        <button class="btn btn-primary btn-lg" type="submit" name="form_action" value="new_password">Wyślij nowe hasło</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
