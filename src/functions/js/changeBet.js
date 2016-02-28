function changeBet(matchId)
{
	var steamid = $("#steamidreal").val();
	$.ajax(
	{
		url: 'changeBet.php', 
		type: 'POST', 
		data: 'matchId=' + matchId + '&steamid=' + steamid, 
		dataType: 'text', 
		success: function (data) 
		{
			//alert(data);
			location.reload(); 
		}
	});
}