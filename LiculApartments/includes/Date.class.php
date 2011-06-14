<?php
//require_once ("SystemConfiguration.class.php");
class Date extends DateTime
{ 	
    public $errors = array(); 
    
	private $_date_time;
   
    public function __toString() {
        return $this->format('c'); // format as ISO 8601
    }
   
    public function __sleep() {
        $this->_date_time = $this->format('c');
        return array('_date_time');
    }
   
    public function __wakeup() {
        $this->__construct($this->_date_time);
    }

    public function __construct($timestamp = null)
    {
    	global $systemConfiguration;    	
    	global $logger;
    	parent::__construct($timestamp, $systemConfiguration->getTimeZone());
//    	if ($timestamp != null)
//    	{
//    		$year = intval(date("Y", $timestamp));
//    		$month = intval(date("m", $timestamp));
//    		$logger->LogInfo("Year=" . $year);
//    		$logger->LogInfo("Month=" . $month);
//    		$this->setDate(date("Y", $timestamp), date("m", $timestamp), date("d", $timestamp));
//    		$this->setTime(date("G", $timestamp), date("i", $timestamp), date("s", $timestamp));
//    		//$this->setTimestamp($timestamp); // = "@" . $timestamp;
//    	}
    	
    }
	
	public static function parse($dateString) 
	{
		$date = new Date();	
		$date->setTime(0, 0, 1);	
		$date->errors = array();
        switch(gettype($dateString))
		{
            case "string":
                if (strlen($dateString) != 10) 
                {					
					return null;
                }
                
                $day = 0;
                $month = 0;
                $year = 0;
                
				if (strpos($dateString, "/") !== false)
				{
					$array = explode("/", $dateString);
					if (sizeof($array) != 3)
					{
						return null;
					}
					$month = intval($array[0]);
	                $day = intval($array[1]);
	                $year = intval($array[2]);
				}
				else if (strpos($dateString, "-") !== false)
				{
					$array = explode("-", $dateString);
					if (sizeof($array) != 3)
					{
						return null;
					}
					$month = intval($array[1]);
	                $day = intval($array[2]);
	                $year = intval($array[0]);
				}
				else
				{		
					$date->errors[] = "Date string is not separated by '-' or '/'";			
					return null;
				}
				
				if (!checkdate($month, $day, $year)) 
				{		       
					$date->errors[] = "Invalid date";  	
		            return null;
		        }
		        $date->setDate($year, $month, $day);
		        $date->setTime(0, 0, 1);				
				return $date;	
				break;
			case "integer":
				if (strlen($dateString) != 8) 
                {
					$date->setError("Invalid date");
					return null;
                }
                $month = intval(substr($dateString, 5, 2));
                $day = intval(substr($dateString, 7, 2));
                $year = intval(substr($dateString, 1, 4));
				if (!checkdate($month, $day, $year)) 
				{		  
					$date->setError("Invalid date");       	
		            return null;
		        }
		        $date->setDate($year, $month, $day);
		        $date->setTime(0, 0, 0);		        
				return $date;
				break;
			default:
				$date->setError("Neither integer nor string was passed to parse function");				
				return null;				
				break;
		}
	}
	
	public function formatMySql()
	{
		return $this->format("Y-m-d");
	}
	
	public function getTimestamp()
	{		
		return intval($this->format("U"));
	}

	public function getInterval($date=NULL, $type = "days") 
	{
		$dateTimestamp = strtotime("now");		
		if ($date != null && $date instanceof Date)
		{
			$dateTimestamp = $date->getTimestamp();
		}
		
		if ($type == "days")
		{
			return round(($this->getTimestamp() - $dateTimestamp) / 86400); 
		}
		else if ($type == "minutes")
		{
			return round(($this->getTimestamp() - $dateTimestamp) / 1440); 
		}
		return $this->getTimestamp() - $dateTimestamp;		  
	}
	
	public function isBetween($date1, $date2) 
	{
		if (!($date1 instanceof Date && $date2 instanceof Date))
		{
			return false;
		}
		$thisTimestamp = $this->getTimestamp();
		return $thisTimestamp >= $date1->getTimestamp() && $thisTimestamp <= $date2->getTimestamp();				  
	}
	
	public function nextDay() 
	{		
		$timestamp = $this->getTimestamp() + 86400;
		$nextDay = new Date();		
    	$nextDay->setDate(date("Y", $timestamp), date("m", $timestamp), date("d", $timestamp));
    	$nextDay->setTime(date("G", $timestamp), date("i", $timestamp), date("s", $timestamp));    				
		return $nextDay;			  
	}
	
	public function compareTo($date)
	{
		$thisTimestamp = $this->getTimestamp();		
		if ($date != null && $date instanceof Date)
		{
			$dateTimestamp = $date->getTimestamp();
		}
		else
		{
			$dateTimestamp = strtotime("now");
		}
		if ($thisTimestamp > $dateTimestamp)
		{
			return 1;
		}
		else if ($thisTimestamp < $dateTimestamp)
		{
			return -1;
		}
		else 
		{
			return 0;
		}				
	}
	
	private function setError($errorMessage)
	{
		$this->errors[] = $errorMessage;
	}
}