<?php require_once 'templates/header.php'; ?>

<div class="refByContainer">

	<?php
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT steam64id, id, banned, admin FROM users WHERE id='".$_GET['id']."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{
			while($row = $result->fetch_assoc()) 
			{
				$steamInfo1 = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=2A19C2EA73F803C304ED6DEE6DEA4408&steamids='.$row['steam64id']);
				$steamInfoDecoded1 = json_decode($steamInfo1, true);

				$steam64id = $row['steam64id'];

				$sql2 = "SELECT * FROM users WHERE referredBy='".$steam64id."'";
				$result2 = $conn->query($sql2);
				if ($result2->num_rows > 0) 
				{
					$totalReferredUsers = mysqli_num_rows($result2);
					echo '<h1>Users referred by <a href="manage-user.php?id='.$_GET['id'].'">'.$steamInfoDecoded1['response']['players'][0]['personaname'].'</a> ('.$totalReferredUsers.')</h1>';

					while($row2 = $result2->fetch_assoc()) 
					{
						if($row2['admin']!=='1')
						{
							$rank = '<span>User</span>';
						}
						elseif($row2['admin']=='1')
						{
									$rank = '<span style="color:green">Admin</span>';
						}
						if($row2['banned']=='1')
						{
							$rank = '<span style="color:red">Banned</span>';
						}

						$steamInfo = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=2A19C2EA73F803C304ED6DEE6DEA4408&steamids='.$row2['steam64id']);
						$steamInfoDecoded = json_decode($steamInfo, true);

						$sql3 = "SELECT id FROM users WHERE referredBy='".$row2['steam64id']."'";
						$result3 = $conn->query($sql3);

						$referredBySingle = mysqli_num_rows($result3);
						
						$test = 1;
						echo 
						'
							<div class="text-center ib">
								<a href="manage-user.php?id='.$row2['id'].'"><img src="'.$steamInfoDecoded['response']['players'][0]['avatarfull'].'" class="about-avatar" /></a>
								<h1>'.$steamInfoDecoded['response']['players'][0]['personaname'].'</h1>
								<h4><strong>Balance: </strong>'.$row2['balance'].' coins</h4>
								<h4><strong>Steam ID: </strong>'.$row2['steam64id'].'</h4>
								<h4><strong>Registration date: </strong>'.$row2['date'].'</h4>
								<h4><strong>Rank: </strong>'.$rank.'</h4>
								<h4><strong>Total referred users: </strong><a href="referredBy.php?id='.$row2['id'].'">'.$referredBySingle.'</a></h4>
								<h4><strong>Total bet: </strong>'.$row2['totalBet'].' coins</h4>
							</div>
						';
					}
					echo '<br><a href="users.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Go back to user list</a>';
				}

			}
		} 

		if(@$referredBySingle<=0 && @$test!==1)
		{
			echo '<h1>Users referred by <a href="manage-user.php?id='.$_GET['id'].'">'.$steamInfoDecoded1['response']['players'][0]['personaname'].'</a> (0)</h1>';
			echo '<br><a href="users.php?page='.$_SESSION['page'].'" class="btn btn-default"><i class="fa fa-arrow-left"></i> Go back to user list</a>';
		}

		elseif ($result->num_rows <= 0) 
		{
			echo 'Nothing here.<a href="users.php?page='.$_SESSION['page'].'" class="btn btn-default"><i class="fa fa-arrow-left"></i> Go back to user list</a>';
			exit;
		}

		$conn->close();

	?>

</div>

<?php require_once 'templates/footer.php'; ?>