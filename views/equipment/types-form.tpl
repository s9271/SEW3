<div id="equipment_type_form" class="container">
    <div class="jumbotron">
        <h1 class="controller_title"><?php echo ((isset($this->tpl_values['sew_action']) && $this->tpl_values['sew_action'] == 'add') ? 'Dodaj typ wyposażenia' : 'Edytuj typ wyposażenia'); ?></h1>
        
        <?php $this->getAlerts(true); ?>
    
        <form method="post" class="form-horizontal">

            <div class="form-group">
                <div class="col-sm-2 text-right require_text">
                    * - wymagane pola
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_name" class="control-label">*Nazwa typu wyposażenia:</label>
                </div>
                <div class="col-sm-8">
                    <input id="form_name" class="form-control input-sm" type="text" placeholder="Nazwa typu wyposażenia" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_parent" class="control-label">Kategoria główna:</label>
                </div>
                <div class="col-sm-8">
                    <select class="form-control input-sm jsselect" id="form_parent" name="form_parent">
                        <option value="">Wybierz</option>
                        <?php
                            if (isset($this->tpl_values['parent_categories']) && $this->tpl_values['parent_categories']){
                                foreach ($this->tpl_values['parent_categories'] as $parents) {
                                    echo '<option value="'.$parents['id_equipment_type'].'"'.((isset($this->tpl_values['form_parent']) && $this->tpl_values['form_parent'] == $parents['id_equipment_type']) ? ' selected="selected"' : '').'>'.$parents['name'].'</option>';
                                }
                            }
                        ?>
                    </select>
                    <span class="sew_hint">Jeśli nie zostanie wybrana, zostanie jedną z kategorii głównych.</span>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-2 text-right">
                    <label for="form_active" class="control-label">Aktywna:</label>
                </div>
                <div class="col-sm-8">
                    <label class="checkbox-inline">
                        <input type="checkbox" id="form_active" name="form_active" value="1"<?php echo ((isset($this->tpl_values['form_active']) && $this->tpl_values['form_active'] == '1') ? ' checked="checked"' : ''); ?> /> Gdy typ wyposażenia jest aktywny, można do niego dodać wyposażenie.
                    </label>
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 control-label col-sm-8">
                    <a href="/typy-wyposazenia/<?php echo $this->tpl_values['list_page'] ? $this->tpl_values['list_page'] : ''; ?>" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Lista</a>
                    
                    <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                        <button class="btn btn-success mar_button" type="submit" name="form_action" value="equipment_type_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                        
                    <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                        <input type="hidden" name="id_equipment_type" value="<?php echo $this->tpl_values['id_equipment_type']; ?>" />
                        <button class="btn btn-success mar_button" type="submit" name="form_action" value="equipment_type_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    <?php } ?>
                    
                </div>
            </div>
        </form>
    </div>
</div>
