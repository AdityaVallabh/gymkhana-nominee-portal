
<?php

Include 'connection.php';

session_start();
if(isset($_SESSION["type"]) && $_SESSION["type"] == 'student')
	header("location: welcome.php");
else if(isset($_SESSION["type"]) && $_SESSION["type"] == 'admin')
	header("location: mod.php");
?>

<html>
<head>
	<title>Login Page</title>
	<link href="css/index.css" rel="stylesheet" type="text/css">
</head>

<body >
	<h1 style="text-align: center"><img src="./images/iiita.png"></h1>
	<p style="text-align: center"><img src="./images/logo.gif" style="width:70px;height:70px;"></p><br>
	<div class="login-page">
		<div class="form">    
			<form class="login-form" method="POST" action="">
				<input type="text" placeholder="username" name="user"/>
				<input type="password" placeholder="password" name="pass"/>
				<input type="submit" value="Login" style="background: rgb(127, 186, 228)">
			</form>
		</div>
	</div>

	<div id="buttons">
		<a href="applied.php" class="btn blue">Nominees Applied</a>&nbsp;
		<a href="nominees.php" class="btn blue">Nominees Page</a>
	</div>
</body>
</html>

<?php

if(isset($_POST['user']) && isset($_POST['pass'])){
	$user = htmlentities($_POST['user']);
	$pass = md5(htmlentities($_POST['pass']));

	$tbname = "Admins";
	$sql= "SELECT id, password FROM $tbname WHERE username = '$user'";
	$stmt = $conn->query($sql); 
	$row =$stmt->fetch_assoc();		
	$act_pass = $row['password'];	
	$conn->close();

	if($pass == $act_pass){
		$_SESSION['login_user'] = $user;
		$_SESSION['type'] = 'admin';         
		header("location: mod.php");
	}
	else{

		$tbname = "ldap";
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) 
			die("Connection failed: " . $conn->connect_error);

		$sql= "SELECT * FROM $tbname WHERE roll = '$user'";
		$stmt = $conn->query($sql); 
		$row =$stmt->fetch_assoc();		
		$act_pass = $row['password'];
		$name = $row['name'];
		$conn->close();

		if($pass == $act_pass){
			$_SESSION['login_user'] = $user;
			$_SESSION['name'] = $name;
			$_SESSION['type'] = 'student';          
			header("location: welcome.php");
		}
	}
}
?>
