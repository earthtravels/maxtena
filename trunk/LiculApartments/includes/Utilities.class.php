<?php
class Utilities
{
	public static function getMySqlValue($var)
	{		
		if (is_null($var))
		{
			return "NULL";
		}
		else if (is_numeric($var))
		{
			return $var;
		}
		else if (is_bool($var))
		{
			return $var ? "1" : "0";
		}
		else if (is_string($var))
		{
			if (strlen(trim($var)) == 0)
			{
				return "NULL";
			}
			else
			{
				return "'" . mysql_escape_string(trim($var)) . "'";
			}
		}		
		else if (is_object($var) && $var instanceof Date)		
		{
			return $var->formatMySql();			
		}
		else 
		{
			return $var;
		}			
	}
	
	public static function stringEndsWith($string, $test) 
	{		
	    $strlen = strlen($string);
	    $testlen = strlen($test);
	    if ($testlen > $strlen) return false;
	    return substr_compare($string, $test, -$testlen) === 0;		
	}
	
	public static function stringLastReplace($search, $replace, $subject)
	{
	    $pos = strrpos($subject, $search);
	    if($pos === false)
	    {
	        return $subject;
	    }
	    else
	    {
	        return substr_replace($subject, $replace, $pos, strlen($search));
	    }
	}	
} 
?>