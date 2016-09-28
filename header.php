<?php 

include $_SERVER['DOCUMENT_ROOT'] . "/libs/connect.php";

if(isset($_SESSION['userID'])){

  $sessionUsername = $_SESSION['username'];

  $sessionUserID = $_SESSION['userID'];

  $sessionUsergroup = $_SESSION['usergroup'];

  if($controller->storeSession($sessionUsername,$sessionUserID,$sessionUsergroup)){

  }
/*
  NOTE:  FOR SOME REASON THIS KEEPS ON LOGGING OUT USERS THAT ARE REGISTERED INTO THE DATABASE MUST FIX THIS SOON.

  if($controller->checkSession($sessionUserID) == false){

    $sessionUserID = "";

    $logMessagePrefix = "[Sessions]";

    $logMessageInput = "A session has been ended due to no database entry!";

    $controller->insertLog($sessionUserID,$logMessagePrefix,$logMessageInput);

    session_destroy();


  }

*/


}

?>

<html>

<head>

  <link rel="stylesheet" href="http://localhost/css/bootstrap.css">

  <link rel="stylesheet" href="http://localhost/css/custom.css">

  <title>Marketplace</title>

</head>

<body>

<nav class="navbar navbar-default">

  <div class="container-fluid">

    <div class="navbar-header">

      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">

        <span class="sr-only">Toggle navigation</span>

        <span class="icon-bar"></span>

        <span class="icon-bar"></span>

        <span class="icon-bar"></span>

      </button>

      <a class="navbar-brand" href="#">Marketplace</a>

    </div>

    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

      <ul class="nav navbar-nav">

        <li><a href="http://localhost/">Home</a></li>

        <li><a href="">About</a></li>

      </ul>

      <ul class="nav navbar-nav navbar-right">

        <?php if($controller->isLoggedIn() == false){?>


          <li><a href="/sessions/login/">Login</a></li>

          <li><a href="/sessions/register/">Register</a></li>

        <?php } else { ?>


            <li><a href="/sessions/logout/">Logout</a></li>

        <?php }?>

      </ul>

    </div>

  </div>
  
</nav>

<?php 

if(isset($_GET['success'])){

  if($_GET['success'] == "loginSuccess"){

      $message = "You have successfully logged in!";

  }elseif($_GET['success'] = "logoutSuccess"){

      $message = "You have been successfully logged out";
  
  }elseif($_GET['success'] == "signupSuccess"){

      $message = "Your account has been successfully created!";

  }


  if(isset($message)){?>

<div class="container">

    <div class="alert alert-dismissible alert-success">

         <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>

            <h4>Success!</h4>

              <p><?Php echo $message;?></p>

      </div>

</div>

  <?php

  }


}
?>
