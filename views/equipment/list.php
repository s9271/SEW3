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
    <h5>Uzbrojenie</h5>

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
            <th align="center"><h6>Numer Ewidencyjny</h6></th>
            <th class="success"align="center"><h6>TYP/U</h6></th>
             <th class="success"align="center"><h6>RODZ/U</h6></th>
             <th class="success"align="center"><h6>SER/U</h6></th>
            

        </tr>
        </thead>
        <tbod>
        <?php foreach ($this->items as $item): ?>
          <tr class="sm">
            <div class="tr">
              <div class="col-sm">
                
                <td class="success"><h6 align="left"><?php echo $item['equipmentNumber'] ?></h6></td>
               
                <td class="success"><h6 align="left"><?php echo $item['equipmentType'] ?></h6></td>

                <td class="success"><h6 align="left"><?php echo $item['equipmentMark'] ?></h6></td>
               
                <td class="success"><h6 align="left"><?php echo $item['equipmentSerial'] ?></h6></td>


                

                <td><a href="#"style="text-decoration:none"<span>&emsp;</span><span class="glyphicon glyphicon-pencil">&emsp;|&emsp;</span></a><a href="#" style="text-decoration:none"<span class="glyphicon glyphicon-zoom-in"></span></a></td>
                </div>
              </div>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>


    <a href="equipment.php?action=create"><span class="btn   btn-info"/>Dodaj Uzbrojenie</a>

</div>
</div>
</div>


<!-- End Body -->

<!-- Footer -->
<?php require('./views/partial/footer.php');?>
<!-- End Footer -->
