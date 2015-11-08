<!DOCTYPE html>
<html lang="en">
<head>
  <title>Bootstrap Example</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container"></br>
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">


      <form class="form-horizontal" role="form">
        <div class="form-group">
          <label class="control-label col-sm-2" for="email">Email:</label>
          <div class="col-sm-8">
            <input type="email" class="form-control" id="email" placeholder="Enter email">
          </div>
        </div>
        <fieldset disabled>
        <div class="form-group">
          <label class="control-label col-sm-2" for="disabledTextInput">Reset:</label>
          <div class="col-sm-8">
            <input type="password" class="form-control" id="pwd" placeholder="Zapomniałem hasła, proszę o zresetowanie.">
          </div>
        </div>
        </fieldset>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-warning">Wyślij</button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>
</div>
</div>
</body>
</html>
