<?php	
//Wait for PI to boot up because this file is auto loaded on boot.
sleep(60);
//Function to get index of first occurance of number.
function get_index($message){
	preg_match('/^\D*(?=\d)/', $message, $i);
	return isset($i[0]) ? strlen($i[0]) : false;
}
while(1){
	//Get all the ongoing matches from cricapi.
	$cricketMatchesTxt = file_get_contents('http://cricapi.com/api/cricket/?apikey=123456789');
	$cricketMatches = json_decode($cricketMatchesTxt);
	//Get the previously selected match to display score on LCD.
	$f=fopen("/var/www/html/country1.txt", "r");
	$c=fread($f,filesize("/var/www/html/country1.txt"));

	//Remove numbers and special symbols.
	$country = preg_replace('/[\/&*0-9]/u', '', $c);
	
	$found=0;
	//Check the score of previously selected match.
	foreach($cricketMatches->data as $item) {
		//Check if previously selected match is still going on and is in the list returned by cricapi.
		if(strpos($item->title,trim($country)) !== false)	
		{		
			$found=1;
			$countries=preg_replace('/\s+/', ' ', $item->title);		
			$v_separate = explode(" v ", $countries);
			$country1 = $v_separate[0];
			$country2 = $v_separate[1];
			//Condition to check if selected countries have scores or not.
			if(preg_match('/[0-9]/', $country1) && preg_match('/[0-9]/', $country2))
			{	
				//Split country1 name and its score.
				$c_name1=substr($country1,0,(get_index($country1)-1));
				$c_score1=substr($country1,get_index($country1),strlen($country1));
				
				//Split country2 name and its score.
				$c_name2=substr($country2,0,(get_index($country2)-1));
				$c_score2=substr($country2,get_index($country2),strlen($country2));
			
				//Write the scores of both countries in their own files.
				$country1_file = fopen("/var/www/html/country1.txt", "w");
				$country2_file = fopen("/var/www/html/country2.txt", "w");
			
				//Check if the length of country name with score is more tham 16, because, our LCD display is 16x2. If its more then print at next line.
				if((strlen($c_name1.$c_score1)+1)<17)
					fwrite($country1_file, trim($country1));
				else
					fwrite($country1_file, trim($c_name1."\n".$c_score1));
			
				if((strlen($c_name2.$c_score2)+1)<17)
					fwrite($country2_file, trim($country2));
				else
					fwrite($country2_file, trim($c_name2."\n".$c_score2));
			
				fclose($country1_file);
				fclose($country2_file);
			}
			else if(preg_match('/[0-9]/', $country1))
			{
				//Split country1 name and its score.
				$c_name1=substr($country1,0,(get_index($country1)-1));
				$c_score1=substr($country1,get_index($country1),strlen($country1));
				
				
				//Write the scores of both countries in their own files.
				$country1_file = fopen("/var/www/html/country1.txt", "w");	
			
				//Check if the length of country name with score is more tham 16, because, our LCD display is 16x2. If its more then print at next line.
				if((strlen($c_name1.$c_score1)+1)<17)
					fwrite($country1_file, trim($country1));
				else
					fwrite($country1_file, trim($c_name1."\n".$c_score1));
			
				fclose($country1_file);
				
				//This block is used to save name of country2 in files if its doesn't have any score.	
				$country2_file = fopen("/var/www/html/country2.txt", "w");
				fwrite($country2_file, trim($country2));
				fclose($country2_file);
			}
			else if(preg_match('/[0-9]/', $country2))
			{
				//Split country2 name and its score.
				$c_name2=substr($country2,0,(get_index($country2)-1));
				$c_score2=substr($country2,get_index($country2),strlen($country2));
			
				//Write the scores of both countries in their own files.	
				$country2_file = fopen("/var/www/html/country2.txt", "w");
			
				//Check if the length of country name with score is more tham 16, because, our LCD display is 16x2. If its more then print at next line.
				
				if((strlen($c_name2.$c_score2)+1)<17)
					fwrite($country2_file, trim($country2));
				else
					fwrite($country2_file, trim($c_name2."\n".$c_score2));
				
				fclose($country2_file);
				
				//This block is used to save name of country1 in files if it doesn't have any score.	
				$country1_file = fopen("/var/www/html/country1.txt", "w");
				fwrite($country1_file, trim($country1));
				fclose($country1_file);	
			}
		}
	}
	if($found==0)
	{
		//If previously selected match is not found in the list from cricapi, we can display message that is it finished.
		$country1_file = fopen("/var/www/html/country1.txt", "w");		
		$country2_file = fopen("/var/www/html/country2.txt", "w");
		fwrite($country1_file, "Match has been\nfinished");	
		fwrite($country2_file, "Match has been\nfinished");	
		fclose($country1_file);
		fclose($country2_file);		
	}	
	//Continuously check for score every 10 seconds.
	sleep(10);
}
?>
