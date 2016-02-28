<?php require_once 'templates/header.php'; ?>

	<?php

		$id = $_GET['id'];
		$action = $_GET['action'];

		if($action=='ban')
		{
			$query = '1';
		}
		elseif($action=='unban')
		{
			$query = '0';
		}

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "UPDATE users SET banned='".$query."' WHERE id='".$id."'";

		if ($conn->query($sql) === TRUE) 
		{
			header('location: manage-user.php?id='.$id);
		} 
		else 
		{
			echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();

	?>


<?php require_once 'templates/footer.php'; ?>