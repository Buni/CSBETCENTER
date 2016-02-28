<?php require_once 'templates/site/header.php'; ?>

<noscript>
	<?php echo '<link rel="stylesheet" href="css/home.css">'; ?>
</noscript>

<div class="welcome">
	<i class="fa fa-home fa-4x"></i>
	<h1 style="color:#fff">Welcome to CS:GO esports betting!</h1>
	<small>
		Here you can bet on professional matches and win items!
	</small>
</div>
<div class="welcome2">
	<h1 style="color:#fff">About</h1>
	<small>
		CSBETCENTER is a betting site, that allows you to place bet on professional CS:GO matches with ingame items known as skins.
	</small>
</div>
<div class="welcome3">
	<h1 style="color:#fff">Why choose us?</h1>
	<div class="row">
		<div class="col-md-4 why">
			<i class="fa fa-reply-all fa-3x why-icon"></i>
			<strong>Fast support</strong>
			<p>Our moderators will help you with your problem.</p>
		</div>
		<div class="col-md-4 why">
			<i class="fa fa-shield fa-3x why-icon"></i>
			<strong>Secure</strong>
			<p>Our system is full-secure, your items are safe.</p>
		</div>
		<div class="col-md-4 why">
			<i class="fa fa-gift fa-3x why-icon"></i>
			<strong>Gifts</strong>
			<p>Get gifts by betting matches, depositing or inviting friends.</p>
		</div>
		<div class="col-md-4 why">
			<i class="fa fa-users fa-3x why-icon"></i>
			<strong>Reffeals</strong>
			<p>Get coins by reffering your friends.</p>
		</div>
		<div class="col-md-4 why">
			<i class="fa fa-building fa-3x why-icon"></i>
			<strong>Stable</strong>
			<p>Even if Steam is down, you are still able to place bets on our site.</p>
		</div>
		<div class="col-md-4 why">
			<i class="fa fa-calculator fa-3x why-icon"></i>
			<strong>Fair</strong>
			<p>Items you deposit are safe until you withdraw them.</p>
		</div>
	</div>
</div>

<?php require_once 'templates/site/footer.php'; ?>

<script type="text/javascript">
	setInterval(function(){ $(".welcome").fadeIn(); }, 100);
	setInterval(function(){ $(".welcome2").fadeIn(); }, 200);
	setInterval(function(){ $(".welcome3").fadeIn(); }, 400);
</script>