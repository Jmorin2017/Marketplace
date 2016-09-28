<?php 

class Controller {

	private $db;

	function __construct($conn){

		$this->db = $conn;

	}

	public function isLoggedIn(){

		if(isset($_SESSION['userID'])){

			return true;

		} else {

			return false;

		}

	}

	public function redirect($url){

		header("Location:  $url");

	}

	public function logout($sessionUserID){

		$logMessagePrefix = "[Sessions]";

		$logMessageInput = "Has logged out!";

		if($this->insertLog($sessionUserID,$logMessagePrefix,$logMessageInput)){

				$query = $this->db->prepare("DELETE FROM session WHERE sessionUserID = :sessionUserID");

				if($query->execute(array(":sessionUserID"=>$sessionUserID))){

					session_destroy();

					return true;

				}

			}
	}

	public function insertLog($sessionUserID,$logMessagePrefix,$logMessageInput){

		if(!isset($sessionUserID)){

			$logmessage = $logMessagePrefix . $logmessageInput;

			$stmt = $this->db->prepare("INSERT INTO adminlogs (logMessage) VALUES (:logMessage)");

			$stmt->bindParam(":logMessage",$logMessage);

		} else {

		$queryMessage = $this->db->prepare("SELECT * FROM users WHERE userID = :userID");

		$queryMessage->execute(array(":userID"=>$sessionUserID));

		$row = $queryMessage->fetch(PDO::FETCH_ASSOC);

		$username = $row['username'];

		$logMessage = $logMessagePrefix ." \"$username\" ". $logMessageInput;

		$stmt = $this->db->prepare("INSERT INTO adminlogs (logMessage) VALUES (:logMessage)");

		$stmt->bindParam(":logMessage",$logMessage);

		if($stmt->execute()){

				return true;

			}

		}


	}

	public function createUser($username,$email,$password,$userIP,$usergroup){

		$security = [

			'cost' => 11,
    		'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),

		];

		$protectedPassword = password_hash($password,PASSWORD_BCRYPT,$security);

		$query = $this->db->prepare("INSERT INTO users (username,email,password,userIP,usergroup) VALUES (:username,:email,:password,:userIP,:usergroup)");

		$query->bindParam(":username",$username);

		$query->bindParam(":email",$email);

		$query->bindParam(":password",$protectedPassword);

		$query->bindParam(":userIP",$userIP);

		$query->bindParam(":usergroup",$usergroup);

		$query->execute();

		return $success = 1;

	}

	public function loginUser($usernameEmail,$password){
       		
		$query = $this->db->prepare("SELECT * FROM users WHERE email = :usernameEmail OR username = :usernameEmail");

		$query->execute(array(":usernameEmail"=>$usernameEmail));

		$rows = $query->fetch(PDO::FETCH_ASSOC);

		$sessionUserID = $rows['userID'];

		$logMessagePrefix = "[Sessions]";

		$logMessageInput = "Has logged in!";

		$this->insertLog($sessionUserID,$logMessagePrefix,$logMessageInput);
		
		if(password_verify($password, $rows['password'])){

			$_SESSION['userID'] = $rows['userID'];

			$_SESSION['username'] = $rows['username'];

			$_SESSION['usergroup'] = $rows['usergroup'];

			return true;

		} else {

			return false;

		}

	}

	public function storeSession($sessionUsername,$sessionUserID,$sessionUsergroup){
	
		$query = $this->db->prepare("INSERT INTO session (sessionUsername,sessionUserID,sessionUsergroup) VALUES (:sessionUsername, :sessionUserID,:sessionUsergroup)");

		$query->bindParam(":sessionUsername",$sessionUsername);

		$query->bindParam(":sessionUserID",$sessionUserID);

		$query->bindParam("sessionUsergroup",$sessionUsergroup);

		$query->execute();

		return true;

	}

	public function checkSession($sessionUserID){

		$query = $this->db->prepare("SELECT * FROM session WHERE sessionUserID = :sessionUserID");

		$query->execute(array("sessionUserID"=>$sessionUserID));

		$rows = $query->rowCount();

		if($rows != 1){

			$query = $this->db->prepare("DELETE FROM session WHERE sessionUserID = :sessionUserID");

			$query->execute(array(":sessionUserID"=>$sessionUserID));

			return false;

		} else {

			return true;

		}

	}


}






