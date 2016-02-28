function addOther() 
{
	var selectBox = document.getElementById("team1");
	var selectedValue = selectBox.options[selectBox.selectedIndex].value;
	if(selectedValue=='Other...')
	{
		$('#addOtherModal').modal('show');
	}

	var selectBox2 = document.getElementById("team2");
	var selectedValue2 = selectBox2.options[selectBox2.selectedIndex].value;
	if(selectedValue2=='Other...')
	{
		$('#addOtherModal').modal('show');
	}
}
