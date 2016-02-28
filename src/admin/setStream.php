<?php require_once 'templates/header.php'; ?>

	<?php

		$id = $_POST['id'];
		$stream = $_POST['stream'];


		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "UPDATE matches SET stream='".$stream."' WHERE id='".$id."'";

		if ($conn->query($sql) === TRUE) 
		{
			header('location: manage-match.php?id='.$id);
		} 
		else 
		{
			echo "Error: " . $sql . "<br>" . $conn->error;
		}

		$conn->close();

	?>


<?php require_once 'templates/footer.php'; ?>