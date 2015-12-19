<div id="military_list" class="container">
    <div class="jumbotron">
        <h1 class="controller_title">Lista Jednostek Wojskowych</h1>
        
        <?php $this->getAlerts(false); ?>
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="table_id">ID</th>
                    <th class="table_number">Numer</th>
                    <th class="table_name">Nazwa</th>
                    <th class="table_lokalizacja">Lokalizacja</th>
                    <th class="table_rodzaj">Rodzaj jednostki</th>
                    <th class="table_status">Status</th>
                    <th class="table_akcje">Akcje</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if($this->tpl_values):
                    foreach ($this->tpl_values as $key => $item):
                ?>
                <tr>
                    <td class="table_id"><?php echo $item['id_military']; ?></td>
                    <td class="table_number"><?php echo $item['number']; ?></td>
                    <td class="table_name"><?php echo $item['name']; ?></td>
                    <td class="table_lokalizacja"><?php echo $item['location']; ?></td>
                    <td class="table_rodzaj"><?php echo $item['military_group_name']; ?></td>
                    <td class="table_status"><?php echo $item['active_name']; ?></td>
                    <td class="table_akcje">
                        <form method="post">
                            <input type="hidden" name="id_military" value="<?php echo $item['id_military']; ?>" />
                            
                            <div class="btn-group btn-group-sm">
                                <a href="/jednostki/edytuj/<?php echo $item['id_military']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                                <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć jednostkę?" type="submit" name="form_action" value="military_delete">Usuń</button>
                            </div>

                        </form>
                    </td>
                </tr>
                <?php
                    endforeach;
                    else:
                ?>
                <tr>
                    <td class="table_null" colspan="7">Brak jednostek do wyświetlenia</td>
                </tr>
                <?php
                    endif;
                ?>
            </tbody>
        </table>
        
        <?php $this->getPages(); ?>
        
        <a href="/jednostki/dodaj" class="btn btn-info">Dodaj Jednostkę wojskową</a>
    </div>
</div>
