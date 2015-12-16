<div id="login_guard" class="clearfix">
    <div id="center_block" class="clearfix">
        <h1>SEW - System Ewidencji Wojskowej</h1>
        
        <div class="block clearfix">
            <h2>Guard</h2>
            
            <?php $this->getAlerts(true); ?>
            
            <form method="post" class="form-horizontal">

                <div class="form-group form-group-lg">
                    <div class="col-sm-12 text-right">
                        <label for="form_key" class="control-label">Na adres mail powiązany z kontem <b><?php echo $this->tpl_values['login_name']; ?></b> został wysłany kod, który należy podać poniżej.</label>
                    </div>
                </div>

                <div class="form-group form-group-lg">
                    <div class="col-sm-12">
                        <input id="form_key" class="form-control input-lg" type="text" placeholder="Klucz" name="form_key" value="<?php echo ((isset($this->tpl_values['form_key']) && $this->tpl_values['form_key'] != '') ? $this->tpl_values['form_key'] : ''); ?>" />
                    </div>
                </div>
                
                <div class="form-group buttons">
                    <div class="control-label col-sm-12">
                        <button class="btn btn-danger btn-lg pull-left" type="submit" name="form_action" value="logout">Anuluj</button>
                        <button class="btn btn-info btn-lg" type="submit" name="form_action" value="guard_resend">Wyślij ponowinie</button>
                        <button class="btn btn-success btn-lg" type="submit" name="form_action" value="guard">Potwierdź</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
