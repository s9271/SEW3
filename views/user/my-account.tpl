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
                <label for="form_login" class="control-label">Nazwa użytkownika:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_login" class="form-control input-sm" type="text" placeholder="Nazwa użytkownika" name="form_login_disabled" value="<?php echo $this->tpl_values['form_login']; ?>" disabled="disabled" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_name" class="control-label">*Imię:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_name" class="form-control input-sm" type="text" placeholder="Imię" name="form_name" required="required" value="<?php echo $this->tpl_values['form_name']; ?>" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_surname" class="control-label">*Nazwisko:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_surname" class="form-control input-sm" type="text" placeholder="Nazwisko" name="form_surname" required="required" value="<?php echo $this->tpl_values['form_surname']; ?>" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_mail" class="control-label">*Adres e-mail:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_mail" class="form-control input-sm" type="text" placeholder="Adres e-mail" name="form_mail" required="required" value="<?php echo $this->tpl_values['form_mail']; ?>" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_phone" class="control-label">Telefon:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_phone" class="form-control input-sm" type="text" placeholder="Telefon" name="form_phone" value="<?php echo $this->tpl_values['form_phone']; ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_guard" class="control-label">Guard:</label>
            </div>
            <div class="col-sm-9">
                <label class="checkbox-inline">
                    <input type="checkbox" id="form_guard" name="form_guard" value="1"<?php echo (isset($this->tpl_values['form_guard']) ? ($this->tpl_values['form_guard'] == '1' ? ' checked="checked"' : '') : ' checked="checked"'); ?> /> (Rekomendowane) Za każdym razem, gdy użytkownik będzie logować się z nieznanego komputera, będzie proszony o podanie kodu przesłanego na adres email.
                </label>
            </div>
        </div>

        <div class="form-group margin_top_50">
            <div class="col-sm-12 text-right">
                <a href="/moje-konto/haslo" class="btn btn-warning mar_button">
                    <i class="fa fa-pencil-square-o"></i>
                    Ustaw nowe hasło
                </a>
                
                <input type="hidden" name="id_user" value="<?php echo $this->tpl_values['id_user']; ?>" />
                <button class="btn btn-success mar_button" type="submit" name="form_action" value="user_edit"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
            </div>
        </div>
    </form>
</div>
