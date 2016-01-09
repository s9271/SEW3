<div id="soldier_addresses_list" class="container soldier_children_list">
    <div class="jumbotron">
        <h1 class="controller_child_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_id">ID</th>
                            <th class="table_street">Ulica</th>
                            <th class="table_postcode">Kod pocztowy</th>
                            <th class="table_city">Miasto</th>
                            <th class="table_country">Kraj</th>
                            <th class="table_type">Typ</th>
                            <th class="table_akcje">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if($this->tpl_values['items']):
                            foreach ($this->tpl_values['items'] as $key => $item):
                        ?>
                        <tr>
                            <td class="table_id"><?php echo $item['id_address']; ?></td>
                            <td class="table_street"><?php echo $item['street']; ?></td>
                            <td class="table_postcode"><?php echo $item['postcode']; ?></td>
                            <td class="table_city"><?php echo $item['city']; ?></td>
                            <td class="table_country"><?php echo $item['country']; ?></td>
                            <td class="table_type">
                            <?php
                                if (isset($item['soldier_address_types']) && $item['soldier_address_types']){
                                    foreach ($item['soldier_address_types'] as $soldier_address_type){
                                        echo '<span class="center-block">'.htmlspecialchars($soldier_address_type['name']).'</span>';
                                    }
                                }
                            ?>
                            </td>
                            <td class="table_akcje">
                                
                                <form method="post">
                                    <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                    <input type="hidden" name="id_address" value="<?php echo $item['id_address']; ?>" />
                                    
                                    <div class="btn-group btn-group-sm">
                                        <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/adresy/edytuj/<?php echo $item['id_address']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                                        <button class="btn btn-danger jsconfirm" data-confirm="Czy na pewno chcesz usunąć adres?" type="submit" name="form_action" value="address_delete">Usuń</button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                        <?php
                            endforeach;
                            else:
                        ?>
                        <tr>
                            <td class="table_null" colspan="7">Żołnierz nie posiada adresów</td>
                        </tr>
                        <?php
                            endif;
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <?php $this->getPages(); ?>
        
        <div class="row">
            <div class="col-sm-12">
                <a href="/zolnierze" class="btn btn-info mar_button"><span class="glyphicon glyphicon-chevron-left"></span>Powrót do listy żołnierzy</a>
                <a href="/zolnierze/podglad/<?php echo $this->tpl_values['id_soldier']; ?>" class="btn btn-primary mar_button">Podgląd żołnierza</a>
                <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/adresy/dodaj" class="btn btn-primary mar_button pull-right"><span class="glyphicon glyphicon-plus"></span> Dodaj adres</a>
            </div>
        </div>
    </div>
</div>
