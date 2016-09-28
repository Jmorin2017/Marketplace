<?php 

include $_SERVER['DOCUMENT_ROOT'] . "/libs/header.php";

if(isset($_POST['loginUser'])){	

	$usernameEmail = $_POST['usernameEmail'];

	$password = $_POST['password'];

	$ip = $_SERVER['REMOTE_ADDR'];

	$stmt = $conn->prepare("SELECT * FROM users WHERE username = :usernameEmail OR email = :usernameEmail");
	
	$stmt->execute(array(":usernameEmail"=>$usernameEmail,));

	$count = $stmt->rowCount();

	if($count != 1){

		$error = "Username/Password is incorrect";

		$sessionUserID = "";

		$logMessagePrefix = "[Login Attempt]";

		$logMessageInput = $ip . "Failed a login attempt to";

		$controller->insertLog($sessionUserID,$logMessagePrefix,$logMessageInput);

	} elseif($controller->loginUser($usernameEmail,$password)){

		header("Location:  http://localhost/?success=loginSuccess");

	}

}
?>

<div class="container">

	<div class="well">

		<h1>Login</h1>

		<hr />

		<?php if(isset($error)){?>

			<div class="alert alert-danger" role="alert"><?php echo $error;?></div>

			<?php }?>
		<form action="" method="post">

			<input type="text" placeholder="Username/Email" name="usernameEmail" class="form-control"><br>

			<input type="password" placeholder="Password" name="password" class="form-control"><br>

			<input type="submit" class="btn btn-submit" value="Login" name="loginUser"><br>

		</form>

	</div>

</div>

<?php include $_SERVER['DOCUMENT_ROOT'] . "/libs/footer.php";?>