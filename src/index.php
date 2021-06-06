<?php
session_start();

require_once "./auth.php";
extract($_POST);

if (isset($email)) {
  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errMsg = "Not a valid email";
  }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Title of the document</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
  <style>
    .input-field {
      width: 50%;
      margin: 30px auto;
    }

    .container {
      margin-top: 150px;
    }
  </style>
</head>

<body>
  <nav>
    <div class="nav-wrapper orange">
      <a href="#" class="brand-logo center">Task Manager</a>
      <ul id="nav-mobile" class="right">
        <li><a href="register.php">Register</a></li>

      </ul>
    </div>
  </nav>

  <div class="container">

    <form action="login.php" method="post">

      <div class="input-field">
        <i class="material-icons prefix">account_circle</i>
        <input name="email" id="email" type="text" class="validate">
        <label for="email">Email</label>
      </div>
      <div class="input-field">
        <i class="material-icons prefix">lock</i>
        <input name="password" id="password" type="password" class="validate">
        <label for="password">Password</label>
      </div>

      <div class="center">

        <button class="btn waves-effect waves-light orange" type="submit" name="action">Login

        </button>
      </div>


    </form>

  </div>

  <?php
  if (isset($errMsg)) {
    echo "<script> M.toast({html: '$errMsg', classes: 'red white-text'}) ; </script>";
  }
  ?>

  <script>
    $(function() {

    })
  </script>
</body>

</html>