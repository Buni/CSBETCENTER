<?php
	require_once 'connect.php';

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	$matchId = $_POST['matchId'];
	$steamId = $_POST['steamid'];

	// check if match is already started
	$dateNow = date('d.m.Y, G:i');

	$sql = "SELECT date FROM matches WHERE id='".$matchId."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
			$dateMatch = $row['date'];
		}
	} 
	
	if($dateNow > $dateMatch)
	{
		echo 'match is already started';
		exit;
	}
	elseif($dateNow < $dateMatch)
	{
		// select actual data
		$sql3 = "SELECT betOn FROM bets WHERE matchId='".$matchId."' AND userId='".$steamId."'";
		$result3 = $conn->query($sql3);

		if ($result3->num_rows > 0) 
		{
			while($row3 = $result3->fetch_assoc()) 
			{
				$type = $row3['betOn'];
				if($type=='team1')
				{
					$typeNew = 'team2';
				}
				if($type=='team2')
				{
					$typeNew = 'team1';
				}
			}
		} 

		$sql2 = "UPDATE bets SET betOn='".$typeNew."' WHERE userId='".$steamId."' AND matchId='".$matchId."'";

		if ($conn->query($sql2) === TRUE) 
		{
			#echo 'changed from '.$type.' to '.$typeNew.' in match '.$matchId.' for user '.$steamId;
		} 

	}

	$conn->close();
?>