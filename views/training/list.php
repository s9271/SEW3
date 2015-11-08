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
    <h5>Lista Szkolen</h5>

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
            
            <th align="center"><h6>RDZ/S</h6></th>
            <th class="success" align="center"><h6>LOK/S</h6></th>
            <th align="center"><h6>DT/ROZ/S</h6></th>
            <th class="success" align="center"><h6>DT/ZAK/S</h6></th>

        </tr>
        </thead>
        <tbod>
        <?php foreach ($this->items as $item): ?>
          <tr class="sm">
            <div class="tr">
              <div class="col-sm">
              


                <td class=""><h6 align="left"><?php echo $item['trainingNumber'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['trainingName'] ?></h6></td>
                <td class=""><h6 align="left"><?php echo $item['trainingLocation'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['trainingDate'] ?></h6></td>
                <td class=""><h6 align="left"><?php echo $item['trainingDate1'] ?></h6></td>

                <td><a href="#"style="text-decoration:none"<span>&emsp;</span><span class="glyphicon glyphicon-pencil">&emsp;|&emsp;</span></a><a href="#" style="text-decoration:none"<span class="glyphicon glyphicon-zoom-in"></span></a></td>
                </div>
              </div>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>


    <a href="training.php?action=create"><span class="btn   btn-info"/>Dodaj Szkolenie</a>

</div>
</div>
</div>


<!-- End Body -->

<!-- Footer -->
<?php require('./views/partial/footer.php');?>
<!-- End Footer -->
