</div>
	<!-- BODY END -->
	
	<!-- jQuery -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
	<script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<script src="js/notify/jquery.bootstrap-growl.js"></script>

	<!-- Bootstrap Core JavaScript -->
	<script src='https://www.google.com/recaptcha/api.js'></script>

	<script type="text/javascript">

		$(document).ready(function() {
			$(".welcome3").css("height",$(window).height()-500);
		});

		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
		})

		document.getElementById("loginA").onclick = function() {
			document.getElementById("loginForm").submit();
		}

	</script>

	<!-- JS -->
	<?php
		foreach (glob("functions/js/*.js") as $filename2)
		{
			echo '<script src="'.$filename2.'"></script>';
		}
	?>



</body>

</html>
