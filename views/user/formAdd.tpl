<div id="user_form" class="container">
    <div class="jumbotron text-left">
        <h1 class="controller_title">Dodaj użytkownika</h1>
        
        <?php $this->getAlerts(true); ?>
    
        <form method="post" action="" class="form-horizontal">

            <div class="form-group">
                <div class="col-sm-2 text-right require_text">
                    * - wymagane pola
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_login" class="control-label">*Nazwa użytkownika:</label>
                </div>
                <div class="col-sm-8">
                    <input id="form_login" class="form-control input-sm" type="text" placeholder="Nazwa użytkownika" name="form_login" required="required" value="<?php echo ((isset($this->tpl_values['form_login']) && $this->tpl_values['form_login'] != '') ? $this->tpl_values['form_login'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_password" class="control-label">*Hasło:</label>
                </div>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input id="form_password" class="form-control input-sm" type="password" placeholder="Hasło" name="form_password" required="required" autocomplete="off" value="<?php echo ((isset($this->tpl_values['form_password']) && $this->tpl_values['form_password'] != '') ? $this->tpl_values['form_password'] : ''); ?>" />
                        <span class="input-group-btn">
                            <button class="btn btn-default btn-sm mar_button jspasswordajax" data-input="form_password" type="button"><span class="glyphicon glyphicon-refresh"></span>Generuj nowe</button>
                        </span>
                    </div>
                    <span class="sew_hint"></span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_name" class="control-label">*Imię:</label>
                </div>
                <div class="col-sm-8">
                    <input id="form_name" class="form-control input-sm" type="text" placeholder="Imię" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_surname" class="control-label">*Nazwisko:</label>
                </div>
                <div class="col-sm-8">
                    <input id="form_surname" class="form-control input-sm" type="text" placeholder="Nazwisko" name="form_surname" required="required" value="<?php echo ((isset($this->tpl_values['form_surname']) && $this->tpl_values['form_surname'] != '') ? $this->tpl_values['form_surname'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_mail" class="control-label">*Adres e-mail:</label>
                </div>
                <div class="col-sm-8">
                    <input id="form_mail" class="form-control input-sm" type="text" placeholder="Adres e-mail" name="form_mail" required="required" value="<?php echo ((isset($this->tpl_values['form_mail']) && $this->tpl_values['form_mail'] != '') ? $this->tpl_values['form_mail'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_phone" class="control-label">Telefon:</label>
                </div>
                <div class="col-sm-8">
                    <input id="form_phone" class="form-control input-sm" type="text" placeholder="Telefon" name="form_phone" value="<?php echo ((isset($this->tpl_values['form_phone']) && $this->tpl_values['form_phone'] != '') ? $this->tpl_values['form_phone'] : ''); ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_permission" class="control-label">*Profil uprawnień:</label>
                </div>
                <div class="col-sm-8">
                    <select class="form-control input-sm jsselect" id="form_permission" name="form_permission" required="required">
                        <option value="">Wybierz</option>
                        <?php
                           if (isset($this->tpl_values['form_permissions'])){
                                foreach ($this->tpl_values['form_permissions'] as $permission) {
                                    echo '<option value="'.$permission['id_permission'].'"'.((isset($this->tpl_values['form_permission']) && $this->tpl_values['form_permission'] === $permission['id_permission']) ? ' selected="selected"' : '').'>'.$permission['name'].'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_military" class="control-label">Jednostka:</label>
                </div>
                <div class="col-sm-8">
                    <select class="form-control input-sm jsselect" id="form_military" name="form_military">
                        <option value="0">Wybierz</option>
                        <?php
                           if (isset($this->tpl_values['form_militaries']) && $this->tpl_values['form_militaries']){
                                foreach ($this->tpl_values['form_militaries'] as $group) {
                                    echo '<optgroup label="'.$group['name'].'">';
                                    
                                    if (isset($group['childs']) && is_array($group['childs']) && count($group['childs']) > 0){
                                        foreach ($group['childs'] as $key_type => $type) {
                                            echo '<option value="'.$key_type.'"'.((isset($this->tpl_values['form_military']) && $this->tpl_values['form_military'] == $key_type) ? ' selected="selected"' : '').'>'.$type['name'].'</option>';
                                        }
                                    }
                                    
                                    echo '</optgroup>';
                                }
                            }
                        ?>
                    </select>
                    <span class="sew_hint">Opcjonalne, zalecane przy użytkownikach z kadry administracyjnej.</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_active" class="control-label">Włączony:</label>
                </div>
                <div class="col-sm-8">
                    <input type="checkbox" class="input-sm" id="form_active" name="form_active" value="1"<?php echo ((isset($this->tpl_values['form_active']) && $this->tpl_values['form_active'] == '1') ? ' checked="checked"' : ''); ?> />
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_guard" class="control-label">Guard:</label>
                </div>
                <div class="col-sm-8">
                    <label class="checkbox-inline">
                        <input type="checkbox" id="form_guard" name="form_guard" value="1"<?php echo (isset($this->tpl_values['form_guard']) ? ($this->tpl_values['form_guard'] == '1' ? ' checked="checked"' : '') : ' checked="checked"'); ?> /> (Rekomendowane) Za każdym razem, gdy użytkownik będzie logować się z nieznanego komputera, będzie proszony o podanie kodu przesłanego na adres email.
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 control-label col-sm-8">
                    <a href="/uzytkownicy" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Lista</a>
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="user_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                </div>
            </div>
        </form>
    </div>
</div>
