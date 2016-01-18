<div id="soldier_badges_view" class="container mar_custom">
    <div class="jumbotron">
        <h1 class="controller_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <h4 class="page-header no_margin_top">Dane powiązania do odznaczenia</h4>
        
        <div class="row">
            <div class="col-sm-3 text-right row_title">Status:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['status_name']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Typ odznaczenia:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['badge_type']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Data przyznania:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['date_grant']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Dodatkowe informacje:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['description']; ?></div>
            </div>
        </div>
        
        <?php if($this->tpl_values['received'] == '1'){ ?>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Data odebrania:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['date_receive']; ?></div>
            </div>
        </div>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Powód odebrania:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['description_receive']; ?></div>
            </div>
        </div>
        
        <?php } ?>
        
        <div class="row margin_top_15">
            <div class="col-sm-12">
                <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/odznaczenia/" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy odznaczeń żołnierza</a>
                
                <?php if($this->tpl_values['received'] == '0' || $this->tpl_values['id_login_permission'] == '1'){ ?>
        
                <form method="post" class="pull-right">
                    <?php if($this->tpl_values['received'] == '0'){ ?>
                    
                        <a class="btn btn-warning mar_button" href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/odznaczenia/edytuj/<?php echo $this->tpl_values['id_soldier2badges']; ?>" title="Edytuj">
                            <span class="glyphicon glyphicon-pencil"></span>Edytuj
                        </a>
                    
                        <a class="btn btn-warning mar_button" href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/odznaczenia/odbierz/<?php echo $this->tpl_values['id_soldier2badges']; ?>" title="Odbierz">
                            <span class="glyphicon glyphicon-pencil"></span>Odbierz
                        </a>
                    
                    <?php } ?>
                    
                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                    <input type="hidden" name="id_soldier2badges" value="<?php echo $this->tpl_values['id_soldier2badges']; ?>" />
                    <input type="hidden" name="id_badge" value="<?php echo $this->tpl_values['id_badge']; ?>" />
                    <button class="btn btn-danger jsconfirm" data-confirm="Czy na pewno chcesz usunąć te odznaczenie?" type="submit" name="form_action" value="badge_delete"><span class="glyphicon glyphicon-trash"></span> Usuń powiązanie</button>
                </form>
                
                <?php } ?>
                
            </div>
        </div>
        
        <h4 class="page-header">Dane Odznaczenia</h4>
        
        <?php if($this->tpl_values['badge']->load_class == '1'){ ?>
        
        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Nazwa:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['badge']->name; ?></div>
            </div>
        </div>

        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Ilość stopni:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['badge']->badge_rank_name['name']; ?></div>
            </div>
        </div>

        <div class="row margin_top_15">
            <div class="col-sm-3 text-right row_title">Aktywna:</div>
            <div class="col-sm-9">
                <div class="row_content"><?php echo $this->tpl_values['badge']->active_name; ?></div>
            </div>
            <div class="col-sm-offset-3 col-sm-9 clear sew_hint">Gdy odznaczenie jest aktywne, można do niego dodać żołnierzy.</div>
        </div>
        
        <?php }else{ ?>
        
        <div class="row margin_top_15">
            <div class="col-sm-12 text-center sew_red">Odznaczenie nie istnieje</div>
        </div>
        
        <?php } ?>
        
    </div>
</div>
