<?php
function getUserDateFormat($inputDtFormat){		
	$dtformatter = array('dd'=>'%d', 'mm'=>'%m', 'yyyy'=>'%Y', 'yy'=>'%y');		
	$dtformat = split("[/.-]", $inputDtFormat);
	$dtseparator = ($dtformat[0] === 'yyyy')? substr($inputDtFormat, 4, 1) : substr($inputDtFormat, 2, 1);
	return $dtformatter[$dtformat[0]].$dtseparator.$dtformatter[$dtformat[1]].$dtseparator.$dtformatter[$dtformat[2]];	
}
function getMySqlDate($date, $outputFormat){
	$dateformatter = split("[/.-]", $outputFormat);
	$date_part = split("[/.-]", $date);		
	$date_array = array();		
	for($i=0; $i<3; $i++) {
		$date_array[$dateformatter[$i]] = $date_part[$i];
	}
	return $date_array['yyyy']."-".$date_array['mm']."-".$date_array['dd'];
}
?>