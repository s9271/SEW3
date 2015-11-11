<div id="mission_form" class="container">
    <div class="jumbotron">
        <h1 class="controller_title">Dodaj Misje</h1>
        
        <?php $this->getAlerts(true); ?>
    
        <form method="post" action="" class="form-horizontal">

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_name" class="control-label">*Kryptonim misji:</label>
                </div>
                <div class="col-sm-8">
                    <input id="form_name" class="form-control login_input input-sm" type="text" placeholder="Kryptonim misji" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_type" class="control-label">*Rodzaj misji:</label>
                </div>
                <div class="col-sm-8">
                    <select class="form-control input-sm jsselect" id="form_type" name="form_type" required="required">
                        <option value="">Wybierz</option>
                        <?php
                            if (isset($this->tpl_values['form_type'])){
                                foreach ($this->tpl_values['form_type'] as $group) {
                                    echo '<optgroup label="'.$group['name'].'">';
                                    
                                    if (isset($group['childs']) && is_array($group['childs']) && count($group['childs']) > 0){
                                        foreach ($group['childs'] as $key_type => $type) {
                                            echo '<option value="'.$key_type.'"'.((isset($type['current']) && $type['current'] === true) ? ' selected="selected"' : '').'>'.$type['name'].'</option>';
                                        }
                                    }
                                    
                                    echo '</optgroup>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_location" class="control-label">*Lokalizacja misji:</label>
                </div>
                <div class="col-sm-8">
                    <input id="form_location" class="form-control input-sm" type="text" placeholder="Lokalizacja misji" name="form_location" required="required" value="<?php echo ((isset($this->tpl_values['form_location']) && $this->tpl_values['form_location'] != '') ? $this->tpl_values['form_location'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_description" class="control-label">Opis:</label>
                </div>
                <div class="col-sm-8">
                    <textarea id="form_description" class="form-control input-sm" placeholder="Opis" name="form_description"><?php echo ((isset($this->tpl_values['form_description']) && $this->tpl_values['form_description'] != '') ? $this->tpl_values['form_description'] : ''); ?></textarea>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label class="control-label">Data:</label>
                </div>
                <div class="col-sm-4">
                    <div class="input-group">
                        <span id="sizing-addon2" class="input-group-addon">
                            od*
                        </span>
                        <input class="form-control input-sm jsdatetime" type="text" placeholder="Data rozpoczęcia" name="form_date_start" required="required" value="<?php echo ((isset($this->tpl_values['form_date_start']) && $this->tpl_values['form_date_start'] != '') ? $this->tpl_values['form_date_start'] : ''); ?>" />
                    </div>
                    <span class="sew_hint">Format: 00.00.0000 00:00</span>
                </div>
                
                <div class="col-sm-4">
                    <div class="input-group">
                        <span id="sizing-addon2" class="input-group-addon">
                            do
                        </span>
                        <input id="form_date_end" class="form-control input-sm jsdatetime" type="text" placeholder="Data zakończenia" name="form_date_end" value="<?php echo ((isset($this->tpl_values['form_date_end']) && $this->tpl_values['form_date_end'] != '') ? $this->tpl_values['form_date_end'] : ''); ?>" />
                    </div>
                    <span class="sew_hint">Format: 00.00.0000 00:00</span>
                    <span class="sew_hint">Data zakończenia może pozostać pusta, gdy nie wiadomo do kiedy ma trwać misja.</span>
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label class="control-label">Aktywna:</label>
                </div>
                <div class="col-sm-8">
                    <label class="checkbox-inline">
                        <input type="checkbox" id="form_active" name="form_active" value="1"<?php echo ((isset($this->tpl_values['form_active']) && $this->tpl_values['form_active'] == '1') ? ' checked="checked"' : ''); ?> /> Gdy misja jest aktywna, można na nią wysyłać żołnierzy
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 control-label col-sm-8">
                    <a href="/misje" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Wstecz</a>
                    <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                        <button class="btn btn-success mar_button" type="submit" name="form_action" value="mission_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                    <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                        <input type="hidden" name="id_mission" value="<?php echo $this->tpl_values['id_mission']; ?>" />
                        <button class="btn btn-success mar_button" type="submit" name="form_action" value="mission_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    <?php } ?>
                </div>
            </div>
        </form>
    </div>
</div>
