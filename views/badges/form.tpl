<div id="badge_form" class="new_admin_tpl">

    <?php $this->getAlerts(false); ?>

    <form method="post" class="form-horizontal">

        <div class="form-group">
            <div class="col-sm-12 require_text">
                * - wymagane pola
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_name" class="control-label">*Nazwa odznaczenia:</label>
            </div>
            <div class="col-sm-9">
                <input id="form_name" class="form-control input-sm" type="text" placeholder="Nazwa odznaczenia" name="form_name" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_rank" class="control-label">*Rodzaj odznaczenia:</label>
            </div>
            <div class="col-sm-9">
                <select class="form-control input-sm jsselect" id="form_rank" name="form_rank" required="required">
                    <option value="">Wybierz</option>
                    <?php
                        if (isset($this->tpl_values['form_ranks']) && $this->tpl_values['form_ranks']){
                            foreach ($this->tpl_values['form_ranks'] as $key => $rank) {
                                echo '<option value="'.$key.'"'.((isset($this->tpl_values['form_rank']) && $this->tpl_values['form_rank'] == $key) ? ' selected="selected"' : '').'>'.$rank.'</option>';
                            }
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-3 text-right">
                <label for="form_active" class="control-label">Aktywna:</label>
            </div>
            <div class="col-sm-9">
                <label class="checkbox-inline">
                    <input type="checkbox" id="form_active" name="form_active" value="1"<?php echo ((isset($this->tpl_values['form_active']) && $this->tpl_values['form_active'] == '1') ? ' checked="checked"' : ''); ?> /> Gdy odznaczenie jest aktywne, można do niego dodać żołnierzy.
                </label>
            </div>
        </div>
        
        <div class="form-group margin_top_50">
            <div class="col-sm-12 text-right">
                <a href="/odznaczenia" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy odznaczeń</a>
                
                <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="badge_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                    
                <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                
                    <a href="/odznaczenia/podglad/<?php echo $this->tpl_values['id_badge']; ?>" class="btn btn-primary mar_button">Podgląd odznaczenia</a>
                    <input type="hidden" name="id_badge" value="<?php echo $this->tpl_values['id_badge']; ?>" />
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="badge_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    
                <?php } ?>
                
            </div>
        </div>
    </form>
</div>
