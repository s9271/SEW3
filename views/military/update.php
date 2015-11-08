<!-- Header -->
<?php require('./views/partial/header.php'); ?>
<!-- End Header -->

<!-- Top Nav -->
<?php require('./views/partial/top-nav.php');?>
<!-- End Top Nav -->

<div class="container">
  <div class="jumbotron">
    <?php
    if (isset($registration)) {
        if ($registration->errors) {
            foreach ($registration->errors as $error) {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
        }
        if ($registration->messages) {
            foreach ($registration->messages as $message) {
                echo '<div class="alert alert-danger" role="alert">' . $message . '</div>';
            }
        }
    }
    ?>

    <h2>Soldier :: update</h2>

  </div>
</div>
