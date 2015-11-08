<!-- Header -->
<?php require('views/partial/header.php');?>
<!-- End Header -->




<div class="container">
    <div class="alert alert-danger" role="alert" ><span class="glyphicon glyphicon-star"</span><h4>S.E.W.</h4>Wypełnij i wyślij. Sprawdź pocztę!</div>

<?php
if (isset($registration)) {
    if ($registration->errors) {
        foreach ($registration->errors as $error) {
            echo '<div class="alert alert-danger" role="alert">' . $error .'</div>';
        }
    }
    if ($registration->messages) {
        foreach ($registration->messages as $message) {
            echo '<div class="alert alert-danger" role="alert">' . $message .'</div>';
        }
    }
}
?>

<div class="alert alert-danger" role="alert">
<form method="post" action="#" name="registerform" class="form-horizontal">

    <div class="form-group">
      <label for="login_input_username" class="col-sm-2 control-label">Użytkownik</label>
    <div class="col-sm-10">
      <input id="login_input_username" class="form-control login_input" type="text" pattern="[a-zA-Z0-9]{2,64}" name="user_name" required />
      </div>
    </div>
    <div class="form-group">
      <label for="login_input_email" class="col-sm-2 control-label">Email</label>
    <div class="col-sm-10">
      <input id="login_input_email" class="form-control login_input" type="email" name="user_email" required />
      </div>
    </div>
    <div class="form-group">
      <div class="col-sm-offset-2  control-label col-sm-10">
        <input type="submit" class="btn  btn-success" name="register" value="Wyślij" />
      </div>
    </div>
</form>
</div>
<div class="form-group">
  <div class="col-sm-offset-2  control-label col-sm-8">
<a href="index.php"><input type="submit" class="btn btn-primary" value="Logowanie" /></a>
</div>
</div>


</div>
