<?php
class DateUtils
{
	public static function isValidDate($date, $format)
	{		
		$day = "";
		$month = "";
		$year = "";
		if (!DateUtils::parseDate($date, $format, $day, $month, $year))
		{
			return false;
		}
		return checkdate($month, $day, $year);		
	}
	
	public static function getMySqlDate($date, $format)
	{
		$day = "";
		$month = "";
		$year = "";
		if (!DateUtils::parseDate($date, $format, $day, $month, $year))
		{
			return false;
		}
		return str_pad($year, 4, '0', STR_PAD_LEFT) . "-" . str_pad($year, 2, '0', STR_PAD_LEFT) . "-" . str_pad($year, 2, '0', STR_PAD_LEFT);		
	}
	
	public static function getNumericDate($date, $format)
	{
		$day = "";
		$month = "";
		$year = "";
		if (!DateUtils::parseDate($date, $format, $day, $month, $year))
		{
			return false;
		}
		return intval(str_pad($year, 4, '0', STR_PAD_LEFT) . str_pad($year, 2, '0', STR_PAD_LEFT) . str_pad($year, 2, '0', STR_PAD_LEFT));		
	}
	
	
	public static function parseDate($date, $format, &$day, &$month, &$year)	
	{
		$date = strtolower($date);
		$format = strtolower($format);
		if (trim($date) == "" || trim($format) == "")
		{
			return false;
		}
		else if (strlen($date) < strlen($format))
		{
			return false;
		}
		
		$dayIndex = strpos($format, "dd");
		if ($dayIndex === false)
		{
			return false;
		}
		
		$monthIndex = strpos($format, "mm");
		if ($monthIndex === false)
		{
			return false;
		}
		
		$yearIndex = strpos($format, "yyyy");
		if ($yearIndex === false)
		{
			return false;
		}
		
		$day = substr($date, $dayIndex, 2);
		$month = substr($date, $monthIndex, 2);
		$year = substr($date, $yearIndex, 2);
		
		if (!is_int($day))
		{
			return false;
		}
		else if (!is_int($month))
		{
			return false;
		}
		else if (!is_int($year))
		{
			return false;
		}
		return true;		
	}
} 
?>