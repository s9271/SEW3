<div id="sew_alert" class="new_admin_tpl">
    <?php echo (isset($this->tpl_values['title']) && $this->tpl_values['title'] != '') ? '<h1 class="controller_title">'.$this->tpl_values['title'].'</h1>' : ''; ?>
    
    <?php $this->getAlerts(false); ?>
    
    <?php if(isset($this->tpl_values['wstecz']) && $this->tpl_values['wstecz'] != ''){ ?>
        <div class="row">
            <div class="col-sm-12">
                <a href="<?php echo $this->tpl_values['wstecz']; ?>" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Wstecz</a>
            </div>
        </div>
    <?php } ?>
</div>
