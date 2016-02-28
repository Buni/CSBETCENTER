function setStream()
{
	var id = $("#matchid").val();
	var stream = $("#stream").val();
	$.ajax(
	{
		url: 'setStream.php', 
		type: 'POST', 
		data: 'id=' + id + '&stream=' + stream, 
		dataType: 'text', 
		success: function (data) 
		{
			//alert(data);
			location.reload(); 
		}
	});
}