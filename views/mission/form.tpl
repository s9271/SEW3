<div id="mission_form" class="container">
    <div class="jumbotron">
        <h1 class="controller_title">Dodaj Misje</h1>
        
        <?php
            if (isset($values['errors'])){
                foreach ($values['errors'] as $error) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                }
            }
        ?>
    
        <form method="post" action="" class="form-horizontal">

            <div class="form-group">
                <label for="form_name" class="col-sm-2 control-label">Kryptonim misji *</label>
                <div class="col-sm-8">
                    <input id="form_name" class="form-control login_input input-sm" type="text" placeholder="Kryptonim misji" name="form_name" required="required" value="<?php echo ((isset($values['form_name']) && $values['form_name'] != '') ? $values['form_name'] : ''); ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <label for="form_type" class="col-sm-2 control-label">Rodzaj misji *</label>
                <div class="col-sm-8">
                    <select class="form-control input-sm" id="form_type" name="form_type" required="required">
                        <option value="0"> ------------------------------- </option>
                        <?php
                            if ($values && isset($values['form_type'])){
                                foreach ($values['form_type'] as $group) {
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
                <label for="form_location" class="col-sm-2 control-label">Lokalizacja misji *</label>
                <div class="col-sm-8">
                    <input id="form_location" class="form-control login_input input-sm" type="text" placeholder="Lokalizacja misji" name="form_location" required="required" value="<?php echo ((isset($values['form_location']) && $values['form_location'] != '') ? $values['form_location'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <label for="form_date_start" class="col-sm-2 control-label">Data rozpoczęcia *</label>
                <div class="col-sm-8">
                    <input id="form_date_start" class="form-control login_input input-sm form_datepicker" type="text" placeholder="Data rozpoczęcia" name="form_date_start" required="required" value="<?php echo ((isset($values['form_date_start']) && $values['form_date_start'] != '') ? $values['form_date_start'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <label for="form_date_end" class="col-sm-2 control-label">Data zakończenia *</label>
                <div class="col-sm-8">
                    <input id="form_date_end" class="form-control login_input input-sm form_datepicker" type="text" placeholder="Data zakończenia" name="form_date_end" required="required" value="<?php echo ((isset($values['form_date_end']) && $values['form_date_end'] != '') ? $values['form_date_end'] : ''); ?>" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-2 control-label col-sm-8">
                    <a href="/misje" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Wstecz</a>
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="mission_add"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    <button class="btn btn-success mar_button" type="submit" name="form_action" value="mission_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                </div>
            </div>
        </form>
        <script type="text/javascript">
            $(function () {
                $('.form_datepicker').datepicker({
                    format: "yyyy-mm-dd"
                });
            });
        </script>
    </div>
</div>
