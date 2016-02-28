<?php

	session_start();

	require_once '../connect.php';

	$conn = new mysqli($servername, $username, $password, $dbname);
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	$sql = "SELECT steam64id, admin, banned, date FROM users WHERE steam64id='".$_SESSION['steamid']."'";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) 
	{
		while($row = $result->fetch_assoc()) 
		{
			$steam64idUser = $row['steam64id'];
			$adminUser = $row['admin'];
			$bannedUser = $row['banned'];
		}
	} 

	$conn->close();

	if(!isset($_SESSION['steamid'])) 
	{
		header('location: ../index.php');
		exit;
	}
	else
	{
		include ('../steamauth/userInfo.php');
	}

	if($adminUser!=='1')
	{
		header('location: ../index.php');
		exit;
	}

	if($bannedUser=='1')
	{
		header('location: ../index.php');
		exit;
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="description">
	<meta name="author" content="mvegter197">

	<title>CSBETCENTER - admin panel</title>

	<!-- Bootstrap Core CSS -->
	<link href="../css/bootstrap.min.css" rel="stylesheet">

	<!-- Favicon -->
	<link rel="shortcut icon" href="../img/favicon2.ico">

	<!-- FontAwesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

	<!-- Custom CSS -->
	<link href="../css/cosmo.css" rel="stylesheet">
	<link href="../css/style.css" rel="stylesheet">
	<link href="../css/buttons.css" rel="stylesheet">
	<link href="../css/spinner.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato" /> 

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
</head>

<body scroll="no">

	<script type="text/javascript">

		$(function() {
			//$.bootstrapGrowl("<h4><strong><i class='icon fa fa-gift'></i> Gift!</h4></strong><p>You have received a gift! Go to 'Gifts' tab to see what it is!</p>", { type: 'success', delay: '4000' });
			//$.bootstrapGrowl("<h4><strong><i class='icon fa fa-clock-o'></i> Timezone alert!</h4></strong><p>You haven't set your timezone, so we set it Europe/Warsaw. If you want to change it, go to your settings page.</p>", { type: 'danger', delay: '7000' });
		});

	</script>

	<!-- INCLUDE -->
	<?php
		
		foreach (glob("functions/php/*.php") as $filename)
		{
			include $filename;
		}

		require '../steamauth/steamauth.php';

		@session_start();

		$balance = '0';

		require_once '../connect.php';
		$conn = new mysqli($servername, $username, $password, $dbname);

		if ($conn->connect_error) 
		{
			die("Connection failed: " . $conn->connect_error);
		}

		$sql2 = "SELECT id FROM matches WHERE winner=''";
		$result2 = $conn->query($sql2);
		$upcomingMatches = mysqli_num_rows($result2);

		?>

	<!-- BODY -->

	<!-- TOP NAVBAR -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand logob" href="/">
					<img src="../img/logo200x200.png" class="logo" />
					CSBETCENTER.EU
				</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav navbar-right">
					<?php

						if(!isset($_SESSION['steamid'])) 
						{
							echo 
							'
							<li>
								<a href="#" id="loginA">
									<i class="fa fa-steam-square" style="color:#000"></i> Log in with Steam
								</a>
							</li>
							'; // custom login buttom

							steamlogin(); //login form

						}  
						else 
						{
							include ('../steamauth/userInfo.php'); //To access the $steamprofile array
							//Protected content

							$sql = "SELECT steam64id, admin, balance FROM users WHERE steam64id='".$steamprofile['steamid']."'";
							$result = $conn->query($sql);

							if ($result->num_rows > 0) 
							{
								while($row = $result->fetch_assoc()) 
								{
									if($row['admin']==1)
									{
										$isAdmin = 1;
									}	
									else
									{
										$isAdmin = 0;
									}

									$balance = $row['balance'];
								}
							} 
							else 
							{
								$isAdmin = 0;
							}

							$conn->close();

							if(@$isAdmin==1)
							{
								$adminpanel = '<li><a href="admin"><i class="fa fa-lock"></i> Admin panel</a></li>';
							}

							echo '<span id="balanceContainer"><span class="navbar-text" id="balance"><strong>Balance:</strong> <span id="balance-text">'.$balance.'</span> <i class="fa fa-refresh refreshBalance" onclick="refreshBalance()"></i></span></span>';

							echo
							'
								<li class="dropdown">
									<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
										<img style="width:20px;height:20px;" src="'.$steamprofile['avatar'].'">
										'.$steamprofile['personaname'].' 
										<span class="caret"></span>
									</a>
									<ul class="dropdown-menu">
										'.@$adminpanel.'
										<li><a href="../bets.php"><i class="fa fa-pie-chart"></i> My bets</a></li>
										<li><a href="../user-settings.php"><i class="fa fa-cogs"></i> Settings</a></li>
										<li role="separator" class="divider"></li>
										<li><a href="../steamauth/logout.php" id="logoutA"><i class="fa fa-sign-out"></i> Log out</a></li>
									</ul>
								</li>
							';
						}     
					?>
				</ul>
			</div>
		</div>
	</nav>

	<input type="hidden" id="steamidreal" value="<?php echo $steamprofile['steamid']; ?>" />

	<!-- MAIN CONTENT -->
	<div style="margin-top:70px !important" class="container">