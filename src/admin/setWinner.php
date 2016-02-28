<?php

	$id = $_POST['matchId'];
	$winner = $_POST['winner'];

	require_once '../connect.php';

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	if($winner=='none')
	{
		$sql = "UPDATE matches SET winner='".$winner."' WHERE id='".$id."'";
	}
	else
	{
		$sql = "UPDATE matches SET winner='".$winner."' WHERE id='".$id."'";
	}

	if ($conn->query($sql) === TRUE && $winner=='team1' || $winner=='team2') 
	{
		// bet data
		$sql4 = "SELECT * FROM bets WHERE matchId='".$id."' AND betOn='team1'";
		$result4 = $conn->query($sql4);

		if ($result4->num_rows > 0) 
		{
			while($row4 = $result4->fetch_assoc()) 
			{
				$betOnTeam1users = mysqli_num_rows($result4);
			}
		}
		elseif ($result4->num_rows <= 0)
		{
			$betOnTeam1users = 0;
		}

		// bets data, team 2
		$sql5 = "SELECT * FROM bets WHERE matchId='".$id."' AND betOn='team2'";
		$result5 = $conn->query($sql5);

		if ($result5->num_rows > 0) 
		{
			while($row5 = $result5->fetch_assoc()) 
			{
				$betOnTeam2users = mysqli_num_rows($result5);
			}
		}		
		elseif ($result5->num_rows <= 0)
		{
			$betOnTeam2users = 0;
		}

		if($betOnTeam1users==0)
		{
			$team1Percent = '0';
			$team2Percent = '100';
		}
		if($betOnTeam2users==0)
		{
			$team2Percent = '0';
			$team1Percent = '100';
		}
		if($betOnTeam1users==0 && $betOnTeam2users==0)
		{
			$team1Percent = '0';
			$team2Percent = '0';
		}

		if($betOnTeam1users >= 1 && $betOnTeam2users >= 1)
		{
			$betTotal = $betOnTeam1users + $betOnTeam2users;
			$team1Percent = floor($betOnTeam1users / $betTotal * 100);
			$team2Percent = ceil($betOnTeam2users / $betTotal * 100);
		}

		// calculating values
		$value1 = '0.'.$team2Percent;
		$value2 = '0.'.$team1Percent;

		$team1Value = 0;
		$team2Value = 0;

		if($team1Percent > $team2Percent)
		{
			$team1Value = 1.5;
			$team2Value = 3;
		}
		elseif($team1Percent < $team2Percent)
		{
			$team1Value = 3;
			$team2Value = 1.5;
		}

		if($team1Percent==50)
		{
			$team1Value = 2;
			$team2Value = 2;
		}

		if($team2Percent > $team1Percent)
		{
			$team1Value = 3;
			$team2Value = 1.5;
		}
		elseif($team2Percent < $team1Percent)
		{
			$team1Value = 1.5;
			$team2Value = 3;
		}

		if($winner=='team1')
		{
			$valueFor1 = round(1 * $value1 * $team1Value, 2);
		}
		elseif($winner=='team2')
		{
			$valueFor1 = round(1 * $value2 * $team2Value, 2);
		}

		$sql = "SELECT userId, betAmount FROM bets WHERE matchId='".$id."' AND betOn='".$winner."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{
			while($row = $result->fetch_assoc()) 
			{
				$sql2 = "SELECT balance FROM users WHERE steam64id='".$row['userId']."'";
				#echo $sql2;
				$result2 = $conn->query($sql2);

				while($row2 = $result2->fetch_assoc()) 
				{
					$userBalance = $row2['balance'];
				}

				$winning = $row['betAmount'] + $row['betAmount'] * $valueFor1;
				$totalNewBalance = $userBalance + $winning;
				$userId = $row['userId'];

				$sql = "UPDATE users SET balance='".$totalNewBalance."' WHERE steam64id='".$row['userId']."'";

				if ($conn->query($sql) === TRUE) 
				{
					#echo 'All users have been refunded.';
				} 
				else 
				{
					echo "Error while refunding users: " . $conn->error;
				}
			}
		} 

	} 

	elseif($winner=='none')
	{
		$sql = "SELECT userId, betAmount FROM bets WHERE matchId='".$id."' AND betOn='".$winner."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{
			while($row = $result->fetch_assoc()) 
			{
				$sql2 = "SELECT balance FROM users WHERE steam64id='".$row['userId']."'";
				#echo $sql2;
				$result2 = $conn->query($sql2);

				while($row2 = $result2->fetch_assoc()) 
				{
					$userBalance = $row2['balance'];
				}

				$winning = $row['betAmount'];
				$totalNewBalance = $userBalance + $winning;
				$userId = $row['userId'];

				$sql = "UPDATE users SET balance='".$totalNewBalance."' WHERE steam64id='".$row['userId']."'";

				if ($conn->query($sql) === TRUE) 
				{
					#echo 'All users have been refunded.';
				} 
				else 
				{
					echo "Error while refunding users: " . $conn->error;
				}
			}
		} 
	}

	$conn->close();

?>