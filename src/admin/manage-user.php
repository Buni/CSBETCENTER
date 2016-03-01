<?php require_once 'templates/header.php'; ?>

	<?php
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT * FROM users WHERE id='".$_GET['id']."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{
			while($row = $result->fetch_assoc()) 
			{
				$info = $row;
				$steamInfo = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=(PUT STEAM API KEY HERE)&steamids='.$info['steam64id']);
				$steamInfoDecoded = json_decode($steamInfo, true);

				$referredById = '';
				$sql2 = "SELECT id FROM users WHERE steam64id='".$info['referredBy']."'";
				$result2 = $conn->query($sql2);
				if ($result2->num_rows > 0) 
				{
					while($row2 = $result2->fetch_assoc()) 
					{
						$referredById = $row2['id'];
					}
				}
				
				$totalReferredUsers = '0';
				$sql3 = "SELECT id FROM users WHERE referredBy='".$info['steam64id']."'";
				$result3 = $conn->query($sql3);
				if ($result3->num_rows > 0) 
				{
					while($row3 = $result3->fetch_assoc()) 
					{
						$totalReferredUsers = mysqli_num_rows($result3);
					}
				}

				$steamInfoReferred = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=(PUT STEAM API KEY HERE)&steamids='.$info['referredBy']);
				$steamInfoDecodedReferred = json_decode($steamInfoReferred, true);

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
			}
		} 
		else 
		{
			echo 'User not found.<a href="users.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Go back to user list</a>';
			exit;
		}

		$conn->close();

	?>

	<div class="text-center">

		<a target="_blank" href="http://steamcommunity.com/profiles/<?php echo $info['steam64id']; ?>"><img src="<?php echo $steamInfoDecoded['response']['players'][0]['avatarfull']; ?>" class="about-avatar" /></a>

		<h1><?php echo $steamInfoDecoded['response']['players'][0]['personaname']; ?></h1>

		<h4><strong>Balance: </strong><?php echo $info['balance']; ?> coins</h4>
		<h4><strong>Steam ID: </strong><?php echo $info['steam64id']; ?></h4>
		<h4><strong>Registration date: </strong><?php echo $info['date']; ?></h4>
		<h4><strong>Rank: </strong><?php echo $rank; ?></h4>
		<h4><strong>Referred by: </strong>
		<a href="manage-user.php?id=<?php echo $referredById; ?>">
		<?php	
		if ($referredById != 0) {
			echo $steamInfoDecodedReferred['response']['players'][0]['personaname']; 
		}
		?>
		</a></h4>
		<h4><strong>Total referred users: </strong><a href="referredBy.php?id=<?php echo $info['id']; ?>"><?php echo $totalReferredUsers; ?></a></h4>
		<h4><strong>Total bet: </strong><?php echo $info['totalBet']; ?> coins</h4>

		<h3>Actions:</h3>
		<?php
			if($info['banned']!=='1'){echo '<a href="ban.php?id='.$_GET['id'].'&action=ban" class="btn btn-danger"><i class="fa fa-ban"></i> Ban user</a>';}
			elseif($info['banned']=='1'){echo '<a href="ban.php?id='.$_GET['id'].'&action=unban" class="btn btn-success"><i class="fa fa-check"></i> Unban user</a>';}
		?>
		<?php
			if($info['admin']!=='1'){echo '<a href="admin.php?id='.$_GET['id'].'&action=admin" class="btn btn-primary"><i class="fa fa-unlock"></i> Set admin</a>';}
			elseif($info['admin']=='1'){echo '<a href="admin.php?id='.$_GET['id'].'&action=unadmin" class="btn btn-info"><i class="fa fa-lock"></i> Remove admin</a>';}
		?>

		<a role="button" class="btn btn-primary" onclick="setBalanceDisplay();"><i class="fa fa-diamond"></i> Set balance</a>
		<div id="setBalanceDisplay">
			<div class="input-group" style="width:15%;margin-left:auto;margin-right:auto">
				<form action="setBalance.php" method="POST">
					<input type="hidden" value="<?php echo $_GET['id']; ?>" id="id" name="id" />
					<input type="text" class="form-control" value="<?php echo $info['balance']; ?>" placeholder="New balance..." name="balance" required />
					<span class="input-group-btn">
						<input type="submit" name="submit" class="btn btn-default" value="Set!" />
					</span>
				</form>
			</div>
		</div>


		<br><a href="users.php?page=<?php echo $_SESSION['page']; ?>" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Go back to user list</a>

	</div>


<?php require_once 'templates/footer.php'; ?>
