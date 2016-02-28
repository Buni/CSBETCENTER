<?php require_once 'templates/header.php'; ?>

	<?php

		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		 
		$record_count = $conn->query("SELECT * FROM users");
		$per_page=6;
		$pages = ceil($record_count->num_rows / $per_page);
		if(!isset($_GET['page']))
		{
			$page = 1;
		} 
		else
		{
			$page = $_GET['page'];
		}
		if($page <= 0) 
		{
			$start = 1;
		} 
		else 
		{
			$start = $page * $per_page - $per_page;
		}
			$prev = $page -1;
			$next = $page +1;
 
		$query = "SELECT * FROM users ORDER BY id ASC LIMIT $start, $per_page";
 
		if ($result = $conn->query($query)) 
		{
			echo '<div class="refByContainer">';
			while ($row = $result->fetch_assoc()) 
			{
				$info = $row;

				if($info['admin']!=='1')
				{
					$rank = '<span>User</span>';
				}
				elseif($info['admin']=='1')
				{
					$rank = '<span style="color:green">Admin</span>';
				}
				if($info['banned']=='1')
				{
					$rank = '<span style="color:red">Banned</span>';
				}

				$steamInfo = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=2A19C2EA73F803C304ED6DEE6DEA4408&steamids='.$row['steam64id']);
				$steamInfoDecoded = json_decode($steamInfo, true);

				$sql4 = "SELECT id, steam64id FROM users WHERE steam64id='".$row['referredBy']."'";
				$result4 = $conn->query($sql4);
				if ($result4->num_rows > 0) 
				{
					while ($row4 = $result4->fetch_assoc()) 
					{
						$referredById = $row4['id'];
						$referredBySteamId = $row4['steam64id'];

						$steamInfo2 = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=2A19C2EA73F803C304ED6DEE6DEA4408&steamids='.$referredBySteamId);
						$steamInfoDecoded2 = json_decode($steamInfo2, true);

						$referredByName = $steamInfoDecoded2['response']['players'][0]['personaname'];
					}
				}

				$sql3 = "SELECT id, steam64id FROM users WHERE referredBy='".$row['steam64id']."'";
				$result3 = $conn->query($sql3);

				$referredBySingle = mysqli_num_rows($result3);

				echo 
				'
					<div class="text-center ib">
						<a href="manage-user.php?id='.$row['id'].'"><img src="'.$steamInfoDecoded['response']['players'][0]['avatarfull'].'" class="about-avatar" /></a>
						<h1>'.$steamInfoDecoded['response']['players'][0]['personaname'].'</h1>
						<h4><strong>Balance: </strong>'.$row['balance'].' coins</h4>
						<h4><strong>Steam ID: </strong>'.$row['steam64id'].'</h4>
						<h4><strong>Registration date: </strong>'.$row['date'].'</h4>
						<h4><strong>Rank: </strong>'.$rank.'</h4>
						<h4><strong>Referred by: </strong><a href="manage-user.php?id='.$referredById.'">'.$referredByName.'</a></h4>
						<h4><strong>Total referred users: </strong><a href="referredBy.php?id='.$row['id'].'">'.$referredBySingle.'</a></h4>
						<h4><strong>Total bet: </strong>'.$row['totalBet'].' coins</h4>
					</div>
				';
				$referredByName = '';
			}
			echo '</div>';
		}

		echo '<nav class="text-center">';
  		echo '<ul class="pagination">';

		if($prev > 0)
		{
			echo '<li><a href="?page='.$prev.'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
		}
 
		$number = 1;
		for($number; $number <= $pages; $number+=1) 
		{
			if($page==$number)
			{
				echo '<li class="active"><a href="#">'.$number.' <span class="sr-only">(current)</span></a></li>';
			}
			else 
			{
				echo '<li><a href="?page='.$number.'">'.$number.'</a></li>';
			}
		}
		if($page < $pages)
		{
			echo '<li><a href="?page='.$next.'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
		}

		echo '</ul>';
		echo '</nav>';

		$_SESSION['page'] = $page;

		$conn->close();

	?>

	<p class="text-center"><a href="index.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Go back</a></p>


<?php require_once 'templates/footer.php'; ?>