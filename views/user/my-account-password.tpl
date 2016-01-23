<div id="user_form" class="new_admin_tpl">
        
    <?php $this->getAlerts(false); ?>

    <form method="post" class="form-horizontal">

        <div class="form-group">
            <div class="col-sm-12 require_text">
                * - wymagane pola
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_new_password" class="control-label">*Nowe hasło:</label>
            </div>
            <div class="col-sm-9">
                <div class="input-group">
                    <input id="form_new_password" class="form-control input-sm ajaxpassword" type="password" placeholder="Nowe hasło" name="form_new_password" required="required" autocomplete="off" value="<?php echo ((isset($this->tpl_values['form_new_password']) && $this->tpl_values['form_new_password'] != '') ? $this->tpl_values['form_new_password'] : ''); ?>" />
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-sm mar_button jspasswordajaxwithrepeat" data-input-class="ajaxpassword" data-hint-class="sew_hint" type="button"><span class="glyphicon glyphicon-refresh"></span>Generuj nowe</button>
                    </span>
                </div>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_new_password_repeat" class="control-label">*Powtórz nowe hasło:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_new_password_repeat" class="form-control input-sm ajaxpassword" type="password" placeholder="Powtórz nowe hasło" name="form_new_password_repeat" required="required" autocomplete="off" value="<?php echo ((isset($this->tpl_values['form_new_password_repeat']) && $this->tpl_values['form_new_password_repeat'] != '') ? $this->tpl_values['form_new_password_repeat'] : ''); ?>" />
                <span class="sew_hint"></span>
            </div>
        </div>

        <div class="form-group margin_top_50">
            <div class="col-sm-12 text-right">
                <a href="/moje-konto" class="btn btn-warning mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do mojego konta</a>
                
                <input type="hidden" name="id_user" value="<?php echo $this->tpl_values['id_user']; ?>" />
                <button class="btn btn-success mar_button" type="submit" name="form_action" value="user_password_update"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz nowe hasło</button>
            </div>
        </div>
    </form>
</div>
