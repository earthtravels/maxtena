<?php
class ExtraService
{
	public $id = 0;
    private $serviceNames = array();
	public $isNightly = false;
	public $maxNumberAvailable = 0;
	public $price = 0;
	
	public $errors = array();
    public static $staticErrors = array();


    public function getName($languageCode)
    {
    	$this->errors = array();
        if (isset($this->serviceNames[$languageCode]))
        {
            return $this->serviceNames[$languageCode];
        }
        else
        {
            $this->setError("Language code " . $languageCode . " could not be found.");
        }
        return NULL;
    }
	
	public static function fetchFromParameters($params) 
	{
        $extraService = new ExtraService();
		if (isset($params['id']) && is_numeric($params['id']))
		{
			$extraService->id = intval($params['id']);
		}
        foreach ($params as $key => $value)
        {
            if (preg_match('/service_name_[A-Za-z]{2}/', $key))
            {
                $languageCode = substr($key, -2);  
                $extraService->serviceNames[$languageCode] = $value;
            }
        }
		if (isset($params['is_nightly']))
		{
			$extraService->isNightly = intval($params['is_nightly']) == 1;
		}
		
		if (isset($params['max_available']) && is_numeric($params['max_available']))
		{
			$extraService->maxNumberAvailable = intval($params['max_available']);
		}
		
		if (isset($params['price']) && is_numeric($params['price']))
		{
			$extraService->price = floatval($params['price']);
		}	
		return $extraService;		
	}

    public static function fetchAllFromDb() 
	{
        $extraSevices = array();
        ExtraService::$staticErrors = array();
				
		$sql = "SELECT * FROM bsi_extra_services ORDER BY id";
		$query = mysql_query($sql);
        if (!$query)
        {
        	global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
            die ("Error: " . mysql_error());
        }
        while($row = mysql_fetch_assoc($query))
		{
            $extraService = ExtraService::fetchFromParameters($row);
            $extraSevices[] = $extraService;
		}
		return $extraSevices;
	}
	
	public static function fetchFromDb($id) 
	{
        ExtraService::$staticErrors = array();
		if (!is_numeric($id))
		{
            array_push(ExtraService::$staticErrors, "Id : $id is not numeric");
			return null;
		}
		
		$sql = "SELECT * FROM bsi_extra_services WHERE id = $id";
		$query = mysql_query($sql);
        if (!$query)
        {
        	global $logger;
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
            die ("Error: " . mysql_error());
        }
		if ($row = mysql_fetch_assoc($query))
		{
            $extraService = ExtraService::fetchFromParameters($row);
            if ($extraService == null)
            {
                return null;
            }
			return $extraService;
		}
		else
		{
            array_push(ExtraService::$staticErrors, "No extra service with Id : $id exists");
			return null;
		}		
	}

	public function isValid()
	{
		$this->errors = array();
        foreach($this->serviceNames as $serviceName)
        {
            if (strlen(trim($serviceName)) == 0)
            {
                $this->setError("Service name cannot be empty");
            }
        }
		if ($this->price <= 0)
		{
			$this->setError("Price must be greater than zero");
		}
		if (!$this->isNightly && $this->maxNumberAvailable <= 0)
		{
			$this->setError("Max number available must be zero or greater");
		}		
        return sizeof($this->errors) == 0;
	}
	
	public function save()
	{
		if (!$this->isValid())
		{
			return false;
		}
		
		else if ($this->id == 0)
		{
			// Run INSERT
			$sql = "INSERT INTO bsi_extra_services (";
            foreach ($this->serviceNames as $key => $value)
            {
                $sql.= "service_name_$key, ";
            }
            $sql.= "is_nightly, max_available, price) VALUES (";
            foreach ($this->serviceNames as $key => $value)
            {
                $sql.= "'" . mysql_escape_string($this->serviceNames[$key]) . "', ";
            }
		  	$sql.= ($this->isNightly ? 1 : 0). ", " . $this->maxNumberAvailable . ", " . $this->price . ")";		  	
			if (!mysql_query($sql))
			{
				global $logger;
				$logger->LogFatal("Error executing query: $sql");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				die('Error: ' . mysql_error());
			} 
			$this->id = mysql_insert_id();
		}
		else 
		{
			// Run UPDATE
			$sql = "UPDATE bsi_extra_services SET ";
            foreach ($this->serviceNames as $key => $value)
            {
                $sql.= "service_name_$key = '" . mysql_escape_string($this->serviceNames[$key]) . "', ";
            }
			$sql = $sql . "max_available = " . $this->maxNumberAvailable . ", is_nightly = " . ($this->isNightly ? 1 : 0) . ", price = " . $this->price . " WHERE id = " . $this->id;			 		  	
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
	
	public static function delete($id)	
	{
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_extra_services WHERE id = " . $id;			 		  	
			if (!mysql_query($sql))
			{
				global $logger;
				$logger->LogFatal("Error executing query: $sql");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
				die('Error: ' . mysql_error());
			}
		}
	}

    private function setError($errorMessage)
	{
		$this->errors[] = $errorMessage;
	}
}