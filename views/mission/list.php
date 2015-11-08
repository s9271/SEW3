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
    <h5>Lista Misji</h5>

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
            <th class="success"align="center"><h6>KRP/M</h6></th>
            <th align="center"><h6>RDZ/M</h6></th>
            <th class="success" align="center"><h6>LOK/M</h6></th>
            <th align="center"><h6>DT/ROZ/M</h6></th>
            <th class="success" align="center"><h6>DT/ZAK/M</h6></th>

        </tr>
        </thead>
        <tbod>
        <?php foreach ($this->items as $item): ?>
          <tr class="sm">
            <div class="tr">
              <div class="col-sm">
                <td><h6><?php echo $item['id'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['missionNumber'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['missionName'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['missionLocation'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['missionDate'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['missionDate1'] ?></h6></td>

                <td><a href="#"style="text-decoration:none"<span>&emsp;</span><span class="glyphicon glyphicon-pencil">&emsp;|&emsp;</span></a><a href="#" style="text-decoration:none"<span class="glyphicon glyphicon-zoom-in"></span></a></td>
                </div>
              </div>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>


    <a href="mission.php?action=create"><span class="btn   btn-info"/>Dodaj Misje</a>

</div>
</div>
</div>


<!-- End Body -->

<!-- Footer -->
<?php require('./views/partial/footer.php');?>
<!-- End Footer -->
