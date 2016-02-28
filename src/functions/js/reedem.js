function redeem() 
{
	var code = $("#code").val();
	var steamid = $("#steamid").val();
	$.ajax(
	{
		url: 'redeem.php', 
		type: 'POST', 
		data: 'code=' + code + '&steamid=' + steamid, 
		dataType: 'text', 
		success: function (data) 
		{
			document.getElementById('redeemResult').innerHTML = data;
		}
	});
}