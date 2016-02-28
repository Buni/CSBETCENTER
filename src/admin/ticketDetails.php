<?php

	require_once '../connect.php';

	@session_start();

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

			$submittedbyid = $row['userId'];
			$steamInfo = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=2A19C2EA73F803C304ED6DEE6DEA4408&steamids='.$submittedbyid);
			$steamInfoDecoded = json_decode($steamInfo, true);
			$submittedby = $steamInfoDecoded['response']['players'][0]['personaname'];

			$sql2 = "SELECT id FROM users WHERE steam64id='".$submittedbyid."'";
			$result2 = $conn->query($sql2);

			if ($result2->num_rows > 0) 
			{
				while($row2 = $result2->fetch_assoc()) 
				{
					$submittedbyid2 = $row2['id'];
				}
			}

			echo '<h2>Ticket details:</h2>';
			echo '<h4><b>Submitted by: </b><a href="manage-user.php?id='.$submittedbyid2.'">'.$submittedby.'</a></h4>';
			echo '<h4><b>Subject: </b>'.$row['subject'].'</h4>';
			echo '<h4><b>Date: </b>'.$row['date'].'</h4>';
			echo '<h4><b>Message: </b>'.$row['message'].'</h4>';
			echo '<h4><b>Status: </b>'.$status.'</h4>';
			echo '<form action="tickets.php" METHOD="POST">';
			echo '<textarea name="answer" class="form-control" placeholder="Answer..." style="width:50%;margin-left:25%;"></textarea>';
			echo '<input type="hidden" name="tsteamid" value="'.$_SESSION['steamid'].'" />';
			echo '<input type="submit" class="btn btn-primary" value="Submit" name="ssubmit" />';
			echo '</form>';
		}
	} 
	else
	{
		echo "0 results";
	}

	$conn->close();

?>