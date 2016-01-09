<div id="soldier_schools_form" class="container mar_custom">
    <div class="jumbotron">
        <h1 class="controller_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(true); ?>
    
        <form method="post" class="form-horizontal">

            <div class="form-group">
                <div class="col-sm-12 require_text">
                    * - wymagane pola
                </div>
            </div>
                
            <div class="form-group">
                <div class="col-sm-6">
                    <label for="form_name">*Nazwa szkoły:</label>
                    <input name="form_name" type="text" class="form-control input-sm" id="form_name" placeholder="Nazwa szkoły" required="required" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" />
                </div>
                <div class="col-sm-6">
                    <label for="form_address">*Adres szkoły:</label>
                    <input name="form_address" type="text" class="form-control input-sm" id="form_address" placeholder="Adres szkoły" required="required" value="<?php echo ((isset($this->tpl_values['form_address']) && $this->tpl_values['form_address'] != '') ? $this->tpl_values['form_address'] : ''); ?>" />
                </div>
            </div>
                
            <div class="form-group">
                <div class="col-sm-6">
                    <label for="form_specialization">*Kierunek szkolenia / specjalizacja:</label>
                    <input name="form_specialization" type="text" class="form-control input-sm" id="form_specialization" placeholder="Kierunek szkolenia / specjalizacja" required="required" value="<?php echo ((isset($this->tpl_values['form_specialization']) && $this->tpl_values['form_specialization'] != '') ? $this->tpl_values['form_specialization'] : ''); ?>" />
                </div>
                <div class="col-sm-6">
                    <label for="form_academic_degree">*Tytuł zawodowy:</label>
                    
                    <select class="input-sm jsselect" id="form_academic_degree" name="form_academic_degree" required="required">
                        <option value="">Wybierz</option>
                        <?php
                            if (isset($this->tpl_values['academic_degrees']) && $this->tpl_values['academic_degrees']){
                                foreach ($this->tpl_values['academic_degrees'] as $key => $name) {
                                    echo '<option value="'.$key.'"'.((isset($this->tpl_values['form_academic_degree']) && $this->tpl_values['form_academic_degree'] == $key) ? ' selected="selected"' : '').'>'.htmlspecialchars($name).'</option>';
                                }
                            }
                        ?>
                    </select>
                </div>
            </div>
                
            <div class="form-group">
                <div class="col-sm-6">
                    <label for="form_date_start">*Data rozpoczęcia:</label>
                    <input id="form_date_start" class="form-control input-sm jsdate" type="text" placeholder="Data rozpoczęcia" name="form_date_start" required="required" value="<?php echo ((isset($this->tpl_values['form_date_start']) && $this->tpl_values['form_date_start'] != '') ? $this->tpl_values['form_date_start'] : ''); ?>" />
                </div>
                <div class="col-sm-6">
                    <label for="form_date_end">*Data zakończenia:</label>
                    <input id="form_date_end" class="form-control input-sm jsdate" type="text" placeholder="Data zakończenia" name="form_date_end" required="required" value="<?php echo ((isset($this->tpl_values['form_date_end']) && $this->tpl_values['form_date_end'] != '') ? $this->tpl_values['form_date_end'] : ''); ?>" />
                </div>
            </div>
            
            <div class="form-group">
                <div class="col-sm-12">
                    <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/szkoly-wyzsze/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy szkół żołnierza</a>
                    
                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                    
                    <?php if($this->tpl_values['sew_action'] == 'add'){ ?>
                    <button class="btn btn-success mar_button pull-right" type="submit" name="form_action" value="school_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                        
                    <?php }elseif($this->tpl_values['sew_action'] == 'edit'){ ?>
                        <input type="hidden" name="id_school" value="<?php echo $this->tpl_values['id_school']; ?>" />
                        <button class="btn btn-success mar_button pull-right" type="submit" name="form_action" value="school_save"><span class="glyphicon glyphicon-floppy-disk"></span>Zapisz</button>
                    <?php } ?>
                    
                </div>
            </div>
        </form>
    </div>
</div>
