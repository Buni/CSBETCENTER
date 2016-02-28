<?php require_once 'templates/header.php'; ?>

	<div class="row">

		<div class="col-md-6 a-m-l">
			<h1>
				<i class="fa fa-arrow-up"></i>
				Upcoming matches
				<a href="addMatch.php" class="btn btn-default" style="float:right"><i class="fa fa-plus"></i> Add</a>
			</h1>
			

			<?php

				$dateNow = date('d.m.Y, G:i');

				$conn = new mysqli($servername, $username, $password, $dbname);
				if ($conn->connect_error) 
				{
					die("Connection failed: " . $conn->connect_error);
				}

				$sql = "SELECT * FROM matches WHERE winner='' ORDER BY date ASC";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) 
				{
					while($row = $result->fetch_assoc()) 
					{
						if($row['date'] >= $dateNow)
						{
							// bets data, team 1
							$sql4 = "SELECT * FROM bets WHERE matchId='".$row['id']."' AND betOn='team1'";
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
							$sql5 = "SELECT * FROM bets WHERE matchId='".$row['id']."' AND betOn='team2'";
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


							// displaying
							echo
							'
								<div class="match-container">
									<span>'.$row['date'].'</span>
									<span class="match-info">'.$row['message'].'</span>

									<a href="manage-match.php?id='.$row['id'].'">

										<div class="match">

											<span class="match-box">
												<span class="match-team">'.$row['team1'].'</span>
												<span class="match-percent">'.$team1Percent.'%</span>
											</span>

											<img src="../img/teams/'.$row['img1'].'.png" class="match-logo" />

											<span class="match-vs">vs</span>

											<img src="../img/teams/'.$row['img2'].'.png" class="match-logo" />

											<span class="match-box">
												<span class="match-team">'.$row['team2'].'</span>
												<span class="match-percent">'.$team1Percent.'%</span>
											</span>
            
										</div>

									</a>
								</div>
							';
						}
					}
				}

			?>

		</div>

		<div class="col-md-6 a-m-r">
			<h1>
				<i class="fa fa-arrow-down"></i>
				Live matches
			</h1>

			<?php

				$sql = "SELECT * FROM matches WHERE winner='' ORDER BY date ASC";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) 
				{
					while($row = $result->fetch_assoc()) 
					{
						if($row['date'] <= $dateNow)
						{
							// bets data, team 1
							$sql4 = "SELECT * FROM bets WHERE matchId='".$row['id']."' AND betOn='team1'";
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
							$sql5 = "SELECT * FROM bets WHERE matchId='".$row['id']."' AND betOn='team2'";
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


							// displaying
							echo
							'
								<div class="match-container">
									<span>'.$row['date'].' <strong style="color:green">LIVE</strong></span>
									<span class="match-info">'.$row['message'].'</span>

									<a href="manage-match.php?id='.$row['id'].'">

										<div class="match">

											<span class="match-box">
												<span class="match-team">'.$row['team1'].'</span>
												<span class="match-percent">'.$team1Percent.'%</span>
											</span>

											<img src="../img/teams/'.$row['img1'].'.png" class="match-logo" />

											<span class="match-vs">vs</span>

											<img src="../img/teams/'.$row['img2'].'.png" class="match-logo" />

											<span class="match-box">
												<span class="match-team">'.$row['team2'].'</span>
												<span class="match-percent">'.$team1Percent.'%</span>
											</span>
            
										</div>

									</a>
								</div>
							';
						}
					}
				}

				$conn->close();

			?>

		</div>

	</div>

	<p class="text-center"><a href="index.php" class="btn btn-default"><i class="fa fa-arrow-left"></i> Go back</a></p>

<?php require_once 'templates/footer.php'; ?>