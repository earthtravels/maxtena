<?php
require_once ("SystemConfiguration.class.php");

class Booking
{	
    public $id = 0;
	public $roomId = 0;
	public $bookingTime = ""; 
	public $startDate = null;
	public $endDate = null;
    public $clientId = 0;
    public $adultCount = 0;
    public $childCount = 0;
    public $extraGuestCunt = 0;
    public $promoCode = "";
	public $totalCost = 0;
	public $paymentGatewayId = 0;
	public $paymentAmount = 0;
	public $isPaymentSuccessful = false;
	public $paymentTransactionId = 0;
	public $paypalEmail = "";
	public $languageCode = "";
	public $invoice = "";
	public $specialId = 0;
	public $specialRequests = 0;
	public $isBlocked = false;
	public $isDeleted = false;
		
	public $errors = array();	
	public static $staticErrors = array();
	
	public function __construct()
	{	 
	}	
	
	public function getClient()
	{
		global $logger;		
		$logger->LogDebug(__METHOD__ . " Getting client for booking with client id: " . $this->clientId);
		
		$client = Client::fetchFromDb($this->clientId);
		if ($client == null)
		{
			$logger->LogError("There is no client with id: " . $this->clientId);
			$this->errors = Client::$staticErrors;
		}
		else
		{
			$logger->LogDebug("Found room with id: " . $this->clientId);
		}
		return $client;
	}
	
	public function getRoom()
	{
		global $logger;		
		$logger->LogDebug(__METHOD__ . "Getting room for booking with room id: " . $this->roomId);
		
		$room = Room::fetchFromDb($this->roomId);
		if ($room == null)
		{
			$logger->LogError("There is no room with id: " . $this->roomId);
			$this->errors = Room::$staticErrors;
		}
		else
		{
			$logger->LogDebug("Found room with id: " . $this->clientId);
		}
		return $room;
	}
	
	public function getPaymentGateway()
	{		
		global $logger;		
		$logger->LogDebug(__METHOD__ . " Getting payment gateway for booking with payment gateway id: " . $this->paymentGatewayId);
		
		$paymentGateway = PaymentGateway::fetchFromDb($this->paymentGatewayId);		
		if ($paymentGateway == null)
		{
			$logger->LogWarn("There is no payment gateway with id: " . $this->paymentGatewayId);
			$this->errors = PaymentGateway::$staticErrors;
		}
		else
		{
			$logger->LogDebug("Found payment gateway with id: " . $this->paymentGatewayId);
		}
		return $paymentGateway;
	}	
	
	public static function fetchFromParameters($params)
    {
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Creating new " . __CLASS__ . " object from parameters ...");
		$logger->LogDebug("Parameters are:");
		$logger->LogDebug($params);
		
    	
    	$booking = new Booking();
    	if (isset($params['booking_id']))
    	{
    		$booking->id = intval($params['booking_id']);
    	}
    	if (isset($params['room_id']))
    	{
    		$booking->roomId = intval($params['room_id']);
    	}
    	if (isset($params['booking_time']))
    	{
    		$booking->bookingTime = $params['booking_time'];
    	}
    	if (isset($params['start_date']))
    	{
    		$booking->startDate = Date::parse($params['start_date']);
    	}
    	if (isset($params['end_date']))
    	{
    		$booking->endDate = Date::parse($params['end_date']);
    	}
    	if (isset($params['client_id']))
    	{
    		$booking->clientId = intval($params['client_id']);
    	}
    	if (isset($params['adult_count']))
    	{
    		$booking->adultCount = intval($params['adult_count']);
    	}
    	if (isset($params['child_count']))
    	{
    		$booking->childCount = intval($params['child_count']);
    	}
    	if (isset($params['extra_guest_count']))
    	{
    		$booking->extraGuestCunt = intval($params['extra_guest_count']);
    	}
    	if (isset($params['discount_coupon']))
    	{
    		$booking->booking = $params['discount_coupon'];
    	}
    	if (isset($params['total_cost']))
    	{
    		$booking->totalCost = floatval($params['total_cost']);
    	}
    	if (isset($params['payment_amount']))
    	{
    		$booking->paymentAmount = floatval($params['payment_amount']);
    	}
    	if (isset($params['payment_gateway_id']))
    	{
    		$booking->paymentGatewayId = intval($params['payment_gateway_id']);
    	}
    	if (isset($params['payment_success']))
    	{
    		$booking->isPaymentSuccessful = intval($params['payment_success']) == 1;
    	}
    	if (isset($params['payment_txnid']))
    	{
    		$booking->paymentTransactionId = $params['payment_txnid'];
    	}
    	if (isset($params['paypal_email']))
    	{
    		$booking->paypalEmail = $params['paypal_email'];
    	}
    	if (isset($params['special_id']))
    	{
    		$booking->specialId = $params['special_id'];
    	}
    	if (isset($params['special_requests']))
    	{
    		$booking->specialRequests = $params['special_requests'];
    	}
    	if (isset($params['is_block']))
    	{
    		$booking->isBlocked = intval($params['is_block']) == 1;
    	}
    	if (isset($params['is_deleted']))
    	{
    		$booking->isDeleted = intval($params['is_deleted']) == 1;
    	}
    	if (isset($params['language_code']))
    	{
    		$booking->languageCode = $params['language_code'];
    	}
    	if (isset($params['invoice']))
    	{
    		$booking->invoice = $params['invoice'];
    	}    	
    	return $booking;    	
    }
    
