<div id="soldier_schools_list" class="container soldier_children_list">
    <div class="jumbotron">
        <h1 class="controller_child_title"><?php echo $this->tpl_title; ?></h1>
        
        <?php $this->getAlerts(false); ?>
        
        <div class="row">
            <div class="col-sm-12">
                <table class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="table_id">ID</th>
                            <th class="table_name">Nazwa szkoły</th>
                            <th class="table_address">Adres szkoły</th>
                            <th class="table_specialization">Specjalizacja</th>
                            <th class="table_academic_degree">Tytuł</th>
                            <th class="table_date_start">Data rozpoczęcia</th>
                            <th class="table_date_end">Data zakończenia</th>
                            <th class="table_akcje">Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            if($this->tpl_values['items']):
                            foreach ($this->tpl_values['items'] as $key => $item):
                        ?>
                        <tr>
                            <td class="table_id"><?php echo $item['id_school']; ?></td>
                            <td class="table_name"><?php echo $item['name']; ?></td>
                            <td class="table_address"><?php echo $item['address']; ?></td>
                            <td class="table_specialization"><?php echo $item['specialization']; ?></td>
                            <td class="table_academic_degree"><?php echo $item['academic_degree_name']; ?></td>
                            <td class="table_date_start"><?php echo $item['date_start']; ?></td>
                            <td class="table_date_end"><?php echo $item['date_end']; ?></td>
                            <td class="table_akcje">
                                
                                <form method="post">
                                    <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                    <input type="hidden" name="id_school" value="<?php echo $item['id_school']; ?>" />
                                    
                                    <div class="btn-group btn-group-sm">
                                        <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/szkoly-wyzsze/edytuj/<?php echo $item['id_school']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                                        <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?>: Czy na pewno chcesz usunąć szkołę?" type="submit" name="form_action" value="school_delete">Usuń</button>
                                    </div>

                                </form>
                                
                            </td>
                        </tr>
                        <?php
                            endforeach;
                            else:
                        ?>
                        <tr>
                            <td class="table_null" colspan="8">Żołnierz nie posiada szkół</td>
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
                <a href="/zolnierze/<?php echo $this->tpl_values['id_soldier']; ?>/szkoly-wyzsze/dodaj" class="btn btn-primary mar_button pull-right"><span class="glyphicon glyphicon-plus"></span> Dodaj szkołę</a>
            </div>
        </div>
    </div>
</div>
