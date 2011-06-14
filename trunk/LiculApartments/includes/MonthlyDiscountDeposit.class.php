<?php
class MonthlyDiscountDeposit
{  	
	private $monthNumber = 0;
	private $monthName = "";
	public  $discountPercent = 0;
    public  $depositPercent = 0;
	
	public $errors = array();	
    public static $staticErrors = array();

	public function getMonthNumber()
    {
		return $this->monthNumber;
    }

    public function getMonthName()
    {
		return $this->monthName;
    }
	
    public static function fetchFromParameters($params) 
    {
		$monthlyDiscountDesposit = new MonthlyDiscountDeposit();
		if (isset($params['month_num']) && is_numeric($params['month_num']))
		{
			$monthlyDiscountDesposit->monthNumber = intval($params['month_num']);
		}
		if (isset($params['month']))
		{
			$monthlyDiscountDesposit->monthName = $params['month'];
		}		
		if (isset($params['discount_percent']))
		{
			$monthlyDiscountDesposit->discountPercent = floatval($params['discount_percent']);
		}		  
	        if (isset($params['deposit_percent']))
		{
			$monthlyDiscountDesposit->depositPercent = floatval($params['deposit_percent']);
		}
		return $monthlyDiscountDesposit;
    }
    
    public static function fetchAllFromDb() 
    {
        MonthlyDiscountDeposit::$staticErrors = array();
		$monthlyDiscountDesposits = array();
		$sql = "SELECT * FROM bsi_deposit_discount ORDER BY month_num";
		$query = mysql_query($sql);
		if (!$query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$monthlyDiscountDesposit = MonthlyDiscountDeposit::fetchFromParameters($row);
			$monthlyDiscountDesposits[] = $monthlyDiscountDesposit;
		}
		return $monthlyDiscountDesposits;		
    }
    
    public static function fetchFromDb($monthNumber) 
    {                                   
		MonthlyDiscountDeposit::$staticErrors = array();
		if (!is_numeric($monthNumber))
		{
		    MonthlyDiscountDeposit::setStaticError("Month number " . $monthNumber . " is not numeric.");			
		    return null;
		}
				
		$sql = "SELECT * FROM bsi_deposit_discount WHERE month_num = " . $monthNumber;
		$query = mysql_query($sql);
		if (!$query)
		{
			global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		    die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		if ($row = mysql_fetch_assoc($query))
		{
		    $monthlyDiscountDesposit = MonthlyDiscountDeposit::fetchFromParameters($row);			
		    return $monthlyDiscountDesposit;
		}
		else
		{
		    MonthlyDiscountDeposit::setStaticError("No disco with id " . $monthNumber . " could be found.");
		    return NULL;
		}		
    }

    public static function fetchFromDbForDate($date) 
    {     	         
        MonthlyDiscountDeposit::$staticErrors = array();
		if (!($date instanceof Date))
        {
            MonthlyDiscountDeposit::setStaticError("Date is not an instance of date");
	    	return null;
        }		
		return MonthlyDiscountDeposit::fetchFromDb(intval($date->format("m")));
    }

    public function isValid()
    {
		$this->errors = array();		
		if (intval($this->monthNumber) <= 0 && intval($this->monthNumber) >= 13)
		{
			$this->setError("Month number is invalid");
		}
		if (strlen(trim($this->monthName)) == 0)
		{
			$this->setError("Month name cannot be empty.");
		}
		if (floatval($this->discountPercent) < 0 || floatval($this->discountPercent) > 100)
		{
			$this->setError("Discount percent must be greater than zero and less than 100");
		}
		if (floatval($this->depositPercent) < 0 || floatval($this->depositPercent) > 100)
		{
			$this->setError("Deposit percent must be greater than zero and less than 100");
		}
		return sizeof($this->errors) == 0;					
    }
    
    public function save()
    {
	$this->errors = array();
	if (!$this->isValid())
	{
	    return false;
	}
	
	if ($this->monthNumber > 0 && $this->monthNumber < 13)
	{					
	    // Run UPDATE
	    $sql = "UPDATE bsi_deposit_discount ";
	    $sql.= "SET discount_percent = " . $this->discountPercent;
	    $sql.= ", deposit_percent = " . $this->depositPercent;
	    $sql.= " WHERE month_num = " . $this->monthNumber;			 		  	
	    if (!mysql_query($sql))
	    {
	    	global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		    die('Error: ' . mysql_error());
	    }												
	}	
	return true;	
    }
    
    private function setError($errorMessage)
    {
		$this->errors[] = $errorMessage;
    }

    private static function setStaticError($errorMessage)
    {
        Client::$staticErrors[] = $errorMessage;
    }
}