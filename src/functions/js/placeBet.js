function updateValue() 
{
	var value = document.getElementById('betValueInput').value;

	var multiplier1 = document.getElementById('valueFor1team1').innerHTML;
	var multiplier2 = document.getElementById('valueFor1team2').innerHTML;

	document.getElementById('betValueDisplay1').innerHTML = value;
	document.getElementById('betValueDisplay2').innerHTML = value;

	document.getElementById('betValuemultiplier1').innerHTML = round(value * multiplier1, 2);
	document.getElementById('betValuemultiplier2').innerHTML = round(value * multiplier2, 2);
}

function round(n, k)
{
	var factor = Math.pow(10, k);
	return Math.round(n*factor)/factor;
}

function placeBet() 
{
	if (typeof selectedTeam === 'undefined')
	{
		document.getElementById('placeBetAlerts').innerHTML = '<div class="alert alert-danger"><i class="fa fa-exclamation-triangle"></i> <a href="#" class="close" onclick="placeBetFadeOut();">&times;</a>Pick a team in order to place bet.</div>';
		$("#placeBetAlerts").fadeIn();
	}
	if(selectedTeam)
	{
		var balance = '<?php echo $balance; ?>';
		var betValue = document.getElementById('betValueInput').value;
		//var nameOfSelectedTeam = document.getElementById(selectedTeam).innerHTML;
		if(selectedTeam=='team1')
		{
			var nameOfSelectedTeam = document.getElementById('matchTeam1').innerHTML;
		}
		else if(selectedTeam=='team2')
		{
			var nameOfSelectedTeam = document.getElementById('matchTeam2').innerHTML;
		}
		if(document.getElementById('placeBetButton').innerHTML=='Place bet')
		{
			document.getElementById('placeBetButton').innerHTML = 'Are you sure?';
			exit;
		}
		if(document.getElementById('placeBetButton').innerHTML=='Are you sure?')
		{
			if(betValue>=1 && betValue<= balance)
			{
				var steamid2 = $("#steamidreal").val();
				$.ajax(
				{
					url: 'placeBet.php', 
					type: 'POST', 
					data: 'amount=' + betValue + '&steamid=' + steamid2 + '&matchid=' + matchId + '&betOn=' + nameOfSelectedTeam + '&betOn2=' + selectedTeam, 
					dataType: 'text', 
					success: function (data) 
					{
						document.getElementById('placeBetAlerts').innerHTML = data;
						$("#placeBetAlerts").fadeIn();
					}
				});
				$("#placeBetAlerts").fadeIn();
			}
		}
	}
}

function placeBetFadeOut()
{
	$("#placeBetAlerts").fadeOut();
}