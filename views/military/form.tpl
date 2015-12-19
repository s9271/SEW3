<?php // var_dump(ClassTools::getValuesdf($this->tpl_values['form_name'])); ?>

<div id="mission_form" class="container">
    <div class="jumbotron">
        <h1 class="controller_title"><?php echo ((isset($this->tpl_values['sew_action']) && $this->tpl_values['sew_action'] == 'add') ? 'Dodaj jednostkę wojskową' : 'Edytuj jednostkę wojskową'); ?></h1>
        
        <?php $this->getAlerts(true); ?>
    
        <form method="post" class="form-horizontal">

            <div class="form-group">
                <div class="col-sm-2 text-right require_text">
                    * - wymagane pola
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_name" class="control-label">*Nazwa jednostki:</label>
                </div>
                <div class="col-sm-8">
                    <input id="form_name" class="form-control input-sm" type="text" placeholder="Nazwa jednostki" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_number" class="control-label">*Numer jednostki:</label>
                </div>
                <div class="col-sm-2">
                    <input id="form_number" class="form-control login_input input-sm" type="text" placeholder="Numer jednostki" name="form_number" required="required" value="<?php echo ((isset($this->tpl_values['form_number']) && $this->tpl_values['form_number'] != '') ? $this->tpl_values['form_number'] : ''); ?>" />
                </div>
                
                <div class="col-sm-2 text-right">
                    <label for="form_group" class="control-label">*Rodzaj jednostki:</label>
                </div>
                <div class="col-sm-4">
                    <select class="form-control input-sm jsselect" id="form_group" name="form_group" required="required">
                        <option value="">Wybierz</option>
                        <?php
                            if (isset($this->tpl_values['form_groups']) && $this->tpl_values['form_groups']){
                                foreach ($this->tpl_values['form_groups'] as $key => $name) {
                                    echo '<option value="'.$key.'"'.((isset($this->tpl_values['form_group']) && $this->tpl_values['form_group'] == $key) ? ' selected="selected"' : '').'>'.$name.'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_location" class="control-label">*Lokalizacja:</label>
                </div>
                <div class="col-sm-8">
                    <input id="form_location" class="form-control login_input input-sm" type="text" placeholder="Lokalizacja" name="form_location" required="required" value="<?php echo ((isset($this->tpl_values['form_location']) && $this->tpl_values['form_location'] != '') ? $this->tpl_values['form_location'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_active" class="control-label">Aktywna:</label>
                </div>
                <div class="col-sm-8">
                    <label class="checkbox-inline">
                        <input type="checkbox" id="form_active" name="form_active" value="1"<?php echo ((isset($this->tpl_values['form_active']) && $this->tpl_values['form_active'] == '1') ? ' checked="checked"' : ''); ?> /> Gdy jednostka jest aktywna, można do niej dodać żołnierzy i użytkowników.
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 control-label col-sm-8">
                    <a href="/jednostki" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Lista</a>
                    
                    <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                        <button class="btn btn-success mar_button" type="submit" name="form_action" value="military_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                        
                    <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                        <input type="hidden" name="id_military" value="<?php echo $this->tpl_values['id_military']; ?>" />
                        <button class="btn btn-success mar_button" type="submit" name="form_action" value="military_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    <?php } ?>
                    
                </div>
            </div>
        </form>
    </div>
</div>
