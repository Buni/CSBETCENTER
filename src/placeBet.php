<?php
	require_once 'connect.php';

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	$steamId = $_POST['steamid'];
	$matchId = $_POST['matchid'];
	$betOn = $_POST['betOn2'];
	$betOnName = $_POST['betOn'];
	$amount = $_POST['amount'];

	if(isset($steamId) && isset($matchId) && isset($betOn) && isset($amount))
	{
		$sql3 = "SELECT balance, referredBy, totalBet, commision FROM users WHERE steam64id='".$steamId."'";
		$result3 = $conn->query($sql3);

		if ($result3->num_rows > 0) 
		{
			while($row3 = $result3->fetch_assoc()) 
			{
				$balance = $row3['balance'];
				$newBalance = $balance - $amount;
				$referredBy = $row3['referredBy'];
				$totalBet = $row3['totalBet'];
				$totalBetNew = $totalBet + $amount;
				$commision = $row3['commision'];
				$commisionTest = floor($amount * 0.03);
				$commisionNew = $commision + $commisionTest;

				$sql6 = "SELECT available FROM users WHERE steam64id='".$referredBy."'";
				$result6 = $conn->query($sql6);
				if ($result6->num_rows > 0) 
				{
					while($row6 = $result6->fetch_assoc()) 
					{
						$referredByBalance = $row6['available'];
					}
				}
			}
		}

		$sql = "SELECT id FROM bets WHERE matchId='".$matchId."' AND userId='".$steamId."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{
			echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <a href="#" class="close" onclick="placeBetFadeOut();">&times;</a>You have already placed a bet on this match. Check <a href="bets.php" class="alert-link">your bets</a>.</div>';
		} 
		else 
		{
			if($balance<=0)
			{
				echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <a href="#" class="close" onclick="placeBetFadeOut();">&times;</a>You dont have enough coins. You can <a href="deposit.php" class="alert-link">deposit some skins</a> to get coins.</div>';
				exit;
			}
			else
			{
				$sql2 = 'INSERT INTO bets (matchId, betAmount, betOn, userId) VALUES ("'.$matchId.'", "'.$amount.'", "'.$betOn.'", "'.$steamId.'")';

				if ($conn->query($sql2) === TRUE) 
				{
					$sql5 = "UPDATE users SET balance='".$newBalance."', totalBet='".$totalBetNew."', commision='".$commisionNew."' WHERE steam64id='".$steamId."'";
					$conn->query($sql5);
					$amountRef = $amount * 0.03;
					$referredByBalanceNew = floor($referredByBalance + $amountRef);
					$sql7 = "UPDATE users SET available='".$referredByBalanceNew."' WHERE steam64id='".$referredBy."'";
					$conn->query($sql7);
					echo '<div class="alert alert-success"><i class="fa fa-check"></i> <a href="#" class="close" onclick="placeBetFadeOut();">&times;</a>You have successfully placed '.$amount.' coins on team '.$betOnName.'! Go to <a href="bets.php" class="alert-link">your bets</a> to manage it.</div>';
				} 
				else 
				{
					echo '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <a href="#" class="close" onclick="placeBetFadeOut();">&times;</a>Database error. Try refreshing site or contact administrator.</div>';
					echo $conn->error.' / '.$sql2;
				}
			}
		}
	}
	else
	{
		echo 'error...';
	}

	$conn->close();
?>