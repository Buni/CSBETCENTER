<?php

	require_once 'connect.php';

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT * FROM tickets WHERE id='".$_GET['id']."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
			if($row['status']=='open'){$status='<span style="color:green">OPEN</span>';}
			if($row['status']=='closed'){$status='<span style="color:red">CLOSED</span>';}

			$steamInfo = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=2A19C2EA73F803C304ED6DEE6DEA4408&steamids='.$row['reviewedBy']);
			$steamInfoDecoded = json_decode($steamInfo, true);
			$reviewedby = @$steamInfoDecoded['response']['players'][0]['personaname'];

			echo '<h2>Ticket details #'.$row['id'].'</h2>';
			echo '<h4><b>Subject: </b>'.$row['subject'].'</h4>';
			echo '<h4><b>Date: </b>'.$row['date'].'</h4>';
			echo '<h4><b>Message: </b>'.$row['message'].'</h4>';
			echo '<h4><b>Status: </b>'.$status.'</h4>';
			echo '<h4><b>Reviewed by: </b>'.$reviewedby.'</h4>';
			echo '<h4><b>Answer: </b><br>'.$row['answer'].'</h4>';	
		}
	} 
	else
	{
		echo "0 results";
	}

	$conn->close();

?>