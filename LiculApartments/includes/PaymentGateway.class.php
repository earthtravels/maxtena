<?php
class PaymentGateway
{  
    // id, gateway_name, gateway_code, account, enabled, is_admin
    public $id = 0;
    public $gatewayName;    
    public $gatewayCode = "";
    public $account = "";
    public $isEnabled = false;
    public $isAdmin = false;  
    public $displayOrder = 0;
    public $productionUrl;
    public $testUrl;    
    public $isProductionMode = 0;  
	
    public $errors = array();	
    public static $staticErrors = array();

    public function __construct()
    {
		$this->gatewayName = new LocalizedText("gateway_name_");
    }
    
    public function getUrl()
    {
    	if ($this->isProductionMode)
    	{
    		return $this->productionUrl;
    	}
    	return $this->testUrl;
    }
	
    public static function fetchFromParameters($params) 
    {
		$paymentGateway = new PaymentGateway();
		if (isset($params['id']) && is_numeric($params['id']))
		{
		    $paymentGateway->id = intval($params['id']);
		}
		$paymentGateway->gatewayName = LocalizedText::fetchFromParameters($params, "gateway_name_");
		if (isset($params['gateway_code']))
		{
		    $paymentGateway->gatewayCode = $params['gateway_code'];
		}		
		if (isset($params['account']))
		{
		    $paymentGateway->account = $params['account'];
		}		  
    	if (isset($params['production_url']))
		{
		    $paymentGateway->productionUrl = $params['production_url'];
		}
    	if (isset($params['test_url']))
		{
		    $paymentGateway->testUrl = $params['test_url'];
		}
    	if (isset($params['is_production_mode']))
		{
		    $paymentGateway->isProductionMode = intval($params['is_production_mode']) == 1;
		}
	    if (isset($params['enabled']))
		{
		    $paymentGateway->isEnabled = intval($params['enabled']) == 1;
		}
    	if (isset($params['is_admin']))
		{
		    $paymentGateway->isAdmin = intval($params['is_admin']) == 1;
		}
    	if (isset($params['display_order']) && is_numeric($params['display_order']))
		{
		    $paymentGateway->displayOrder = intval($params['display_order']);
		}	        
		return $paymentGateway;
    }
    
