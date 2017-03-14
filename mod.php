<?php
	session_start();

	if(!isset($_SESSION["type"]) || $_SESSION["type"] != 'admin')
		header("location: index.php");

?>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Moderator Page</title>
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
	<link href="css/mod.css" rel="stylesheet" type="text/css">
</head>

<body>
	<div class="table-title">
	<p style="text-align: right">
	<a href="logout.php" >logout </a>
	</p>
	<h3>Gymkhana Elections - Moderator</h3>
	</div>
	<table class="table-fill">
		<thead>
			<tr>
				<th class="text-centre">ID</th>
				<th class="text-left">Name</th>
				<th class="text-left">Roll.no.</th>
				<th class="text-left">Timestamp</th>
				<th class="text-left">Status</th>
			</tr>
		</thead>
		<tbody class="table-hover">
			<tr>
					<td>0</td>
					<td>Janee Doe</td>
					<td>LMN001</td>
					<td> - </td>
					<td><button>Activate</button></td>
			</tr>
			<?php
			
			include('connection.php');
			
			$sql = "SELECT id, name, status, roll, time FROM Nominees";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {
					echo '<tr>
						<td class="text-center">'  .$row["id"].'</td>
						<td class="text-left">'  .$row["name"].'</td>
						<td class="text-left">'  .$row["roll"].'</td>
						<td class="text-left">'  .$row["time"].'</td>
						<td class="text-left">
							<a href="?roll='  .$row["roll"].'"><button type="button">';
								if($row["status"] == "Inactive") echo '<b>Activate</b>'; 
								else if($row["status"] == "Active") echo "<b>Deactivate</b>";
								else echo "Withdrawn";
							
						echo '</button></a>
						</td>
					</tr>';
			    }
			} else {
			    echo "0 results";
			}
			?>

		</tbody>
	</table>
  

  </body>


<?php
if(isset($_REQUEST["roll"]))
{
	$roll = $_REQUEST["roll"];

	$sql = "SELECT * FROM Nominees WHERE roll='$roll' AND status<>'Withdrawn'";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {
					if($row["status"] == "Inactive"){ 
						$sql = "UPDATE Nominees SET status='Active' WHERE roll='$roll' AND status<>'Withdrawn'"; 
						$stmt = $conn->prepare($sql);
		    				$stmt->execute();
					}
					else if($row["status"] == "Active"){ 
						$sql = "UPDATE Nominees SET status='Inactive' WHERE roll='$roll' AND status<>'Withdrawn'"; 
						$stmt = $conn->prepare($sql);
		    				$stmt->execute();
					}
			    }
			}
	$conn->close();

	header("location: mod.php");
}
?>
