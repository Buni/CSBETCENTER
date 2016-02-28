<?php require_once 'templates/site/header.php'; ?>

<?php

	if(!isset($_SESSION['steamid'])) 
	{
		include '401.php';
		exit;
	}

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT totalbet, commision FROM users WHERE referredBy='".$steamprofile['steamid']."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
			$referredUsers = mysqli_num_rows($result);
			@$totalEarnings += $row['commision'];
		}				
	}

	$sql2 = "SELECT steam64id FROM users WHERE referredBy='".$steamprofile['steamid']."' AND hasDeposit='1'";
	$result2 = $conn->query($sql2);

	if ($result2->num_rows > 0) 
	{
		while($row2 = $result2->fetch_assoc()) 
		{
			$depositors = mysqli_num_rows($result2);
		}				
	}
	else
	{
		$depositors = 0;
	}

	$sql4 = "SELECT code, available FROM users WHERE steam64id='".$steamprofile['steamid']."'";
	$result4 = $conn->query($sql4);

	if ($result4->num_rows > 0) 
	{
		while($row4 = $result4->fetch_assoc()) 
		{
			$promo = $row4['code'];
			$available = $row4['available'];
		}				
	}

	?>

<div class="row" style="margin:15px">

	<div class="col-md-10 col-md-offset-1">

		<div id="setPromoCodeResult" style="display:none"></div>
		<div id="collectEarningsResult" style="display:none"></div>

		<div class="input-group" style="margin-bottom:25px">
			<input type="text" placeholder="Update your refferal code..." class="form-control" id="code2" required>
			<input type="hidden" value="<?php echo $steamprofile['steamid'] ?>" id="steamid2">
			<div class="input-group-btn">
				<input type="submit" class="btn btn-primary" value="Update" onclick="setPromoCode();" />
			</div>
		</div>

		<table class="table table-bordered"> 
			<tbody> 
				<tr> 
					<td>Promo code</td> 
					<td><?php echo $promo; ?></td> 
				</tr> 
				<tr> 
					<td>Referred users</td> 
					<td><?php echo $referredUsers; ?></td> 
				</tr> 
				<tr> 
					<td>Depositors</td> 
					<td><?php echo $depositors; ?></td> 
				</tr> 
				<tr> 
					<td>Total earnings</td> 
					<td><?php echo $totalEarnings; ?></td> 
				</tr> 
				<tr> 
					<td>Available now</td> 
					<td><?php echo $available; ?></td> 
				</tr> 
			</tbody> 
		</table>

		<button class="btn btn-success btn-block" onclick="collectEarnings();">Collect Earnings</button>

		<table class="table table-bordered" style="margin-top:25px;"> 
			<thead> 
				<tr> 
					<th>Steam ID</th> 
					<th>Joined</th> 
					<th>Total bet</th> 
					<th>Commision</th> 
				</tr> 
			</thead> 
			<tbody> 
				<?php
					$sql2 = "SELECT steam64id, date, totalbet, commision FROM users WHERE referredBy='".$steamprofile['steamid']."'";
					$result2 = $conn->query($sql2);

					if ($result2->num_rows > 0) 
					{
						while($row2 = $result2->fetch_assoc()) 
						{
							echo '<tr><td>'.$row2['steam64id'].'</td>';
							echo '<td>'.$row2['date'].'</td>';
							echo '<td>'.$row2['totalbet'].'</td>';
							echo '<td>'.$row2['commision'].'</td></tr>';
						}				
					}

					$conn->close();
				?>
			</tbody> 
		</table>

	</div>
	
</div>

<?php require_once 'templates/site/footer.php'; ?>
