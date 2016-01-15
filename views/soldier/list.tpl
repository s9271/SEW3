<div id="soldier_list" class="container">
    <div class="jumbotron">
        <h1 class="controller_title">Lista Żołnierzy</h1>
        
        <?php $this->getAlerts(false); ?>
        
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="table_id">ID</th>
                    <th class="table_name">Imię</th>
                    <th class="table_surname">Nazwisko</th>
                    <th class="table_mail">Adres e-mail</th>
                    <th class="table_phone">Telefon</th>
                    <th class="table_pesel">Pesel</th>
                    <th class="table_status">Status</th>
                    <th class="table_akcje">Akcje</th>
                </tr>
                
                <?php echo $this->getSearchForm(); ?>
            </thead>
            <tbody>
                <?php 
                    if($this->tpl_values['items']):
                    foreach ($this->tpl_values['items'] as $key => $item):
                ?>
                <tr>
                    <td class="table_id"><?php echo $item['id_soldier']; ?></td>
                    <td class="table_name"><?php echo $item['name']; ?></td>
                    <td class="table_surname"><?php echo $item['surname']; ?></td>
                    <td class="table_mail"><?php echo $item['mail']; ?></td>
                    <td class="table_phone"><?php echo $item['phone']; ?></td>
                    <td class="table_pesel"><?php echo $item['pesel']; ?></td>
                    <td class="table_status"><?php echo $item['status_name']; ?></td>
                    <td class="table_akcje">
                        
                        <div class="btn-group-action">
                            <div class="btn-group">
                                <a href="#" class="edit btn btn-primary" title="Podgląd">
                                    <span class="glyphicon glyphicon-zoom-in"></span>Podgląd
                                </a>
                                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                    <span class="glyphicon glyphicon-chevron-down"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="/zolnierze/edytuj/<?php echo $item['id_soldier']; ?>" title="Edytuj">
                                            <span class="glyphicon glyphicon-pencil"></span>Edytuj
                                        </a>
                                    </li>
                                    
                                    <li class="divider"> </li>
                                    <li class="dropdown-header">Informacje wojskowe</li>
                                    <li>
                                        <a href="#" title="Stopień Wojskowy">
                                            <span class="glyphicon glyphicon-screenshot"></span>Stopień Wojskowy
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Wyposażenie">
                                            <span class="glyphicon glyphicon-screenshot"></span>Wyposażenie
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/misje" title="Misje">
                                            <span class="glyphicon glyphicon-move"></span>Misje
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/szkolenia" title="Szkolenia">
                                            <span class="glyphicon glyphicon-check"></span>Szkolenia
                                        </a>
                                    </li>
                                    <li>
                                        <a href="#" title="Odznaczenia">
                                            <span class="glyphicon glyphicon-tags"></span>Odznaczenia
                                        </a>
                                    </li>
                                    
                                    <li class="divider"> </li>
                                    <li class="dropdown-header">Wykształcenie</li>
                                    <li>
                                        <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/szkoly-wyzsze" title="Szkoły wyższe">
                                            <span class="glyphicon glyphicon-screenshot"></span>Szkoły wyższe
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/jezyki" title="Języki">
                                            <span class="glyphicon glyphicon-screenshot"></span>Języki
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/prawo-jazdy" title="Prawo jazdy">
                                            <span class="glyphicon glyphicon-screenshot"></span>Prawo jazdy
                                        </a>
                                    </li>
                                    
                                    <li class="divider"> </li>
                                    <li class="dropdown-header">Dane identyfikacyjne</li>
                                    <li>
                                        <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/dzieci" title="Dzieci">
                                            <span class="glyphicon glyphicon-screenshot"></span>Dzieci
                                        </a>
                                    </li>
                                    <li>
                                        <a href="/zolnierze/<?php echo $item['id_soldier']; ?>/adresy" title="Adresy">
                                            <span class="glyphicon glyphicon-screenshot"></span>Adresy
                                        </a>
                                    </li>
                                    
                                    <li class="divider"> </li>
                                    <li>
                                        <form method="post">
                                            <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                                            <button class="dropdown-menu-button-link jsconfirm" data-confirm="<?php echo $item['name']; ?> <?php echo $item['surname']; ?>: Czy na pewno chcesz usunąć żołnierza?" type="submit" name="form_action" value="soldier_delete"><span class="glyphicon glyphicon-trash"></span> Usuń</button>
                                        </form>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                    
                    
                    
                    
                        <?php /* <form method="post">
                            <input type="hidden" name="id_soldier" value="<?php echo $item['id_soldier']; ?>" />
                            
                            <div class="btn-group btn-group-sm">
                                <a href="/zolnierze/edytuj/<?php echo $item['id_soldier']; ?>" class="btn btn-warning" title="Edytuj">Edytuj</a>
                                <button class="btn btn-danger jsconfirm" data-confirm="<?php echo $item['name']; ?> <?php echo $item['surname']; ?>: Czy na pewno chcesz usunąć żołnierza?" type="submit" name="form_action" value="equipment_delete">Usuń</button>
                            </div>

                        </form> */ ?>
                    </td>
                </tr>
                <?php
                    endforeach;
                    else:
                ?>
                <tr>
                    <td class="table_null" colspan="8">Brak żołnierzy do wyświetlenia</td>
                </tr>
                <?php
                    endif;
                ?>
            </tbody>
        </table>
        
        <?php $this->getPages(); ?>
        
        <a href="/zolnierze/dodaj" class="btn btn-info">Dodaj Żołnierza</a>
    </div>
</div>
