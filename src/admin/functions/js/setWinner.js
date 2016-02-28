function setWinner(winner)
{
	var matchId = $("#matchid").val();
	$.ajax(
	{
		url: 'setWinner.php', 
		type: 'POST', 
		data: 'matchId=' + matchId + '&winner=' + winner, 
		dataType: 'text', 
		success: function (data) 
		{
			//alert(data);
			location.reload(); 
		}
	});
}