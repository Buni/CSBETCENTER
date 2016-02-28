<?php
	require_once 'connect.php';

	$conn = new mysqli($servername, $username, $password, $dbname);

	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}

	if(empty($_GET['code']))
	{
		echo '
			<div class="alert alert-danger">
				<i class="fa fa-exclamation-triangle"></i> 
				<a href="#" class="close" onclick="promoCodeFadeOut();">&times;</a>
				Please type your promo code.
			</div>';
		exit;
	}

	if(isset($_GET['code']))
	{
		$code = strtoupper($_GET['code']);

		$sql = "SELECT steam64id, code FROM users WHERE code='".$code."' AND steam64id!='".$_GET['steamid']."'";
		$result = $conn->query($sql);

		if (@$result->num_rows > 0) 
		{
			echo '
				<div class="alert alert-danger">
					<i class="fa fa-exclamation-triangle"></i>
					<a href="#" class="close" onclick="promoCodeFadeOut();">&times;</a> 
					This code is already in use. Please type another one.
				</div>
				';
		}
		else
		{
			$codeL = strlen($code);
			if($codeL <= 4)
			{
				echo '
					<div class="alert alert-danger">
						<i class="fa fa-exclamation-triangle"></i> 
						<a href="#" class="close" onclick="promoCodeFadeOut();">&times;</a>
						Your promo code has to be atleast 5 characters.
					</div>
					';
			}
			else
			{
				if(!preg_match('/\s/',$code))
				{
					if(!preg_match('/[^A-Za-z0-9]/', $code)) // '/[^a-z\d]/i' should also work.
					{
						$sql2 = "UPDATE users SET code='".$code."' WHERE steam64id='".$_GET['steamid']."'";

						if ($conn->query($sql2) === TRUE) 
						{
							echo '
								<div class="alert alert-success">
									<i class="fa fa-check"></i> 
									<a href="#" class="close" onclick="promoCodeFadeOut();">&times;</a>
									Your promo code has been changed to <strong>'.$code.'</strong>.
								</div>
								';
						} 
						else 
						{
							echo "
								<div class='alert alert-danger'>
									<i class='fa fa-exclamation-triangle'></i> 
									<a href='#' class='close' onclick='promoCodeFadeOut();'>&times;</a>
									Couldn't connect to database. Try refreshing page or contact the administrator.
								</div>
								";
						}
					}
					elseif (preg_match('/[^A-Za-z0-9]/', $code))
					{
						echo "
							<div class='alert alert-danger'>
								<i class='fa fa-exclamation-triangle'></i> 
								<a href='#' class='close' onclick='promoCodeFadeOut();'>&times;</a>
								Your promo code can contain only letters and numbers.
							</div>
							";
					}
				elseif(preg_match('/\s/',$code))
				{
					echo "
						<div class='alert alert-danger'>
							<i class='fa fa-exclamation-triangle'></i> 
							<a href='#' class='close' onclick='promoCodeFadeOut();'>&times;</a>
							Your promo code can't contain spaces.
						</div>
						";
					}
				}
			}
		}

	}

	$conn->close();
?>