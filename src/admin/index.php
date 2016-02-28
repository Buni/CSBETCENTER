<?php require_once 'templates/header.php'; ?>
<?php
	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	$sql2 = "SELECT id FROM tickets WHERE status='open'";
	$result2 = $conn->query($sql2);
	$tickets = mysqli_num_rows($result2);

	$conn->close();

?>

	<div class="a-parent">
		<div class="col-md-4 text-center a-select">
			<i class="fa fa-users fa-4x"></i>
			<h1>Users</h1>
			<small>Managing users - ban, set balance, block etc.</small>
			<a href="users.php" class="btn btn-default">See more</a>
		</div>
		<div class="col-md-4 text-center a-select">
			<i class="fa fa-gamepad fa-4x"></i>
			<h1>Matches</h1>
			<small>Managing matches - add, end, edit etc.</small>
			<a href="matches.php" class="btn btn-default">See more</a>
		</div>
		<div class="col-md-4 text-center a-select">
			<i class="fa fa-bar-chart fa-4x"></i>
			<h1>Tickets (<span style="color:green"><?php echo $tickets; ?></span>)</h1>
			<small>Users tickets</small>
			<a href="tickets.php" class="btn btn-default">See more</a>
		</div>
	</div>

<?php require_once 'templates/footer.php'; ?>