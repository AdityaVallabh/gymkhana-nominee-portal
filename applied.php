<html>
<head>
	<meta charset="utf-8" />
	<title>Nominees Applied</title>
	<link href="css/nominees.css" rel="stylesheet" type="text/css">
	<meta name="viewport" content="initial-scale=1.0; maximum-scale=1.0; width=device-width;">
</head>
<body>
	<div class="table-title">
		<h3>Filed Nominations</h3>
	</div>
	<table class="table-fill">
		<thead>
			<tr>				
				<th class="text-left">Name</th>
				<th class="text-left">Roll No.</th>
			</tr>
		</thead>
		<tbody class="table-hover">
			<tr><td class="text-left">0) John Doe</td><td class="text-left">XYZ001</td></tr>
			<tr><td class="text-left">0) John Doe</td><td class="text-left">XYZ001</td></tr>
			<tr><td class="text-left">0) John Doe</td><td class="text-left">XYZ001</td></tr>
			
			<?php
			
			include('connection.php');

			$sql = "SELECT id, name, roll FROM Nominees where status='Inactive'";
			$result = $conn->query($sql);
			$x=1;
			if ($result->num_rows > 0) {
			    // output data of each row
			    while($row = $result->fetch_assoc()) {
				echo '<tr><td class="text-left">'. $x++. ') &nbsp'  .$row["name"].'</td><td class="text-left" style="text-transform:uppercase;">' .$row["roll"]. '</td></tr>' ;
			    }
			}
			$conn->close();
			
			?>
			
		</tbody>
	</table>

	<div id="buttons">
	  <a href="index.php" class="btn blue">Login Page</a>
	</div>
  
</body>

</html>
