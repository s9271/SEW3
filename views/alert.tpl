<div id="sew_alert" class="container">
    <div class="jumbotron">
        
        <?php echo (isset($this->tpl_values['title']) && $this->tpl_values['title'] != '') ? '<h1 class="controller_title">'.$this->tpl_values['title'].'</h1>' : ''; ?>
        
        <?php $this->getAlerts(false); ?>
        
        <?php if(isset($this->tpl_values['wstecz']) && $this->tpl_values['wstecz'] != ''){ ?>
            <a href="<?php echo $this->tpl_values['wstecz']; ?>" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Wstecz</a>
        <?php } ?>
        
    </div>
</div>
