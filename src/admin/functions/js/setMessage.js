function setMessage()
{
	var id = $("#matchid").val();
	var msg = $("#msg").val();
	$.ajax(
	{
		url: 'setMessage.php', 
		type: 'POST', 
		data: 'id=' + id + '&msg=' + msg, 
		dataType: 'text', 
		success: function (data) 
		{
			//alert(data);
			location.reload(); 
		}
	});
}