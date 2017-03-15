<?php

session_start();
if(!isset($_SESSION["login_user"]))
	header("location: index.php");
else if(isset($_SESSION["type"]) && $_SESSION['type'] == 'admin')
	header("location: mod.php");
else{

	include('connection.php');

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	}

	$roll = $_SESSION["login_user"];
	$sql = "SELECT id, name, roll FROM Nominees WHERE roll='$roll' AND status<>'Withdrawn'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		echo 'redirecting';
		header("location: withdraw.php");       
	}
}

?>

<!DOCTYPE html>
<html lang="en">

<style>
	.box1 {
		background-color: lightgrey;
		width: 400px;
		height: 270px;
		padding: 25px;
		margin: 25px;
	}

	.box2 {
		background-color: lightgrey #000000;
		width: 400px;
		height: 300px;
		padding-top: 5px;
		padding-left: 25px;
		margin: 25px;
	}
</style>


<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0"/>
	<title>Election Portal</title>

	<!-- CSS  -->
	<link href="css/materialize.css" type="text/css" rel="stylesheet" media="screen,projection"/>
</head>

<body style="background-image: url(./images/filler.jpg)">
	<h5 style="text-align: right">
		[<a href="logout.php" ><b>logout</b></a>]
	</h5>

	<div class="section no-pad-bot" id="index-banner">
		<div class="container">
			<h3 class="">Indian Institute of Information Technology, Allahabad</h3>
			<div class="row center">
				<h4 class="header col s12">Gymkhana-Election portal</h4>
			</div>
			<div class="row center">
				<img src="./images/gymkhana.png" alt="IIITA logo">
			</div>
		</div>
	</div>

	<div class="container" >
		<hr>
		<div class="section">
			<div class="row">
				<div class="col s7 center">
					<div class="box1 centre">
						<h3><i class="mdi-content-send brown-text"></i></h3>
						<h5>Name: <?php echo $_SESSION["name"];?></h5>
						<h5>R.No.: <?php echo $_SESSION["login_user"];?></h5>
						<p>(Upload your photo in the space provided and then submit)</p>
						<br>
						<div id="buttons">
							<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method=post enctype="multipart/form-data" >
								<input type=submit value="Submit" class="btn blue">
							</div>
						</div>
					</div>
					<div class="col s5 centre">
						<div class="box2" >
							<script src = "js/SimpleImage.js"></script>
							<h1 id="t2"></h1>
							<h5>Upload photo:</h5>
							<canvas id = "can" >
							</canvas>
							<p> 

								<input type=hidden name="MAX_FILE_SIZE" value="1000000" />
								<input type="file" multiple="false" accept="image/*" id="finput" name="userfile"> 
								<input type=button value="Upload" onclick="loadForegroundImage()";><br>
							</form>

						</p>
					</div>
					<?php

					// Checking the file was submitted
					if(!isset($_FILES['userfile'])) { echo ''; }

					else{ 
						try {
							$msg = upload(); // function calling to upload an image
							echo $msg;
						}
						catch(Exception $e) {
							echo $e->getMessage();
							echo 'Sorry, Could not upload file';
						}
					}


			function upload() {
					//include "database/dbco.php";
					$maxsize = 10000000; //set to approx 10 MB

					//check associated error code
					if($_FILES['userfile']['error']==UPLOAD_ERR_OK) {

					//check whether file is uploaded with HTTP POST
						if(is_uploaded_file($_FILES['userfile']['tmp_name'])) {

					//checks size of uploaded image on server side
							if( $_FILES['userfile']['size'] < $maxsize) {

					//checks whether uploaded file is of image type
								if($_FILES['userfile']['type']=="image/gif" || $_FILES['userfile']['type']== "image/png" || $_FILES['userfile']['type']== "image/jpeg" || $_FILES['userfile']['type']== "image/JPEG" || $_FILES['userfile']['type']== "image/PNG" || $_FILES['userfile']['type']== "image/GIF") {

					// prepare the image for insertion
									$imgData =addslashes (file_get_contents($_FILES['userfile']['tmp_name']));

					// put the image in the db...
					// database connection

									$tbname = "Nominees";
									$date = date('Y-m-d H:i:s',time());
									$insert = 1;

									include('connection.php');
					/*
					    $roll = $_SESSION["login_user"];
					    $sql = "SELECT * FROM Nominees WHERE roll='$roll'";
					    $result = $conn->query($sql);
					    if ($result->num_rows > 0 || $_SESSION["type"] != "student") {
							$insert = 0;
					        while($row = $result->fetch_assoc()) {
								if($row["status"] == "Withdrawn" ){ 
									$sql = "UPDATE Nominees SET status='Inactive' WHERE roll='$roll'"; 
									$stmt = $conn->prepare($sql);
									    $stmt->execute();
									$sql = "UPDATE Nominees SET image='{$imgData}' WHERE roll='$roll'"; 
									$stmt = $conn->prepare($sql);
									    $stmt->execute();
									$sql = "UPDATE Nominees SET time='{$date}' WHERE roll='$roll'"; 
									$stmt = $conn->prepare($sql);
									    $stmt->execute();
									$insert = 0;
									echo 'updated!';
								}
					      		else {    
					                          ;//header("location: withdraw.php");
					        		exit(); 
					      		}
					    	}    
					    }
					*/

					    if($insert ==1){
					    	$sql = "INSERT INTO $tbname
					    	(name,roll,image, iname,size,time)
					    	VALUES
					    	('{$_SESSION['name']}','{$_SESSION['login_user']}','{$imgData}', '{$_FILES['userfile']['name']}','{$_FILES['userfile']['size']}','{$date}');";
						    // use exec() because no results are returned
					    	$conn->query($sql);


						// insert the image

					    	$msg='<p><b>Nomination successfully filed. You can logout now!</b></p>';
					    	echo '<script>alert("Nomination successfully filed! Approval by Election Commission is still awaited after approval your name will be on the nominees page.Thanks for participating in elections Best Wishes. You can logout now.")</script>;';
					    	header("location:success.php");
					    }
					}
					else
						$msg="<p>Uploaded file is not an image.</p>";
				}
				else {
					// if the file is not less than the maximum allowed, print an error
					$msg='<div>File exceeds the Maximum File limit</div>
					<div>Maximum File limit is '.$maxsize.' bytes</div>
					<div>File '.$_FILES['userfile']['name'].' is '.$_FILES['userfile']['size'].
						' bytes</div><hr />';
					}
				}
				else
					$msg="File not uploaded successfully.";

			}
			else {
				$msg= file_upload_error_message($_FILES['userfile']['error']);
			}
			return $msg;
		}

					// Function to return error message based on error code

		function file_upload_error_message($error_code) {
			switch ($error_code) {
				case UPLOAD_ERR_INI_SIZE:
				return 'The uploaded file exceeds Max size 1 Mb';
				case UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
				case UPLOAD_ERR_PARTIAL:
				return 'The uploaded file was only partially uploaded';
				case UPLOAD_ERR_NO_FILE:
				return 'No file was uploaded';
				case UPLOAD_ERR_NO_TMP_DIR:
				return 'Missing a temporary folder';
				case UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write file to disk';
				case UPLOAD_ERR_EXTENSION:
				return 'File upload stopped by extension';
				default:
				return 'Unknown upload error';
			}
		}
		?>

	</div>
</div>

</div>
</div>

<!--  Scripts-->
<script src="js/jquery-2.1.1.min.js"></script>
<script src="js/materialize.js"></script>
<script src="js/init.js"></script>

</body>
</html>