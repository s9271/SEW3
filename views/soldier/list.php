<!-- Header -->
<?php require('./views/partial/header.php');?>
<!-- End Header -->


<!-- Top Nav -->
<?php require('./views/partial/top-nav.php');?>
<!-- End Top Nav -->


<!-- Body -->
<div class="container">
  <div class="jumbotron">
    <span class="glyphicon glyphicon-star"</span><h6>S.E.W.</h6>
    <h5>Lista Żołnierzy</h5>

    <?php

    if (isset($this)) {
        if ($this->errors) {
            foreach ($this->errors as $error) {
                echo $error;
            }
        }
        if ($this->messages) {
            foreach ($this->messages as $message) {
                echo $message;
            }
        }
    }
    ?>
    <table class="table table-condensed">
        <thead>
        <tr class="info" align="center">
            <th align="center"><h6>id</h6></th>
            <th class="success" align="center"><h6>IM</h6></th>
            <th align="center"><h6>NZ</h6></th>
            <th class="success" align="center"><h6>D/U</h6></th>
            <th align="center"><h6>PŁ</h6></th>
            <th class="success" align="center"><h6>TL</h6></th>
            <th align="center"><h6>EM</h6></th>
            <th class="success" align="center"><h6>KDPT</h6></th>
            <th align="center"><h6>MT</h6></th>
            <th class="success" align="center"><h6>UL</h6></th>
            <th align="center"><h6>N/L</h6></th>
            <th class="success" align="center"><h6>ST</h6></th>
            <th align="center"><h6>JW/N</h6></th>
            <th class="success" align="center"><h6>MJ</h6></th>
            <th align="center"><h6>SZ</h6></th>
            <th class="success" align="center"><h6>BR</h6></th>
            <th align="center"><h6>NR/B</h6></th>
            <th class="success" align="center"><h6>WP</h6></th>
            <th align="center"><h6>IN</h6></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->items as $item): ?>
            <tr class="sm">
              <div class="tr">
                <div class="col-sm">
                <td><h6><?php echo $item['id'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['soldierName'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['soldierSurname'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['birthday'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['sex'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['phone'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['email'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['code'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['city'] ?></h6></td>
                <td class="success"><h6><?php echo $item['street'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['numberHouse'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['militaryRank'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['jwNumber'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['missions'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['training'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['weapon'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['weaponsNumber'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['equipmentSoldier'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['someText'] ?></h6></td>
                <!--<td><a href="soldier.php?action=update"style="text-decoration:none" data-toggle="popover" data-trigger="hover"<span>&emsp;</span><span class="glyphicon glyphicon-pencil">&emsp;|&emsp;</span></a><a href="#" style="text-decoration:none"<span class="glyphicon glyphicon-zoom-in"></span></a></td>-->
                <td><a href="soldier.php?action=update" style="text-decoration:none"  title="Edytuj" data-toggle="tooltip" data-trigger="hover" data-content="Some content"><span>&emsp;</span><span class="glyphicon glyphicon-pencil">&emsp;|&emsp;</span></a><a href="#" style="text-decoration:none" title="Podgląd" data-toggle="tooltip" data-trigger="hover" data-content="Some content"><span>&emsp;</span><span class="glyphicon glyphicon-zoom-in">&emsp;|&emsp;</span></a></td>
                  <script>
                  $(document).ready(function(){
                      $('[data-toggle="tooltip"]').tooltip();
                  });
                  </script>
                </div>
              </div>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>

    <a href="soldier.php?action=create"><span class="btn   btn-info"/>Dodaj Żołnierza</a>

</div>
</div>
</div>


<!-- End Body -->

<!-- Footer -->
<?php require('./views/partial/footer.php');?>
<!-- End Footer -->
