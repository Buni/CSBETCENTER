function collectEarnings() 
{
	var steamid2 = $("#steamid").val();
	$.ajax(
	{
		url: 'collectEarnings.php', 
		type: 'GET', 
		data: 'steamid=' + steamid2, 
		dataType: 'text', 
		success: function (data) 
		{
			document.getElementById('collectEarningsResult').innerHTML = data;
			$("#collectEarningsResult").fadeIn();
		}
	});
}

function collectEarningsFadeOut()
{
	$("#collectEarningsResult").fadeOut();
}