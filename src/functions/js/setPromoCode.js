function setPromoCode() 
{
	var code2 = $("#code2").val();
	var steamid2 = $("#steamid2").val();
	$.ajax(
	{
		url: 'setPromoCode.php', 
		type: 'GET', 
		data: 'code=' + code2 + '&steamid=' + steamid2, 
		dataType: 'text', 
		success: function (data) 
		{
			document.getElementById('setPromoCodeResult').innerHTML = data;
			$("#setPromoCodeResult").fadeIn();
		}
	});
}

function promoCodeFadeOut()
{
	$("#setPromoCodeResult").fadeOut();
}