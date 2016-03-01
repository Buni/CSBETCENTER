<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="description">
	<meta name="author" content="mvegter197">

	<title>CSBETCENTER - esport betting</title>

	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">

	<!-- Favicon -->
	<link rel="shortcut icon" href="img/favicon2.ico">

	<!-- FontAwesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">

	<!-- Custom CSS -->
	<link href="css/cosmo.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/buttons.css" rel="stylesheet">
	<link href="css/spinner.css" rel="stylesheet">
	<link href='https://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Lato" /> 

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script type="text/javascript">
		document.getElementById("loginA").onclick = function() {
			document.getElementById("loginForm").submit();
		}
	</script>
</head>

<body scroll="no">

	<script type="text/javascript">
		document.getElementById("loginA").onclick = function() {
			document.getElementById("loginForm").submit();
		}

		$(function() {
			$.bootstrapGrowl("<h4><strong><i class='icon fa fa-gift'></i> Gift!</h4></strong><p>You have received a gift! Go to 'Gifts' tab to see what it is!</p>", { type: 'success', delay: '4000' });
			$.bootstrapGrowl("<h4><strong><i class='icon fa fa-clock-o'></i> Timezone alert!</h4></strong><p>You haven't set your timezone, so we set it Europe/Warsaw. If you want to change it, go to your settings page.</p>", { type: 'danger', delay: '7000' });
		});

	</script>

	<!-- INCLUDE -->
	<?php
		
		foreach (glob("functions/php/*.php") as $filename)
		{
			include $filename;
		}

		require 'steamauth/steamauth.php';

		@session_start();

		$balance = '0';

		require_once 'connect.php';
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
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand logoa logob" href="/">
					<img src="img/logo200x200.png" class="logo" />
					CSBETCENTER.EU
				</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li>
						<a href="#" data-toggle="modal" data-target="#myModal">
							<i class="fa fa-diamond" style="color:#3588E5"></i> Free coins
						</a>
					</li>
				</ul>
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
							include ('steamauth/userInfo.php'); //To access the $steamprofile array
							//Protected content

							$sql = "SELECT steam64id, admin, balance, banned FROM users WHERE steam64id='".$steamprofile['steamid']."'";
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

									$banned = 0;

									if($row['banned']==1)
									{
										$banned = 1;
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
										<li><a href="bets.php"><i class="fa fa-pie-chart"></i> My bets</a></li>
										<li><a href="user-settings.php"><i class="fa fa-cogs"></i> Settings</a></li>
										<li role="separator" class="divider"></li>
										<li><a href="steamauth/logout.php" id="logoutA"><i class="fa fa-sign-out"></i> Log out</a></li>
									</ul>
								</li>
							';

							registerNewUser($steamprofile['steamid']);
						}     
					?>
				</ul>
			</div>
		</div>
	</nav>

	<input type="hidden" id="steamidreal" value="<?php echo $steamprofile['steamid']; ?>" />

	<!-- LEFT NAVBAR -->
	<nav class="navbar2 navbar navbar-default logob kkk" role="navigation" style="height:1500px;float:left;border-right:1px solid #E7E7E7">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
		</div>
		<div class="collapse navbar-collapse2 navbar-collapse navbar-ex1-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav2 navbar-nav">

				<li>
					<a href="index.php">
						<i class="fa fa-home menu-icon" style="font-size:18px;color:#333333;"></i> Home
					</a>
				</li>

				<li>
					<a href="matches.php">
						<i class="fa fa fa-gamepad menu-icon" style="font-size:18px;color:#333333;"></i> Matches 
						<span class="badge" style="float:right"><?php echo $upcomingMatches; ?></span>
					</a>
				</li>

				<li class="line"></li>

				<li>
					<a href="deposit.php">
						<i class="fa fa-plus-circle menu-icon" style="color:#5FAE63;font-size:18px;"></i> Deposit
					</a>
				</li>
				<li>
					<a href="withdraw.php">
						<i class="fa fa-minus-square menu-icon" style="color:#BC5652;font-size:18px;"></i> Withdraw
					</a>
				</li>

				<li>
					<a href="ref.php">
						<i class="fa fa-users menu-icon" style="color:#4A87F2;font-size:18px;"></i> Referrals
					</a>
				</li>

				<li>
					<a href="gifts.php">
						<i class="fa fa-gift menu-icon" style="color:#E69B23;font-size:18px;"></i> Gifts
						<span class="badge" style="float:right">0</span>
					</a>
				</li>	

				<li class="line"></li>

				<li>
					<a href="contact.php">
						<i class="fa fa-envelope-o menu-icon" style="font-size:18px;color:#333333;"></i> Contact
					</a>
				</li>

				<li>
					<a href="about.php">
						<i class="fa fa-question-circle menu-icon" style="font-size:18px;color:#333333;"></i> About us
					</a>
				</li>

				<li class="line"></li>

				<!-- STEAM STATUS -->
				<div class="steam-status">
					<p>
						<span style="font-weight:bold">
							Steam status
							<i class="fa fa-question-circle menu-icon" style="font-size:18px;float:right" data-toggle="tooltip" data-placement="bottom" title="If Steam is offline, that means you can't deposit, withdraw or even log in to the site."></i>
						</span>
						<br>
						<?php

							$file = file_get_contents('http://is.steam.rip/api/v1/?request=IsSteamRip');
							$json = json_decode($file, true);

							if(@$json['result']['community']=='false')
							{
								echo '<span style="color:green">Online</span>';
							}
							else
							{
								echo '<span style="color:red">Offline</span>';
							}

						?>
						
					</p>
				</div>

				<li class="line"></li>

				<!-- SOCIALS -->
				<div class="socials">
					<a href="http://steamcommunity.com/groups/csbetcenter" class="social-icon" target="_blank"><i class="fa fa-steam-square"></i></a>

					<a href="http://facebook.com/#" class="social-icon" target="_blank"><i class="fa fa-facebook-square"></i></a>
				</div>

				<!-- FOOTER -->
				<footer>
					<div class="footer">
						<p>Made with <i class="fa fa-heart" style="color:red"></i> by gamers</p>
						<p>Powered by Steam</p>
					</div>
				</footer>

			</ul>
		</div><!-- /.navbar-collapse -->
	</nav>

	<!-- Free coins Modal -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Redeem code for free coins</h4>
				</div>
				<div class="modal-body">
					<input type="text" placeholder="Code..." class="form-control" id="code" required>
					<input type="hidden" value="<?php echo $steamprofile['steamid'] ?>" id="steamid">
					<p id="redeemResult"></p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" onclick="redeem()">Redeem</button>
				</div>
			</div>
		</div>
	</div>

	<!-- MAIN CONTENT -->
	<div class="main">
		<?php
			if(@$banned==1)
			{
				require_once 'banned.php';
				exit;
			}
		?>
