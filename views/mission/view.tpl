<div id="mission_view" class="container">
    <div class="jumbotron">
        
        <div class="sew-row clearfix no_margin">
            <h1 class="controller_title col-sm-offset-2 col-sm-8">Podgląd misji: <?php echo $this->tpl_values['form_name']; ?></h1>
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
        
        <div class="sew-row clearfix">
            <div class="col-sm-2 text-right row_title">Status:</div>
            <div class="col-sm-8 row_content"><?php echo $this->tpl_values['status']; ?></div>
        </div>
        
        <div class="sew-row clearfix sew-buttons">
            <div class="col-sm-offset-2 col-sm-8 text-right">
                <form method="post" action="/misje">
                    <a href="/misje" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Lista</a>
                    <a href="/misje/edytuj/<?php echo $this->tpl_values['id_mission']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                    <input type="hidden" name="id_mission" value="<?php echo $this->tpl_values['id_mission']; ?>" />
                    <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $this->tpl_values['form_name']; ?>: Czy na pewno chcesz usunąć misję?" type="submit" name="form_action" value="mission_delete">Usuń</button>
                </form>
            </div>
        </div>
        
        <?php if(isset($this->tpl_values['log']) && $this->tpl_values['log']): ?>
        <div id="log_table" class="sew-row clearfix">
            <div class="clearfix col-sm-offset-2 col-sm-8 no_padding">
                <h1 class="controller_title">Ostatnie zmiany:</h1>
                
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="log_date_edit">Data zmiany</th>
                            <th class="log_user">Użytkownik</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?php echo $this->tpl_values['date_update']; ?></td>
                            <td>DO ZMIANY (czeka na klase AUTH)</td>
                        </tr>
                    <?php foreach($this->tpl_values['log'] as $item_log): ?>
                        <tr>
                            <td><?php echo $item_log['date_update']; ?></td>
                            <td><?php echo $item_log['first_name']; ?> <?php echo $item_log['second_name']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
