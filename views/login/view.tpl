<div id="login_view" class="clearfix">
    <div id="center_block" class="clearfix">
        <h1>SEW - System Ewidencji Wojskowej</h1>
        
        <div class="block clearfix">
            <h2>Panel logowania</h2>
            
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

                <div class="form-group form-group-lg">
                    <div class="col-sm-3 text-right">
                        <label for="form_password" class="control-label">Hasło:</label>
                    </div>
                    <div class="col-sm-9">
                        <input id="form_password" class="form-control input-lg" type="password" placeholder="Hasło" name="form_password" required="required" value="<?php echo ((isset($this->tpl_values['form_password']) && $this->tpl_values['form_password'] != '') ? $this->tpl_values['form_password'] : ''); ?>" />
                    </div>
                </div>
                
                <div class="form-group buttons">
                    <div class="control-label col-sm-12">
                        <a href="/haslo" class="btn btn-info btn-lg" title="Nie pamiętam hasła">Nie pamiętam hasła</a>
                        <button class="btn btn-success btn-lg" type="submit" name="form_action" value="login">Zaloguj</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
