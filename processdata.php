<?php
//Get the selected countries from index.php page and split them in two countries.
$countries=preg_replace('/\s+/', ' ', $_POST["country"]);
$v_separate = explode(" v ", $countries);
$country1 = $v_separate[0];
$country2 = $v_separate[1];

//Function to get index of first occurance of number.
function get_index($message){
	preg_match('/^\D*(?=\d)/', $message, $i);
	return isset($i[0]) ? strlen($i[0]) : false;
}
echo $country1.": ".$country2;
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
	
	//This block is used to save name of country2 in files if it doesn't have any score.	
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
	
	//This block is used to save name of country1 in files if its doesn't have any score.	
	$country1_file = fopen("/var/www/html/country1.txt", "w");
	fwrite($country1_file, trim($country1));
	fclose($country1_file);	
}
?>
