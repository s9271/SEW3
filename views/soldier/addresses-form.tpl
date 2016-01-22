<div id="soldier_address_form" class="new_admin_tpl mar_custom">

    <?php $this->getAlerts(false); ?>

    <form method="post" class="form-horizontal">

        <div class="form-group">
            <div class="col-sm-12 require_text">
                * - wymagane pola
            </div>
        </div>
            
        <div class="form-group">
            <div class="col-sm-6">
                <label for="form_type">*Typ:</label>
                
                <select class="input-sm jsselect" id="form_type" name="form_type[]" required="required" multiple="multiple">
                    <?php
                        if (isset($this->tpl_values['address_types']) && $this->tpl_values['address_types']){
                            foreach ($this->tpl_values['address_types'] as $key => $name){
                                // $disabled = (isset($this->tpl_values['soldier_address_types']) && $this->tpl_values['soldier_address_types'] && is_array($this->tpl_values['soldier_address_types']) && isset($this->tpl_values['soldier_address_types'][$key])) ? ' disabled="disabled"' : '';
                                
                                // echo '<option value="'.$key.'"'.((isset($this->tpl_values['form_type']) && in_array($key, $this->tpl_values['form_type'])) ? ' selected="selected"' : '').''.$disabled.'>'.htmlspecialchars($name).'</option>';
                                echo '<option value="'.$key.'"'.((isset($this->tpl_values['form_type']) && in_array($key, $this->tpl_values['form_type'])) ? ' selected="selected"' : '').'>'.htmlspecialchars($name).'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>
            
        <div class="form-group">
            <div class="col-sm-6">
                <label for="form_street">*Ulica:</label>
                <input name="form_street" type="text" class="form-control input-sm" id="form_street" placeholder="Ulica" required="required" value="<?php echo ((isset($this->tpl_values['form_street']) && $this->tpl_values['form_street'] != '') ? $this->tpl_values['form_street'] : ''); ?>" />
            </div>
            <div class="col-sm-6">
                <label for="form_postcode">Kod pocztowy:</label>
                <input name="form_postcode" type="text" class="form-control input-sm" id="form_postcode" placeholder="Kod pocztowy" value="<?php echo ((isset($this->tpl_values['form_postcode']) && $this->tpl_values['form_postcode'] != '') ? $this->tpl_values['form_postcode'] : ''); ?>" />
            </div>
        </div>
            
        <div class="form-group">
            <div class="col-sm-6">
                <label for="form_city">*Miasto:</label>
                <input name="form_city" type="text" class="form-control input-sm" id="form_city" placeholder="Miasto" required="required" value="<?php echo ((isset($this->tpl_values['form_city']) && $this->tpl_values['form_city'] != '') ? $this->tpl_values['form_city'] : ''); ?>" />
            </div>
            <div class="col-sm-6">
                <label for="form_country">*Kraj:</label>
                <input name="form_country" type="text" class="form-control input-sm" id="form_country" placeholder="Kraj" required="required" value="<?php echo ((isset($this->tpl_values['form_country']) && $this->tpl_values['form_country'] != '') ? $this->tpl_values['form_country'] : ''); ?>" />
            </div>
        </div>
        
        <div class="form-group">
            <div class="col-sm-12">
                <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/adresy/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy adresów żołnierza</a>
                
                <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                
                <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                <button class="btn btn-success mar_button pull-right" type="submit" name="form_action" value="address_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                    
                <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                    <input type="hidden" name="id_address" value="<?php echo $this->tpl_values['id_address']; ?>" />
                    <button class="btn btn-success mar_button pull-right" type="submit" name="form_action" value="address_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                <?php } ?>
                
            </div>
        </div>
    </form>
</div>
