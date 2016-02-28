<?php require_once 'templates/header.php'; ?>

<div class="refByContainer">

	<?php
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		$record_count = $conn->query("SELECT userId, betOn, betAmount FROM bets WHERE matchId='".$_GET['id']."'");
		$per_page=50;
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
 
		$query = "SELECT userId, betOn, betAmount FROM bets WHERE matchId='".$_GET['id']."' LIMIT ".$start.", ".$per_page."";
 
		if ($result = $conn->query($query)) 
		{


		$totalusers = mysqli_num_rows($result);

		// get coins amount
		$sql43 = "SELECT betAmount FROM bets WHERE matchId='".$_GET['id']."'";
		$result43 = $conn->query($sql43);
		$totalbetusers = mysqli_num_rows($result43);
		$totalbet = 0;
		if ($result43->num_rows > 0) 
		{
			while($row43 = $result43->fetch_assoc()) 
			{
				@$totalbet += $row43['betAmount'];
			}
		} 

		// get team names
		$sql = "SELECT team1, team2 FROM matches WHERE id='".$_GET['id']."'";
		$result2 = $conn->query($sql);

		if ($result2->num_rows > 0) 
		{
			while($row2 = $result2->fetch_assoc()) 
			{
				$team['team1'] = $row2['team1'];
				$team['team2'] = $row2['team2'];
			}
		}
		else
		{
			echo 'error...';
			exit;
		}

		echo '<h1>Users who bet on <b>'.$team['team1'].'</b> vs <b>'.$team['team2'].'</b></h1>';
		echo '<h3>'.$totalbet.' coins ('.$totalbetusers.' users)</h3>';

		if ($result->num_rows > 0) 
		{
			echo '<table class="table table-bordered">';
			echo '<thead>';
			echo '<tr><td>Steam64 ID</td><td>Pick</td><td>Bet amount</td></tr>';
			echo '<tbody>';
			while($row = $result->fetch_assoc()) 
			{
				// get user id
				$sql = "SELECT id FROM users WHERE steam64id='".$row['userId']."'";
				$result3 = $conn->query($sql);

				if ($result3->num_rows > 0) 
				{
					while($row3 = $result3->fetch_assoc()) 
					{
						$userId = $row3['id'];
					}
				}
				echo '<tr>';
				echo '<td><a href="manage-user.php?id='.$userId.'">'.$row['userId'].'</a></td>';
				echo '<td>'.$team[$row['betOn']].'</td>';
				echo '<td>'.$row['betAmount'].'</td>';
				echo '</tr>';
			}
			echo '</tbody>';
			echo '</table>';
			echo '<a href="manage-match.php?id='.$_GET['id'].'" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Go back</a></div>';
		} 
		if($totalbetusers<=0)
		{
			echo '<div class="col-md-12"><h3>Nobody has placed bet on this match yet.</h3><a href="manage-match.php?id='.$_GET['id'].'" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Go back</a></div>';
		}

	}

	echo '<nav class="text-center">';
  		echo '<ul class="pagination">';

		if($prev > 0)
		{
			echo '<li><a href="?page='.$prev.'&id='.$_GET['id'].'" aria-label="Previous"><span aria-hidden="true">&laquo;</span></a></li>';
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
				echo '<li><a href="?page='.$number.'&id='.$_GET['id'].'">'.$number.'</a></li>';
			}
		}
		if($page < $pages)
		{
			echo '<li><a href="?page='.$next.'&id='.$_GET['id'].'" aria-label="Next"><span aria-hidden="true">&raquo;</span></a></li>';
		}

		echo '</ul>';
		echo '</nav>';


		$conn->close();

	?>

</div>

<?php require_once 'templates/footer.php'; ?>