<?php
	require_once 'connect.php';

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	if(empty($_POST['code']))
	{
		echo '<span style="color:red">Please type the promo code to receive coins.</span>';
		$dontDisplay = 1;
	}

	if(isset($_POST['code']))
	{
		$sql = "SELECT balance, hasRedeemedCode, code FROM users WHERE steam64id='".$_POST['steamid']."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{
			while($row = $result->fetch_assoc()) 
			{
				if(strtoupper($_POST['code'])!==strtoupper($row['code']))
				{
					if($row['hasRedeemedCode']=='0')
					{
						$sql3 = "SELECT code, steam64id FROM users WHERE code='".strtoupper($_POST['code'])."'";
						$result3 = $conn->query($sql3);
						if ($result3->num_rows > 0) 
						{
							while($row3 = $result3->fetch_assoc()) 
							{
								$referredBy = $row3['steam64id'];
								$newbalance = $row['balance'] + 500;
								$sql2 = "UPDATE `users` SET `balance`=".$newbalance.",`hasRedeemedCode`=1,`referredBy`=".$referredBy." WHERE steam64id='".$_POST['steamid']."'";

								if ($conn->query($sql2) === TRUE) 
								{
									echo '<span style="color:green">You have succesfully redeemed promo code and received 500 coins!</span>';
								} 
								else 
								{
									echo '<span style="color:red">Database error (1). Try refreshing site.</span>';
									//$conn->error
								}
							}
						}
						elseif ($result3->num_rows <= 0) 
						{
							if(@$dontDisplay!==1)
							{
								echo '<span style="color:red">Invalid code. Please try another one.</span>';
							}
						}

					}
					elseif($row['hasRedeemedCode']==1)
					{
						if(@$dontDisplay!==1)
						{
							echo '<span style="color:red">You have already redeemed promo code!</span>';
						}
					}
				}
				elseif(strtoupper($_POST['code'])==strtoupper($row['code']))
				{
					if(@$dontDisplay!==1)
					{
						echo '<span style="color:red">You cant redeem your own code!</span>';
					}
				}
			}
		} 
		elseif ($result->num_rows <= 0) 
		{
			echo '<span style="color:red">Database error (2). Try refreshing site.</span>';
		}
	}

	$conn->close();
?>