    public static function fetchAllFromDb() 
    {
        PaymentGateway::$staticErrors = array();
		$paymentGateways = array();
		$sql = "SELECT * FROM bsi_payment_gateway ORDER BY display_order";
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
		    $paymentGateway = PaymentGateway::fetchFromParameters($row);
		    $paymentGateways[] = $paymentGateway;
		}
		return $paymentGateways;		
    }
    
	public static function fetchFromDbNonAdmin() 
    {
        PaymentGateway::$staticErrors = array();
		$paymentGateways = array();
		$sql = "SELECT * FROM bsi_payment_gateway WHERE gateway_code != 'ha' ORDER BY display_order";
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
		    $paymentGateway = PaymentGateway::fetchFromParameters($row);
		    $paymentGateways[] = $paymentGateway;
		}
		return $paymentGateways;		
    }
    
	public static function fetchFromDbNonAdminActive() 
    {
        PaymentGateway::$staticErrors = array();
		$paymentGateways = array();
		$sql = "SELECT * FROM bsi_payment_gateway WHERE gateway_code != 'ha' AND enabled = 1 ORDER BY display_order";
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
		    $paymentGateway = PaymentGateway::fetchFromParameters($row);
		    $paymentGateways[] = $paymentGateway;
		}
		return $paymentGateways;		
    }
    
    public static function fetchFromDb($id) 
    {                                   
		PaymentGateway::$staticErrors = array();
		if (!is_numeric($id))
		{
		    PaymentGateway::setStaticError("Id " . $id . " is not numeric.");			
		    return null;
		}
				
		$sql = "SELECT * FROM bsi_payment_gateway WHERE id = " . $id;
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
		    $paymentGateway = PaymentGateway::fetchFromParameters($row);			
		    return $paymentGateway;
		}
		else
		{
		    PaymentGateway::setStaticError("No payment gateway with id " . $id . " could be found.");
		    return NULL;
		}		
    }

    public static function fetchFromDbForCode($paymentGatewayCode) 
    {                                   
		PaymentGateway::$staticErrors = array();
		$sql = "SELECT * FROM bsi_payment_gateway WHERE gateway_code = '" . strtolower(trim(mysql_escape_string($paymentGatewayCode))) . "'";
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
		    $paymentGateway = PaymentGateway::fetchFromParameters($row);			
		    return $paymentGateway;
		}
		else
		{
		    PaymentGateway::setStaticError("Payment gateway does not exist.");
		    return null;
		}		
    }   

    public function isValid()
    {
		$this->errors = array();		
		if (intval($this->id) <= 0 && !is_int($this->id))
		{
		    $this->setError("Id is invalid");
		}
		if ($this->gatewayName->areAnyValuesEmpty())
		{
			$this->setError("Gateway name cannot be empty");
		}		
//		foreach ($this->gatewayNames as $gatewayName) 
//		{
//			if (strlen(trim($gatewayName)) == 0)
//			{
//			    $this->setError("Gateway name cannot be empty.");
//			    break;
//			}
//		}
		if (strlen(trim($this->gatewayCode)) == 0)
		{
		    $this->setError("Payment gateway code cannot be empty.");
		}
	
		return sizeof($this->errors) == 0;					
    }
    
    public function save($isValidated=false)
    {
		$this->errors = array();
		if (!$isValidated && !$this->isValid())
		{
		    return false;
		}

        $this->gatewayCode = strtolower(trim($this->gatewayCode));
		if ($this->id == 0)
        {
            // Run INSERT
            $sql = "INSERT INTO bsi_payment_gateway (";
            $sql.= $this->gatewayName->getMySqlFields(false);
//	        foreach ($this-> as $key => $value)
//            {
//                $sql.= "room_desc_" . $key . ", ";
//            }
            $sql.= "gateway_code, account, enabled, is_admin, is_production_mode, production_url, test_url, display_order) VALUES (";
            $sql.= $this->gatewayName->getMySqlValues(false);
            $sql.= "'" . mysql_escape_string($this->gatewayCode) . "', ";
            $sql.= "'" . mysql_escape_string($this->account) . "', ";
            $sql.= ($this->isEnabled ? "1" : "0") . ", ";
            $sql.= ($this->isAdmin ? "1" : "0") . ", ";
            $sql.= ($this->isProductionMode ? "1" : "0") . ", ";
            $sql.= "'" . mysql_escape_string($this->productionUrl) . "', ";
            $sql.= "'" . mysql_escape_string($this->testUrl) . "', ";
            $sql.= "" . $this->displayOrder;            
            $sql.= ")";
            $query = mysql_query($sql);
            if (!$query)
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
	    	$sql = "UPDATE bsi_payment_gateway SET ";
	    	$sql.= $this->gatewayName->getMySqlValuesForSet(false);
            $sql.= "gateway_code = '" . mysql_escape_string($this->gatewayCode) . "', ";
            $sql.= "account = '" . mysql_escape_string($this->account) . "', ";
            $sql.= "enabled = " . ($this->isEnabled ? "1" : "0") . ", ";
            $sql.= "is_admin = " . ($this->isAdmin ? "1" : "0") . ", ";
            $sql.= "is_production_mode = " . ($this->isProductionMode ? "1" : "0") . ", ";
            $sql.= "production_url = '" . mysql_escape_string($this->productionUrl) . "', ";
            $sql.= "test_url = '" . mysql_escape_string($this->testUrl) . "', ";
            $sql.= "display_order = " . $this->displayOrder;            
            $sql.= " WHERE id = " . $this->id;
		    $query = mysql_query($sql);
		    if (!$query)
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
		global $logger;
		PaymentGateway::$staticErrors = array();
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_payment_gateway WHERE id = " . $id;			 		  	
			if (!mysql_query($sql))
			{
				$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
				$logger->LogError("SQL: $sql");
				die('Error: ' . mysql_error());
			}
			return true;
		}
		else
		{
			PaymentGateway::$staticErrors[] = "Id is not numeric";
			return false;
		}
	}
    
    private function setError($errorMessage)
    {
		$this->errors[] = $errorMessage;
    }

    private static function setStaticError($errorMessage)
    {
        PaymentGateway::$staticErrors[] = $errorMessage;
    }
}