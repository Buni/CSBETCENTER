function selectTeam(id)
{
	if(id==1)
	{
		$('#matchTeam1').addClass('selectedName');
		$('#matchTeam2').removeClass('selectedName');
		selectedTeam = 'team1';
	}
	else if(id==2)
	{
		$('#matchTeam2').addClass('selectedName');
		$('#matchTeam1').removeClass('selectedName');
		selectedTeam = 'team2';
	}
	
}