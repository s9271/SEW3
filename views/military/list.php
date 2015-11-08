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
    <h5>Lista Jednostek</h5>

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
            <th class="success" align="center"><h6>NZ/JD</h6></th>
            <th align="center"><h6>NR/JD</h6></th>
            <th class="success" align="center"><h6>RD/WJ</h6></th>
            <th align="center"><h6>DW/JD</h6></th>
            <th class="success" align="center"><h6>ST</h6></th>
            <th align="center"><h6>TL</h6></th>
            <th class="success" align="center"><h6>EM</h6></th>
            <th align="center"><h6>KD/PT</h6></th>
            <th class="success" align="center"><h6>MS</h6></th>
            <th align="center"><h6>UL</h6></th>
            <th class="success" align="center"><h6>NR</h6></th>
        </tr>
        </thead>
        <tbod>
        <?php foreach ($this->items as $item): ?>
          <tr class="sm">
            <div class="tr">
              <div class="col-sm">
                <td><h6><?php echo $item['id'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['militaryName'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['militaryNumber'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['militarySelect'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['militaryCommander'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['militaryCommander1'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['phone'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['email'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['code'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['city'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['street'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['numberHouse'] ?></h6></td>
                <td><a href="#"style="text-decoration:none"<span>&emsp;</span><span class="glyphicon glyphicon-pencil">&emsp;|&emsp;</span></a><a href="#" style="text-decoration:none"<span class="glyphicon glyphicon-zoom-in"></span></a></td>
                </div>
              </div>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>


    <a href="military.php?action=create"><span class="btn   btn-info"/>Dodaj Jednostkę Wojskową</a>

</div>
</div>
</div>


<!-- End Body -->

<!-- Footer -->
<?php require('./views/partial/footer.php');?>
<!-- End Footer -->
