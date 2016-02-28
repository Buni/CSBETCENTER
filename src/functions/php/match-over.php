<?php

	function matchOver($id, $time, $team1, $team2, $img1, $img2, $percent1, $percent2, $info, $winner)
	{
		$template = file_get_contents('templates/match-over.html');
		$template = str_replace('[id]', $id, $template);
		$template = str_replace('[time]', $time, $template);
		$template = str_replace('[team1]', $team1, $template);
		$template = str_replace('[team2]', $team2, $template);
		$template = str_replace('[img1]', $img1, $template);
		$template = str_replace('[img2]', $img2, $template);
		$template = str_replace('[percent1]', $percent1, $template);
		$template = str_replace('[percent2]', $percent2, $template);
		$template = str_replace('[info]', $info, $template);

		if($winner=='team1')
		{
			$template = str_replace('[team1-winner]', '<i class="fa fa-check fa-2x match-winner"></i>', $template);
		}
		if($winner=='team2')
		{
			$template = str_replace('[team2-winner]', '<i class="fa fa-check fa-2x match-winner"></i>', $template);
		}

		$template = str_replace('[team1-winner]', '', $template);
		$template = str_replace('[team2-winner]', '', $template);

		echo $template;
	}

?>