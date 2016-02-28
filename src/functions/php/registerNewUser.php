<?php

	function registerNewUser($steam64id)
	{
		require 'connect.php';

		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT steam64id FROM users WHERE steam64id='".$steam64id."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{
			#header('location: matches.php');
		} 
		else 
		{
			$date = date('d.m.Y').', '.date('G:i');
			$ip = $_SERVER['REMOTE_ADDR'];
			$sql = "INSERT INTO users (steam64id, balance, ip, date, totalBet, commision, banned, hasDeposit, available) VALUES ('".$steam64id."', '0', '".$ip."', '".$date."', '0', '0', '0', '0', '0')";

			if ($conn->query($sql) === TRUE) 
			{
				#echo 'added user with steam id '.$steam64id;
			} else 
			{
				#echo "Error: " . $sql . "<br>" . $conn->error;
			}
		}

		#header('location: matches.php');

		$conn->close();
	}

?>