<div id="badges_list" class="container">
    <div class="jumbotron">
        <h1 class="controller_title">Lista Odznaczeń</h1>
        
        <?php $this->getAlerts(false); ?>
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="table_id">ID</th>
                    <th class="table_name">Nazwa</th>
                    <th class="table_rank">Ilość stopni</th>
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
                    <td class="table_id"><?php echo $item['id_badge']; ?></td>
                    <td class="table_name"><?php echo $item['name']; ?></td>
                    <td class="table_rank"><?php echo $item['badge_rank_name']['name']; ?></td>
                    <td class="table_status"><?php echo $item['active_name']; ?></td>
                    <td class="table_akcje">
                        <form method="post">
                            <input type="hidden" name="id_badge" value="<?php echo $item['id_badge']; ?>" />
                            
                            <div class="btn-group btn-group-sm">
                                <a href="/odznaczenia/edytuj/<?php echo $item['id_badge']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                                <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć odznaczenie?" type="submit" name="form_action" value="badge_delete">Usuń</button>
                            </div>

                        </form>
                    </td>
                </tr>
                <?php
                    endforeach;
                    else:
                ?>
                <tr>
                    <td class="table_null" colspan="7">Brak odznaczeń do wyświetlenia</td>
                </tr>
                <?php
                    endif;
                ?>
            </tbody>
        </table>
        
        <?php $this->getPages(); ?>
        
        <a href="/odznaczenia/dodaj" class="btn btn-info">Dodaj Odznaczenie</a>
    </div>
</div>
