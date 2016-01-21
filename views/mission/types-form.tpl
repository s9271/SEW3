<div id="mission_type_form" class="new_admin_tpl">

    <?php $this->getAlerts(false); ?>

    <form method="post" class="form-horizontal">

        <div class="form-group">
            <div class="col-sm-12 require_text">
                * - wymagane pola
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_name" class="control-label">*Nazwa rodzaju misji:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_name" class="form-control input-sm" type="text" placeholder="Nazwa rodzaju misji" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_parent" class="control-label">Kategoria główna:</label>
            </div>
            <div class="col-sm-9">
                <select class="form-control input-sm jsselect" id="form_parent" name="form_parent">
                    <option value="">Wybierz</option>
                    <?php
                        if (isset($this->tpl_values['parent_categories']) && $this->tpl_values['parent_categories']){
                            foreach ($this->tpl_values['parent_categories'] as $parents) {
                                echo '<option value="'.$parents['id_mission_type'].'"'.((isset($this->tpl_values['form_parent']) && $this->tpl_values['form_parent'] == $parents['id_mission_type']) ? ' selected="selected"' : '').'>'.$parents['name'].'</option>';
                            }
                        }
                    ?>
                </select>
                <span class="sew_hint">Jeśli nie zostanie wybrana, zostanie jedną z kategorii głównych.</span>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_active" class="control-label">Aktywna:</label>
            </div>
            <div class="col-sm-9">
                <label class="checkbox-inline">
                    <input type="checkbox" id="form_active" name="form_active" value="1"<?php echo ((isset($this->tpl_values['form_active']) && $this->tpl_values['form_active'] == '1') ? ' checked="checked"' : ''); ?> /> Gdy rodzaj misji jest aktywny, można do niego dodać misje.
                </label>
            </div>
        </div>

        <div class="form-group margin_top_50">
            <div class="col-sm-12 text-right">
                <a href="/rodzaje-misji/<?php echo $this->tpl_values['list_page'] ? $this->tpl_values['list_page'] : ''; ?>" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy rodzajów misji</a>
                
                <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="mission_type_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                    
                <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                
                    <a href="/rodzaje-misji/podglad/<?php echo $this->tpl_values['id_mission_type']; ?>" class="btn btn-primary mar_button">Podgląd rodzaju misji</a>
                    <input type="hidden" name="id_mission_type" value="<?php echo $this->tpl_values['id_mission_type']; ?>" />
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="mission_type_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    
                <?php } ?>
                
            </div>
        </div>
    </form>
</div>
