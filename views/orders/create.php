<!-- Header -->
<?php require('./views/partial/header.php'); ?>
<!-- End Header -->

<!-- Top Nav -->
<?php require('./views/partial/top-nav.php');?>
<!-- End Top Nav -->


<div class="container">
  <div class="jumbotron">
    <?php
    if (isset($this)) {
        if ($this->errors) {
            foreach ($this->errors as $error) {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
        }
        if ($this->messages) {
            foreach ($this->messages as $message) {
                echo '<div class="alert alert-success" role="alert">' . $message . '</div>';
            }
        }
    }
    ?>

    <h5>Dodaj Odznaczenie</h5>
    <form method="post" action="orders.php?action=create" name="orders-create" class="form-horizontal"><h6>

        <div class="form-group">
            <label for="login_input_username" class="col-sm-2 control-label">Nazwa Odznaczenia *</label>

            <div class="col-sm-8">
                <input id="ordersNumber" class="form-control login_input input-sm" type="text" placeholder="tutaj wpisz nazwe odznaczenia"  name="ordersName" required/>
            </div>
        
        </div>




        <div class="form-group">
           
        </div>
        
        



        <div class="form-group">
            <div class="col-sm-offset-2  control-label col-sm-8">
                <a href="orders.php?action=list"><span class="btn   btn-info"/>Listuj</a>&emsp;<input type="submit" class="btn   btn-success" name="register" value="Zapisz"/>
            </div>
        </div></h6>
    </form>
          <script type="text/javascript">
          $(function () {
              $('#datetimepicker1').datetimepicker();
          });
          </script>
  </div>
</div>



<!-- Footer -->
<?php require('./views/partial/footer.php');?>
<!-- End Footer -->
