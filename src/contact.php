<?php require_once 'templates/site/headerbanned.php'; ?>

<div class="row" style="padding:15px">

	<div class="col-md-6">
		<h1>Submit new ticket</h1>

		<div>
		<?php 
			if(empty($_SESSION['cmsg'])){$_SESSION['cmsg']='';} 
			echo $_SESSION['cmsg']; 
		?>
		</div>

		<?php
			if(!isset($_SESSION['steamid']))
			{
				echo '<h2>In order to contact support you have to be logged in. If for some reason you can not log in, please use our <a href="http://steamcommunity.com/groups/csbetcenter">Steam group</a>.';
				exit;
			}
		?>
		
		<form action="contact.php" method="POST">
			<input type="email" class="form-control" name="email" placeholder="Your email address..." style="margin-top:10px" required>
			<input type="text" class="form-control" name="subject" placeholder="Subject..." style="margin-top:10px" required>
			<input type="hidden" name="steamid4" value="<?php echo $_SESSION['steamid']; ?>">
			<textarea name="message" class="form-control" placeholder="Your message..." style="margin-top:10px" required></textarea>
			<input type="submit" class="btn btn-default" name="submit" style="margin-top:10px" value="Submit" />
		</form>

		<?php

			unset($_SESSION['cmsg']);

			if(isset($_POST['submit']))
			{
				$email = $_POST['email'];
				$subject = $_POST['subject'];
				$steamid = $_POST['steamid4'];
				$message = $_POST['message'];
				$date = date('d.m.Y, G:i');

				$conn = new mysqli($servername, $username, $password, $dbname);
				if ($conn->connect_error) 
				{
					die("Connection failed: " . $conn->connect_error);
				}

				$sql = "INSERT INTO tickets (userId, subject, email, message, status, date) VALUES ('".$steamid."', '".$subject."', '".$email."', '".$message."', 'open', '".$date."')";
				#echo $sql;

				if ($conn->query($sql) === TRUE) 
				{
					$_SESSION['cmsg'] = '<div class="alert alert-success" role="alert"><i class="fa fa-check"></i> Your message was sent to our team. We will answer as soon as possible.<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
					header('location: contact.php');
				} 
				else 
				{
					echo "Error: " . $sql . "<br>" . $conn->error;
				}

			}

		?>

	</div>

	<div class="col-md-6">
		<h1>Your tickets</h1>
		<?php

			$conn = new mysqli($servername, $username, $password, $dbname);
			if ($conn->connect_error) 
			{
				die("Connection failed: " . $conn->connect_error);
			}

			$sql = "SELECT id, subject, message, status, answer, date, reviewedBy FROM tickets WHERE userId='".$_SESSION['steamid']."'";
			$result = $conn->query($sql);

			if ($result->num_rows > 0) 
			{
				echo '<table class="table table-bordered text-center">';
				echo '<thead>';
				echo '<tr><td>Subject</td><td>Status</td><td>Reviewed by</td></tr>';
				echo '</thead>';
				echo '<tbody>';
				while($row = $result->fetch_assoc()) 
				{
					$info = $row;
					if($row['status']=='open'){$status='<span style="color:green">OPEN</span>';}
					if($row['status']=='closed'){$status='<span style="color:red">CLOSED</span>';}

					$answer = $row['answer'];
					if(empty($answer)){$answer='No answer yet. Please be patient.';}

					$steamInfo = file_get_contents('http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=2A19C2EA73F803C304ED6DEE6DEA4408&steamids='.$row['reviewedBy']);
					$steamInfoDecoded = json_decode($steamInfo, true);
					$reviewedby = @$steamInfoDecoded['response']['players'][0]['personaname'];

					echo 
					'
						<tr>
							<td><a href="#" onclick="loadTicket('.$row['id'].');">'.$row['subject'].'</a></td>
							<td>'.$status.'</td>
							<td><a target="_blank" href="http://steamcommunity.com/profiles/'.$row['reviewedBy'].'">'.$reviewedby.'</a></td>
						</tr>
					';
				}
				echo '</tbody>';
				echo '</table>';
			} 
			else 
			{
				echo 'You didnt submit any ticket.';
			}	

			$conn->close();

		?>
	</div>

	<div class="col-md-12 text-center" id="contactDetails"></div>
</div>


<?php require_once 'templates/site/footer.php'; ?>
