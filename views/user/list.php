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
    <h5>Lista Użytkowników </h5>

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
            <th class="success" align="center"><h6>KRYP</h6></th>
            <th align="center"><h6>IM</h6></th>
            <th class="success" align="center"><h6>NZW</h6></th>
            <th align="center"><h6>D/U</h6></th>
            <th class="success" align="center"><h6>HS</h6></th>
            <th align="center"><h6>EM</h6></th>
            <th class="success" align="center"><h6>TL</h6></th>
            <th align="center"><h6>ST</h6></th>
            <th class="success" align="center"><h6>KDPT</h6></th>
            <th align="center"><h6>UL</h6></th>
            <th align="center"><h6>NR</h6></th>
            <th align="center"><h6>JW/N</h6></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($this->items as $item): ?>
            <tr class="sm">
              <div class="tr">
                <div class="col-sm">
                <td><h6><?php echo $item['user_id'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['user_name'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['first_name'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['second_name'] ?></h6></td>
                <td><h6><?php echo $item['birthday'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['user_password_hash'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['user_email'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['phone'] ?></h6></td>
                <td><h6><?php echo $item['militaryRank'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['code'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['street'] ?></h6></td>
                <td class="success"><h6 align="left"><?php echo $item['numberHouse'] ?></h6></td>
                <td><h6 align="left"><?php echo $item['jwNumber'] ?></h6></td>
                <td><a href="#"style="text-decoration:none"<span>&emsp;</span><span class="glyphicon glyphicon-pencil">&emsp;|&emsp;</span></a><a href="#" style="text-decoration:none"<span class="glyphicon glyphicon-zoom-in"></span></a></td>
                </div>
              </div>
            </tr>
        <?php endforeach;?>
        </tbody>
    </table>


    <a href="user.php?action=create"><span class="btn   btn-info"/>Dodaj Użytkownika</a>

</div>
</div>
</div>


<!-- End Body -->

<!-- Footer -->
<?php require('./views/partial/footer.php');?>
<!-- End Footer -->