	private static function fetchFromSqlMultiple($sql) 
    {
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Fetching multiple " .__CLASS__ . " objects for SQL: " . $sql);
		
		if ($sql == null)
		{
			$logger->LogDebug("SQL is null!");
			return null;
		}
		
        Booking::$staticErrors = array();
		$bookings = array();		
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error executing query!");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");			
		}
		while ($row = mysql_fetch_assoc($query))
		{
		    $booking = Booking::fetchFromParameters($row);
		    $bookings[] = $booking;
		}
		$logger->LogDebug("Fetched " . count($bookings) . " bookings.");
		return $bookings;		
    }
    
	private static function fetchFromSqlSingle($sql) 
    {
    	global $logger;
		$logger->LogDebug(__METHOD__ . " line: " . __LINE__);
		$logger->LogDebug("Fetching single " .__CLASS__ . " object for SQL: " . $sql);
		
		$bookings = Booking::fetchFromSqlMultiple($sql);
		if (is_null($bookings))
		{
			$logger->LogDebug("Error fetching booking!");
			return null;
		}
		
		if (sizeof($bookings) > 0)
		{
			return $bookings[0];
		}
		return null;
    }
    
	public static function fetchAllFromDb() 
    {
    	global $logger;
		$logger->LogDebug(__METHOD__ . " line: " . __LINE__);
		$logger->LogDebug("Fetching all " .__CLASS__ . " objects from the database ...");
		
		$sql = "SELECT * FROM bsi_bookings ORDER BY start_date DESC";
		return Booking::fetchFromSqlMultiple($sql);       	
    }
    
	public static function fetchForPromoCode($promoCode) 
    {
    	global $logger;
		$logger->LogDebug(__METHOD__ . " line: " . __LINE__);
		$logger->LogDebug("Fetching all " .__CLASS__ . " objects for promo code " . $promoCode . " from the database ...");
		
		$sql = "SELECT * FROM bsi_bookings WHERE TRIM(UPPER(discount_coupon)) = '" . mysql_escape_string(trim(strtoupper($promoCode))) . "' AND is_deleted = 0 ORDER BY start_date DESC";
		return Booking::fetchFromSqlMultiple($sql);    			
    }
    
	public static function fetchForClientEmail($email) 
    {
    	global $logger;
		$logger->LogDebug(__METHOD__ . " line: " . __LINE__);
		$logger->LogDebug("Fetching all " .__CLASS__ . " objects for client email " . $email . " from the database ...");
		
		$sql = "SELECT b.* FROM bsi_bookings b INNE JOIN bsi_clients c ON b.client_id = c.id WHERE TRIM(LOWER(c.email)) = '" . mysql_escape_string(trim(strtoupper($email))) . "' AND is_deleted = 0 ORDER BY start_date DESC";
		return Booking::fetchFromSqlMultiple($sql);    			
    }
    
    public static function fetchFromDb($id) 
    {           
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Fetching single " .__CLASS__ . " object for id: " . $id);
		
		if (!is_numeric($id))
		{
			$logger->LogDebug("Id: $id is not numeric!");
			return null;
		}
		
		$id = intval($id);
		$logger->LogDebug(__METHOD__ . " Numeric value is: $id");
		$sql = "SELECT * FROM bsi_bookings WHERE booking_id = $id ORDER BY start_date DESC";
		return Booking::fetchFromSqlSingle($sql);    			
    }
    
	public static function fetchFromDbCurrent() 
    {
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Fetching current " .__CLASS__ . " objects from the database ...");
		$currentDate = new Date();
		$sql = "SELECT * FROM bsi_bookings WHERE STR_TO_DATE('" . $currentDate->formatMySql() . "', '%Y-%m-%d') BETWEEN start_date AND end_date ORDER BY start_date";
		return Booking::fetchFromSqlMultiple($sql);        		
    }
    
	public static function fetchFromDbFuture() 
    {
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Fetching future " .__CLASS__ . " objects from the database ...");
		$currentDate = new Date();
		$sql = "SELECT * FROM bsi_bookings WHERE start_date > STR_TO_DATE('" . $currentDate->formatMySql() . "', '%Y-%m-%d') ORDER BY start_date";
		return Booking::fetchFromSqlMultiple($sql);    		
    }
    
	public static function fetchFromDbPast() 
    {
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Fetching future " .__CLASS__ . " objects from the database ...");
		$currentDate = new Date();
		$sql = "SELECT * FROM bsi_bookings WHERE end_date < STR_TO_DATE('" . $currentDate->formatMySql() . "', '%Y-%m-%d') ORDER BY start_date";
		return Booking::fetchFromSqlMultiple($sql);		
    }
	
	public function save($isValidated = false)
	{
		global $logger;		
		$logger->LogDebug(__METHOD__ . " Saving object ...");
		
		if (!$isValidated && !$this->isValid())
		{
			$logger->LogError(__METHOD__ . " Object is not valid!");
			$logger->LogError(__METHOD__ . " Errors:");
			$logger->LogError($this->errors);
			return false;
		}		
		
		$currentTimestamp = new DateTime();
		$currentFormattedTimestamp = $currentTimestamp->format("Y-m-d H:i:s");
		$this->languageCode = trim(strtolower($this->languageCode));
		if ($this->id == 0)
        {
            // Run INSERT
        	$logger->LogDebug(__METHOD__ . " Inserting new object ...");
            $sql = "INSERT INTO bsi_bookings (room_id, booking_time, start_date, end_date, client_id, adult_count, child_count, extra_guest_count, discount_coupon, total_cost, ";
            $sql.= "payment_amount, payment_gateway_id, payment_success, payment_txnid, paypal_email, special_id, special_requests, is_block, is_deleted, language_code, invoice) VALUES (";
            $sql.= $this->roomId . ", ";            
            $sql.= "'" . $currentFormattedTimestamp . "', ";
            $sql.= "'" . $this->startDate->formatMySql() . "', ";
            $sql.= "'" . $this->endDate->formatMySql() . "', ";
            $sql.= $this->clientId . ", ";
            $sql.= $this->adultCount . ", ";
            $sql.= $this->childCount . ", ";
            $sql.= $this->extraGuestCunt . ", ";
            $sql.= "'" . mysql_escape_string($this->promoCode) . "', ";
            $sql.= $this->totalCost . ", ";
            $sql.= $this->paymentAmount . ", ";
            $sql.= $this->paymentGatewayId . ", ";
            $sql.= ($this->isPaymentSuccessful ? "1" : "0") . ", ";
            $sql.= "'" . mysql_escape_string($this->paymentTransactionId) . "', ";
            $sql.= "'" . mysql_escape_string($this->paypalEmail) . "', ";
            $sql.= $this->specialId . ", ";
            $sql.= "'" . mysql_escape_string($this->specialRequests) . "', ";
            $sql.= ($this->isBlocked ? "1" : "0") . ", ";
            $sql.= ($this->isDeleted ? "1" : "0") . ", ";
            $sql.= "'" . mysql_escape_string($this->languageCode) . "', ";            
            $sql.= "'" . mysql_escape_string($this->invoice) . "'";
            $sql.= ")";
            $query = mysql_query($sql);
            if (!$query)
            {
                $logger->LogError("Error executing query!");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");
            } 
            $this->id = mysql_insert_id();
            $this->bookingTime = $currentFormattedTimestamp;            
        }
        else 
        {         
        	// Run UPDATE
        	$logger->LogDebug(__METHOD__ . " Updating existing object with id: $this->id ...");	    	
        	$sql = "UPDATE bsi_bookings SET ";
            $sql.= "room_id = " . $this->roomId . ", ";            
            $sql.= "booking_time = '" . $currentFormattedTimestamp . "', ";
            $sql.= "start_date = '" . $this->startDate->formatMySql() . "', ";
            $sql.= "end_date = '" . $this->endDate->formatMySql() . "', ";
            $sql.= "client_id = " . $this->clientId . ", ";
            $sql.= "adult_count = " . $this->adultCount . ", ";
            $sql.= "child_count = " . $this->childCount . ", ";
            $sql.= "extra_guest_count = " . $this->extraGuestCunt . ", ";
            $sql.= "discount_coupon = '" . mysql_escape_string($this->promoCode) . "', ";
            $sql.= "total_cost = " . $this->totalCost . ", ";
            $sql.= "payment_amount = " . $this->paymentAmount . ", ";
            $sql.= "payment_gateway_id = " . $this->paymentGatewayId . ", ";
            $sql.= "payment_success = " . ($this->isPaymentSuccessful ? "1" : "0") . ", ";
            $sql.= "payment_txnid = '" . mysql_escape_string($this->paymentTransactionId) . "', ";
            $sql.= "paypal_email = '" . mysql_escape_string($this->paypalEmail) . "', ";
            $sql.= "special_id = " . $this->specialId . ", ";
            $sql.= "special_requests = '" . mysql_escape_string($this->specialRequests) . "', ";
            $sql.= "language_code = '" . mysql_escape_string($this->languageCode) . "', ";            
            $sql.= "invoice = '" . mysql_escape_string($this->invoice) . "', ";
            $sql.= "is_block = " . ($this->isBlocked ? "1" : "0") . ", ";
            $sql.= "is_deleted = " . ($this->isDeleted ? "1" : "0");            
            $sql.= " WHERE booking_id = " . $this->id;
            $query = mysql_query($sql);
            if (!$query)
            {
                $logger->LogError("Error executing query!");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");
            }												
		}
		$logger->LogDebug(__METHOD__ . " Object saved successfully.");	
		return true;		
	}
		
	public function isValid()
	{		
		global $logger;
		$logger->LogDebug(__METHOD__ . " Checking if object is valid ...");
		
		global $systemConfiguration;
		$this->errors = array();
		
		if (!is_numeric($this->roomId) || $this->roomId == 0)
		{
			$logger->LogError(__METHOD__ . " Room id: $this->roomId is invalid");
			$this->setError("Room id is invalid");
    	}    	
    	if ($this->startDate == null)
    	{
    		$logger->LogError(__METHOD__ . " Start date is null");
    		$this->setError("Start date is invalid");
    	}
		if ($this->endDate == null)
    	{
    		$logger->LogError(__METHOD__ . " Start date is null");
    		$this->setError("End date is invalid");
    	}    	
    	if (!is_numeric($this->clientId) || $this->clientId == 0)
		{
			$logger->LogError(__METHOD__ . " Client id: $this->clientId is invalid");
			$this->setError("Client id is invalid");
    	} 
    	if (!is_numeric($this->adultCount) || $this->adultCount <= 0)
    	{
    		$logger->LogError(__METHOD__ . " Adult count must be greater than 0");
    		$this->setError("Adult count must be greater than 0");
    	}
		if (!is_numeric($this->childCount) || $this->childCount < 0)
    	{
    		$logger->LogError(__METHOD__ . " Child count must be greater or equal to 0");
    		$this->setError("Child count must be greater or equal to 0");
    	}
		if (!is_numeric($this->extraGuestCunt) || $this->extraGuestCunt < 0)
    	{
    		$logger->LogError(__METHOD__ . " Extra guest count must be greater or equal to 0");
    		$this->setError("Extra guest count must be greater or equal to 0");
    	}    	
    	if (!is_numeric($this->paymentGatewayId) || $this->paymentGatewayId == 0 )
    	{
    		$logger->LogError(__METHOD__ . " Invalid payment gateway id");
    		$this->setError("Invalid payment gateway id");
    	}

    	if (count($this->errors) > 0)
    	{
    		$logger->LogError("Object is invalid!");
    		return false;
    	}
    	$logger->LogDebug(__METHOD__ . " Object is valid.");
		return true;					
	}	
	
	private function setError($errorMessage)
	{		
		$this->errors[] = $errorMessage;
	}
}