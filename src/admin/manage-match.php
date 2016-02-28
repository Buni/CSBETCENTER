<?php require_once 'templates/header.php'; ?>

<style type="text/css">
	.mmmmmmmmmmm > h2, img, i
{
	display: inline-block;
}

.mmmmmmmmmmm > i, img
{
	width: 100px;
}

.mmmmmmmmmmm > h2
{
	margin-left: 10px;
	margin-right: 10px;
}
</style>

	<?php
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		$sql = "SELECT * FROM matches WHERE id='".$_GET['id']."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{
			while($row = $result->fetch_assoc()) 
			{
				$info = $row;
			}
		} 
		else 
		{
			echo 'Match not found.<a href="matches.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Go back to match list</a>';
			exit;
		}

		// bet data
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

		$sql4 = "SELECT * FROM bets WHERE matchId='".$_GET['id']."' AND betOn='team1'";
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
		$sql5 = "SELECT * FROM bets WHERE matchId='".$_GET['id']."' AND betOn='team2'";
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

		$steamInfo = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=2A19C2EA73F803C304ED6DEE6DEA4408&steamids='.$info['addedBy']);
		$steamInfoDecoded = json_decode($steamInfo, true);

		$addedBy = $steamInfoDecoded['response']['players'][0]['personaname'];

		$sql = "SELECT id FROM users WHERE steam64id='".$info['addedBy']."'";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{
			while($row = $result->fetch_assoc()) 
			{
				$id = $row['id'];
			}
		}

		$stream = '<a target="_blank" href="'.$info['stream'].'">'.$info['stream'].'</a>';
		if(empty($info['stream'])){$stream='No stream added.';}

		#$style1 = '';
		#$style2 = '';

		if($info['winner']=='team1')
		{
			$style1 = 'color:green';
			$style2 = '';
		}
		elseif($info['winner']=='team2')
		{
			$style1 = '';
			$style2 = 'color:green';
		}

		$winner = '';
		$winner = $info['winner'];

		$dateNow = date('d.m.Y, G:i');

		if($info['date']<=$dateNow){$live='<strong style="color:green">LIVE</strong>';}
		else{$live='';}

		$conn->close();

	?>

	<div class="text-center mmmmmmmmmmm">

		<h1><strong style="<?php echo $style1; ?>"><?php echo $info['team1']; ?></strong> vs <strong style="<?php echo $style2; ?>"><?php echo $info['team2']; ?></strong></h1>

		<img src="../img/teams/<?php echo $info['img1'] ?>.png"> 
		<h2>VS</h2> 
		<img src="../img/teams/<?php echo $info['img2'] ?>.png">

		<div class="progress" style="width:50%;margin-left:25%;margin-top:15px">
			<div class="progress-bar progress-bar-success" style="width:<?php echo $team1Percent; ?>%"></div>
			<div class="progress-bar" style="width:<?php echo $team2Percent; ?>%"></div>
		</div>

		<h3><?php echo $team1Percent; ?>% / <?php echo $team2Percent; ?>%</h3>

		<h4><strong>Added by: </strong><a href="manage-user.php?id=<?php echo $id; ?>"><?php echo $addedBy; ?></a></h4>
		<h4><strong>Date: </strong><?php echo $info['date'].' '.$live; ?></h4>
		<h4><strong>Stream: </strong><?php echo $stream; ?></h4>
		<h4><strong>Message: </strong><?php echo $info['message']; ?></h4>
		<h4><strong>Total bet: </strong><a href="usersBetOn.php?id=<?php echo $_GET['id']; ?>"><?php echo $totalbet; ?> coins</a> (<?php echo $totalbetusers; ?> users)</h4>
		<h4><strong>Winner: </strong><?php echo @$info[$winner]; ?></h4>

		<h3>Actions:</h3>

		<input type="hidden" id="matchid" value="<?php echo $_GET['id']; ?>" />

		<?php 
			if($info['winner']!=='')
			{
		?>
			<a role="button" disabled="disabled" class="btn btn-primary"><i class="fa fa-trophy"></i> Set winner</a>
			<a role="button" disabled="disabled" class="btn btn-default" style="color: #000;"><i class="fa fa-ban" style="color:red"></i> Close with no result</a>
			<a role="button" class="btn btn-default" data-toggle="collapse" href="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2"><i class="fa fa-info-circle"></i> Set message</a>
		<?php
			}
			elseif($info['winner']=='')
			{ 
		?>
			<a role="button" data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample" class="btn btn-primary"><i class="fa fa-trophy"></i> Set winner</a>
			<a role="button" class="btn btn-default" onclick="setWinner('none');"><i class="fa fa-ban" style="color:red"></i> Close with no result</a>
			<a role="button" class="btn btn-default" data-toggle="collapse" href="#collapseExample2" aria-expanded="false" aria-controls="collapseExample2"><i class="fa fa-info-circle"></i> Set message</a>
			<?php if($info['stream']!==''){$t='Change';} elseif($info['stream']==''){$t='Add';} ?>
			<a role="button" class="btn btn-default" data-toggle="collapse" href="#collapseExample3" aria-expanded="false" aria-controls="collapseExample2"><i class="fa fa-twitch"></i> <?php echo $t; ?> stream</a>

			<div class="collapse" id="collapseExample" style="margin-top:10px;font-size:20px">
				<a role="button" onclick="setWinner('team1');"><?php echo $info['team1']; ?></a> 
				or 
				<a role="button" onclick="setWinner('team2');"><?php echo $info['team2']; ?></a>
			</div>

		<?php
			}
		?>

		<div class="collapse" id="collapseExample2" style="margin-top:10px;font-size:20px">
			<div class="col-lg-6 col-lg-offset-3">
				<div class="input-group">
					<input type="hidden" id="matchid" value="<?php echo $_GET['id']; ?>">
					<input type="text" id="msg" class="form-control" placeholder="New message...">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" onclick="setMessage();">Set!</button>
					</span>
				</div>
			</div>
		</div>

		<div class="collapse" id="collapseExample3" style="margin-top:10px;font-size:20px">
			<div class="col-lg-6 col-lg-offset-3">
				<div class="input-group">
					<input type="text" id="stream" class="form-control" value="<?php echo $info['stream']; ?>" placeholder="New link...">
					<span class="input-group-btn">
						<button class="btn btn-default" type="button" onclick="setStream();">Set!</button>
					</span>
				</div>
			</div>
		</div>


		<div class="col-md-12">
			<a href="matches.php" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Go back to match list</a>
		</div>

	</div>


<?php require_once 'templates/footer.php'; ?>