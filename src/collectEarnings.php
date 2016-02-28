<?php
	require_once 'connect.php';

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT available, balance FROM users WHERE steam64id='".$_GET['steamid']."'";
	$result = $conn->query($sql);

	if (@$result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
			$availableNow = $row['available'];
			$balance = $row['balance'];
		}
	}

	if($availableNow <= 0)
	{
		echo 
		'
			<div class="alert alert-danger">
				<i class="fa fa-exclamation-triangle"></i> 
				<a href="#" class="close" onclick="collectEarningsFadeOut();">&times;</a>
				You need to have atleast 1 available coin.
			</div>
		';
	}
	elseif($availableNow >= 0)
	{
		$balanceNew = $balance + $availableNow;
		$sql2 = "UPDATE users SET balance='".$balanceNew."', available='0' WHERE steam64id='".$_GET['steamid']."'";

		if ($conn->query($sql2) === TRUE) 
		{
			echo 
			'
				<div class="alert alert-success">
					<i class="fa fa-check"></i> 
					<a href="#" class="close" onclick="collectEarningsFadeOut();">&times;</a>
					'.$availableNow.' coins has been added to your account. Your current balance is '.$balanceNew.'.
				</div>
			';
			#$availableNow = 0;
		} 
		else 
		{
			echo 
			"
				<div class='alert alert-danger'>
					<i class='fa fa-exclamation-triangle'></i> 
					<a href='#' class='close' onclick='collectEarningsFadeOut();'>&times;</a>
					Couldn't connect to database. Try refreshing site or contact administrator.
				</div>
			";
		}
	}

	$conn->close();
?>