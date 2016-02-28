<?php require_once 'templates/header.php'; ?>

	<div class="row">
		<div class="col-md-6 col-md-offset-3 text-center">

			<h1><i class="fa fa-plus"></i> Match adding</h1>

			<form action="addMatch.php" method="POST">
				<label>Team 1:</label>
				<?php

					$string = file_get_contents('templates/teams.txt');
					$array = explode(PHP_EOL, $string);

					echo '<select id="team1" class="form-control" name="team1" onchange="addOther();">';

					foreach($array as $k=>$v)
					{
						$array[$k] = explode(' - ', $v);
						echo '<option value="'.$array[$k][0].':'.$array[$k][1].'">'.$array[$k][1].'</option>';
					}

					echo '<option>Other...</option>';
					echo '</select>';

				?>

				<label>Team 2:</label>
				<?php

					$string = file_get_contents('templates/teams.txt');
					$array = explode(PHP_EOL, $string);

					echo '<select id="team2" class="form-control" name="team2" onchange="addOther();">';

					foreach($array as $k=>$v)
					{
						$array[$k] = explode(' - ', $v);
						echo '<option value="'.$array[$k][0].':'.$array[$k][1].'">'.$array[$k][1].'</option>';
					}

					echo '<option>Other...</option>';
					echo '</select>';

				?>

				<label>Date (CET):</label>
				<input type="text" class="form-control" name="date" placeholder="DD.MM.RRRR, HH:MM" required>

				<label>Stream link (not required):</label>
				<input type="text" class="form-control" name="stream" placeholder="http://twitch.tv/...">

				<label>Additional message (not required):</label>
				<input type="text" class="form-control" name="message" placeholder="...">

				<input type="hidden" name="addedBy" value="<?php echo $steamprofile['steamid']; ?>">

				<input type="submit" class="btn btn-success" style="margin-top:10px" name="submit" value="+ Add">
				<a href="matches.php" class="btn btn-default" style="margin-top:10px"><i class="fa fa-arrow-left"></i> Go back</a>

			</form>

			<?php 
				if(isset($_POST['submit']))
				{
					$team1result = $_POST['team1'];
					$team1result = explode(':', $team1result);

					$team1img = $team1result[0];
					$team1name = $team1result[1];

					$team2result = $_POST['team2'];
					$team2result = explode(':', $team2result);

					$team2img = $team2result[0];
					$team2name = $team2result[1];

					$date = $_POST['date'];
					$stream = $_POST['stream'];
					$message = $_POST['message'];

					$addedBy = $_POST['addedBy'];

					$conn = new mysqli($servername, $username, $password, $dbname);
					if ($conn->connect_error) 
					{
    					die("Connection failed: " . $conn->connect_error);
					}

					$sql = "INSERT INTO matches (team1, img1, team2, img2, date, stream, message, addedBy) VALUES ('".$team1name."', '".$team1img."', '".$team2name."', '".$team2img."', '".$date."', '".$stream."', '".$message."', '".$addedBy."')";

					if ($conn->query($sql) === TRUE) 
					{
						header('location: matches.php');
					} 
					else 
					{
						echo "Error: " . $sql . "<br>" . $conn->error;
					}

					$conn->close();
				}

				if(isset($_POST['submit1']))
				{
					$newTeam1Name = $_POST['newTeam1Name'];

					$target_dir = "../img/teams/";
					$target_file = $target_dir . basename($_FILES["logo"]["name"]);
					$uploadOk = 1;
					$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
					// Check if image file is a actual image or fake image
					$check = getimagesize($_FILES["logo"]["tmp_name"]);
					if($check !== false) 
					{
						#echo "File is an image - " . $check["mime"] . ".";
						$uploadOk = 1;
					} 
					else 
					{
						echo "File is not an image.";
						$uploadOk = 0;
						exit;
					}

					if (file_exists($target_file)) 
					{
						echo "Sorry, file already exists.";
						exit;
						$uploadOk = 0;
					}

					// Allow certain file formats
					if($imageFileType != "png") 
					{
						echo "Sorry, only PNG files are allowed.";
						exit;
						$uploadOk = 0;
					}
					// Check if $uploadOk is set to 0 by an error
					if ($uploadOk == 0) 
					{
						echo "Sorry, your file was not uploaded.";
						exit;
					// if everything is ok, try to upload file
					} 
					else 
					{
						$namef = $_FILES["logo"]["name"];
						$namef = substr($namef, 0, -4);
						if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) 
						{
							echo "The file ". basename( $_FILES["logo"]["name"]). " has been uploaded.";
						} 
						else 
						{
							echo "Sorry, there was an error uploading your file.";
							exit;
						}
					}

					$teamList = file_get_contents('templates/teams.txt');
					$teamlistNew = $teamList."\r\n".$namef.' - '.$newTeam1Name;
					$teamListPut = file_put_contents('templates/teams.txt', $teamlistNew);

					header('location: addMatch.php');
				}
			?>

		</div>

		<div class="modal fade" id="addOtherModal" tabindex="-1" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">Add other team</h4>
					</div>
					<div class="modal-body">
						<form action="addMatch.php" method="POST" enctype="multipart/form-data">
							<div class="row">
								<div class="col-md-6">
									<label>Team name</label>
									<input type="text" name="newTeam1Name" placeholder="Team name..." class="form-control" required>
								</div>
								<div class="col-md-6">
									<label>Logo (200x200, png)</label>
									<input type="file" name="logo" />
								</div>
							</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						<input type="submit" name="submit1" class="btn btn-primary" value="+ Add" />
						</form>
					</div>
				</div><!-- /.modal-content -->
			</div><!-- /.modal-dialog -->
		</div><!-- /.modal -->

	</div>

<?php require_once 'templates/footer.php'; ?>