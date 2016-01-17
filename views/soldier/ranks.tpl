<div id="soldier_ranks_list" class="container soldier_children_list mar_custom">
    <div class="jumbotron">
        <h1 class="controller_child_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <div class="row">
            <div class="col-sm-8">
                <form method="post" class="clearfix">
                    <table class="table table_add table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="table_name">Nazwa stopnia wojskowego</th>
                                <th class="table_date">Data przyznania</th>
                                <th class="table_akcje">Akcje</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="table_name">
                                    <input name="form_name" type="text" class="form-control input-sm" id="form_name" placeholder="Nazwa stopnia wojskowego" value="<?php echo ((isset($this->tpl_values['form_name']) && $this->tpl_values['form_name'] != '') ? $this->tpl_values['form_name'] : ''); ?>" required="required" />
                                </td>
                                <td class="table_date">
                                    <input id="form_date" class="form-control input-sm jsdate" type="text" placeholder="Data" name="form_date" required="required" value="<?php echo ((isset($this->tpl_values['form_date']) && $this->tpl_values['form_date'] != '') ? $this->tpl_values['form_date'] : ''); ?>" />
                                </td>
                                <td class="table_akcje">
                                    <input type="hidden" name="id_soldier" value="<?php echo $this->tpl_values['id_soldier']; ?>" />
                                    <button class="btn btn-primary mar_button" type="submit" name="form_action" value="rank_add"><span class="glyphicon glyphicon-plus"></span>Dodaj</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
            </div>
        </div>
        
        <?php if($this->tpl_values['actually_rank']): ?>
        
        <h4 class="page-header no_margin_top">Aktualny stopień wojskowy</h4>
        
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table_now table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_id">ID</th>
                            <th class="table_name">Nazwa</th>
                            <th class="table_date">Data przyznania</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="table_id"><?php echo $this->tpl_values['actually_rank']['id_rank']; ?></td>
                            <td class="table_name"><?php echo $this->tpl_values['actually_rank']['name']; ?></td>
                            <td class="table_date"><?php echo $this->tpl_values['actually_rank']['date_add_rank']; ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php endif; ?>
        
        <h4 class="page-header">Lista stopni wojskowych</h4>
        
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover table_items">
                    <thead>
                        <tr>
                            <th class="table_id">ID</th>
                            <th class="table_name">Nazwa</th>
                            <th class="table_date">Data przyznania</th>
                            <th class="table_akcje">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if($this->tpl_values['items']):
                            foreach ($this->tpl_values['items'] as $key => $item):
                        ?>
                        <tr>
                            <td class="table_id"><?php echo $item['id_rank']; ?></td>
                            <td class="table_name"><?php echo $item['name']; ?></td>
                            <td class="table_date"><?php echo $item['date_add_rank']; ?></td>
                            <td class="table_akcje">
                                
                                <form method="post">
                                    <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                    <input type="hidden" name="id_rank" value="<?php echo $item['id_rank']; ?>" />
                                    
                                    <div class="btn-group btn-group-sm">
                                        <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć stopień wojskowy?" type="submit" name="form_action" value="rank_delete">Usuń</button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                        <?php
                            endforeach;
                            else:
                        ?>
                        <tr>
                            <td class="table_null" colspan="4">Żołnierz nie posiada stopnia wojskowego.</td>
                        </tr>
                        <?php
                            endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php $this->getPages(); ?>
        
        <a href="/zolnierze" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy żołnierzy</a>
        <a href="/zolnierze/podglad/<?php echo $this->tpl_values['id_soldier']; ?>" class="btn btn-primary mar_button">Podgląd żołnierza</a>
    </div>
</div>
