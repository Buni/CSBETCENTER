<?php require_once 'templates/header.php'; ?>

	<div class="row">
		<div class="col-md-6 col-md-offset-3 text-center">

			<h1><i class="fa fa-envelope-o"></i> Ticket review</h1>

			<form action="tickets.php" method="POST">
				<?php

					$conn = new mysqli($servername, $username, $password, $dbname);
					if ($conn->connect_error) 
					{
						die("Connection failed: " . $conn->connect_error);
					}		

					$sql = "SELECT * FROM tickets WHERE status='open'";
					$result = $conn->query($sql);

					if ($result->num_rows > 0) 
					{
						while($row = $result->fetch_assoc()) 
						{
							$info = $row;
							echo '<a href="#" onclick="loadTicket('.$row['id'].');">'.$row['subject'].'</a><br>';
						}
					} 
					else 
					{
						echo 'There is not tickets.';
					}	

					if(isset($_POST['ssubmit']))
					{
						$answer = $_POST['answer'];
						$tsteamid = $_POST['tsteamid'];

						$sql = "UPDATE tickets SET status='closed', answer='".$answer."', reviewedBy='".$tsteamid."' WHERE id='".$info['id']."'";
						#echo $sql;

						if ($conn->query($sql) === TRUE) 
						{
							header('location: tickets.php');
						} 
						else 
						{
							echo "Error updating record: " . $conn->error;
						}
					}

					$conn->close();

				?>
			</form>
		</div>
		<div class="col-md-12 text-center" id="contactDetails"></div>
	</div>

	<p class="text-center"><a href="index.php" class="btn btn-default" style="margin-top:30px"><i class="fa fa-arrow-left"></i> Go back</a></p>

<?php require_once 'templates/footer.php'; ?>