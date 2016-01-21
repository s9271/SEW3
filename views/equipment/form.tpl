<div id="equipments_form" class="new_admin_tpl">

        <?php $this->getAlerts(false); ?>
    
    <form method="post" class="form-horizontal">

        <div class="form-group">
            <div class="col-sm-12 require_text">
                * - wymagane pola
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_name" class="control-label">*Nazwa wyposażenia:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_name" class="form-control input-sm" type="text" placeholder="Nazwa wyposażenia" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_type" class="control-label">*Typ wyposażenia:</label>
            </div>
            <div class="col-sm-9">
                <select class="form-control input-sm jsselect" id="form_type" name="form_type" required="required">
                    <option value="">Wybierz</option>
                    <?php
                        if (isset($this->tpl_values['form_types']) && $this->tpl_values['form_types']){
                            foreach ($this->tpl_values['form_types'] as $type){
                                echo '<optgroup label="'.htmlspecialchars($type['name']).'">';
                            
                                if (isset($type['childs']) && is_array($type['childs']) && count($type['childs']) > 0)
                                {
                                    foreach ($type['childs'] as $key_type => $child)
                                    {
                                        $selected = ((isset($this->tpl_values['form_type']) && $this->tpl_values['form_type'] == $key_type) ? ' selected="selected"' : '');
                                        echo '<option value="'.$key_type.'"'.$selected.'>'.htmlspecialchars($child).'</option>';
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
            <div class="col-sm-3 text-right">
                <label for="form_description" class="control-label">Uwagi:</label>
            </div>
            <div class="col-sm-9">
                <textarea id="form_description" class="form-control input-sm jstextareaautoheight" placeholder="Uwagi" name="form_description"><?php echo ((isset($this->tpl_values['form_description']) && $this->tpl_values['form_description'] != '') ? $this->tpl_values['form_description'] : ''); ?></textarea>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_active" class="control-label">Aktywna:</label>
            </div>
            <div class="col-sm-9">
                <label class="checkbox-inline">
                    <input type="checkbox" id="form_active" name="form_active" value="1"<?php echo ((isset($this->tpl_values['form_active']) && $this->tpl_values['form_active'] == '1') ? ' checked="checked"' : ''); ?> /> Gdy wyposażenie jest aktywne, można do niego dodać żołnierzy.
                </label>
            </div>
        </div>

        <div class="form-group margin_top_50">
            <div class="col-sm-12 text-right">
                <a href="/wyposazenie" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy wyposażenia</a>
                
                <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="equipment_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                    
                <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                
                    <a href="/wyposazenie/podglad/<?php echo $this->tpl_values['id_equipment']; ?>" class="btn btn-primary mar_button">Podgląd wyposażenia</a>
                    <input type="hidden" name="id_equipment" value="<?php echo $this->tpl_values['id_equipment']; ?>" />
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="equipment_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    
                <?php } ?>
                
            </div>
        </div>
    </form>
</div>
