<div id="soldier_mission_view" class="container soldier_option_page">
    <div class="jumbotron">
        
        <div class="sew-row clearfix no_margin">
            <h1 class="controller_title col-sm-offset-2 col-sm-8 controller_title_option"><?php echo $this->tpl_values['name']; ?></h1>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Data przydziału:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['s2m_date_add']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Użytkownik przydziału:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['s2m_user_add']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Opis przydziału:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['s2m_description_add']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Status:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['s2m_status']; ?></div>
        </div>
        
        <?php if($this->tpl_values['s2m_deleted'] == '1'): ?>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Data oddelegowania:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['s2m_date_delete']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Użytkownik oddelegowania:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['s2m_user_delete']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Opis oddelegowania:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['s2m_description_delete']; ?></div>
        </div>
        <?php endif; ?>
        
        <div class="sew-row clearfix sew-buttons">
            <div class="col-sm-offset-2 col-sm-8 text-right">
                <form method="post" action="">
                    <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/misje" class="btn btn-info"><span class="glyphicon glyphicon-chevron-left"></span>Lista misji żołnierza</a>
                    
                    <?php if($this->tpl_values['s2m_deleted'] == '1'): ?>
        
                    <span class="btn btn-default disabled">Oddeleguj</span>
                    <span class="btn btn-default disabled">Usuń</span>
                    
                    <?php else: ?>
        
                    <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/misje/<?php echo $this->tpl_values['id_mission']; ?>/oddeleguj/<?php echo $this->tpl_values['id_soldier2missions']; ?>" class="btn btn-warning">Oddeleguj</a>
                    <input type="hidden" name="id_soldier2missions" value="<?php echo $this->tpl_values['id_soldier2missions']; ?>" />
                    <button class="btn btn-danger jsconfirm" data-confirm="Czy na pewno chcesz usunąć powiązanie żołnierza z misją?" type="submit" name="form_action" value="soldier_mission_delete">Usuń</button>
                    
                    <?php endif; ?>
                    
                </form>
            </div>
        </div>
        
        
        
        <div class="sew-row clearfix no_margin title_margin_top">
            <h1 class="controller_title col-sm-offset-2 col-sm-8 controller_title_option">Podgląd misji</h1>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Kryptonim misji:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['form_name']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Rodzaj misji:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['type']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Lokalizacja misji:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['form_location']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Opis:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['form_description']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Data rozpoczęcia:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['form_date_start']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Data zakończenia:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['form_date_end']; ?></div>
        </div>
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Aktywna:</div>
            <div class="col-sm-8 row_content"><?php echo ($this->tpl_values['form_active'] == '1' ? 'Tak' : 'Nie'); ?></div>
            <div class="col-sm-offset-2 col-sm-8 clear sew_hint">Gdy misja jest aktywna, można do niej dodać żołnierzy.</div>
        </div>
        
        <div class="sew-row clearfix sew-buttons">
            <div class="col-sm-offset-2 col-sm-8 text-right">
                <a href="#" class="btn btn-primary">Zobacz żołnierzy powiązanych z misją</a>
            </div>
        </div>
        
    </div>
</div>
