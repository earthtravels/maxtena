<?php
class PromoCode
{
    public $id = 0;
    public $promoCode = "";
    public $discountAmount = 0;
    public $minAmount = 0;
    public $minNights = 0;
    //public $percentage = 0;
    public $isPercentage = false;
    public $customerEmail = "";
    public $category = 0; // 0 - all customers; 1 - all existing customers; 2 - specific customer
    public $expirationDate = null;
    public $isReusable = false;           
	
    public $errors = array();	
    public static $staticErrors = array();

    public function __construct()
    {		
    }
	
    public static function fetchFromParameters($params) 
    {
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Creating new " . __CLASS__ . " object from parameters ...");
		$logger->LogDebug("Parameters are:");
		$logger->LogDebug($params);
		
		$promoCode = new PromoCode();
		if (isset($params['id']) && is_numeric($params['id']))
		{
		    $promoCode->id = intval($params['id']);
		}
		if (isset($params['promo_code']))
		{
		    $promoCode->promoCode = $params['promo_code'];
		}		
		if (isset($params['discount']))
		{
		    $promoCode->discountAmount = floatval($params['discount']);
		}		  
	    if (isset($params['min_amount']))
		{
		    $promoCode->minAmount = floatval($params['min_amount']);
		}
    	if (isset($params['min_nights']))
		{
		    $promoCode->minNights = intval($params['min_nights']);
		}		
	    if (isset($params['is_percentage']))
		{
		    $promoCode->isPercentage = intval($params['is_percentage']) == 1;
		}
	    if (isset($params['customer_email']))
		{
		    $promoCode->customerEmail = $params['customer_email'];
		}
	    if (isset($params['exp_date']))
		{
		    $promoCode->expirationDate = Date::parse($params['exp_date']);
		}
	    if (isset($params['reuse_promo']))
		{
		    $promoCode->isReusable = intval($params['reuse_promo']) == 1;
		}
    	if (isset($params['promo_category']))
		{
		    $promoCode->category = intval($params['promo_category']);
		}
		return $promoCode;
    }
    
    public static function fetchAllFromDb() 
    {
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Fetching all " . __CLASS__ . " objects from database ...");    	
        
		$promoCodes = array();
		$sql = "SELECT * FROM bsi_promo_codes ORDER BY promo_code";
		$query = mysql_query($sql);
		if (!$query)
		{
		    $logger->LogError("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");				
		}
		while ($row = mysql_fetch_assoc($query))
		{
		    $promoCode = PromoCode::fetchFromParameters($row);
		    $promoCodes[] = $promoCode;
		}
		$logger->LogDebug("Fetched " . count($promoCodes) . " promo codes.");
		return $promoCodes;		
    }
    
    public static function fetchFromDb($id) 
    {               
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Fetching " . __CLASS__ . " object from database for id: $id");		
		                    
		PromoCode::$staticErrors = array();
		if (!is_numeric($id))
		{
			$logger->LogError("Id: $id is not numeric.");
		    PromoCode::setStaticError("Id " . $id . " is not numeric.");			
		    return null;
		}
				
		$sql = "SELECT * FROM bsi_promo_codes WHERE id = $id";
		$query = mysql_query($sql);
		if (!$query)
		{
		    $logger->LogError("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");				
		}
		if ($row = mysql_fetch_assoc($query))
		{
		    $promoCode = PromoCode::fetchFromParameters($row);			
		    return $promoCode;
		}
		else
		{
			$logger->LogWarn("There is no promo code for id: $id");
		    PromoCode::setStaticError("No promo code with id " . $id . " could be found.");
		    return NULL;
		}		
    }

    public static function fetchFromDbForCode($promoCode) 
    {                 
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Fetching " . __CLASS__ . " object from database for promo code: $promoCode");	
		                  
		PromoCode::$staticErrors = array();
		$sql = "SELECT * FROM bsi_promo_codes WHERE UPPER(TRIM(promo_code)) = '" . strtoupper(trim(mysql_escape_string($promoCode))) . "'";
		$query = mysql_query($sql);
		if (!$query)
		{
		    $logger->LogError("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");				
		}
		if ($row = mysql_fetch_assoc($query))
		{
		    $promoCode = PromoCode::fetchFromParameters($row);			
		    return $promoCode;
		}
		else
		{
			$logger->LogWarn("There is no promo code for promo code: $promoCode");
		    PromoCode::setStaticError(BOOKING_DETAILS_COUPON_INVALID);
		    return null;
		}		
    }

    public function isApplicable($amount, $email, $nightCount, &$message)
    {
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Checking if promo code is applicable to amount: $amount; email: $email; night count: $nightCount ...");
		
        $currentDate = new Date();
        if (!$this->isValid())
        {
        	$logger->LogError("Object is invalid!");
            $message = $this->errors[0];
            return false;
        }
        
        // Check if coupon has been used
        if (!$this->isReusable)
        {
        	$logger->LogDebug("Promo is not reusable. Checking if it has been used already ...");
        	$bookings = Booking::fetchForPromoCode($this->promoCode);
        	if (count($bookings) > 0)
        	{
        		$logger->LogDebug("Promo has been used on booking id: " . $bookings[0]->id);
        		$message = BOOKING_DETAILS_COUPON_USED;
        		return false;	
        	}
        }
        
        // Not enough purchases
        if ($this->minAmount > 0 && floatval($amount) < $this->minAmount)
        {
        	$logger->LogDebug("Promo has a minumum amount of $this->minAmount so it is not applicable!");
            $message = BOOKING_DETAILS_COUPON_MIN_AMT;
            return false;
        }
        
        // Expired code
        if ($this->expirationDate != null && $currentDate->compareTo($this->expirationDate) == 1)
        {
        	$logger->LogDebug("Current date is: $currentDate->format('m/d/Y') so promo has expired!");
            $message = BOOKING_DETAILS_COUPON_EXPIRED;
            return false;
        }
        
        // Minimun nights
        if ($this->minNights > 0 && $nightCount < $this->minNights)
        {
        	$logger->LogDebug("Promo has a minumum nights of $this->minNights so it is not applicable!");
        	$message = BOOKING_DETAILS_COUPON_MIN_NIGHTS_PART_1 . " " . $this->minNights . " " . BOOKING_DETAILS_COUPON_MIN_NIGHTS_PART_2;
        	return false; 
        }
        
        // Must be existing customer
    	if ($this->category == 1)
        {
        	$logger->LogDebug("Promo must be used on an existing customer.");
        	$client = Client::fetchFromDbForEmail($email);
        	if (client == null)
        	{
        		$logger->LogDebug("There is no customer with email: $email in existing customers. Promo is not applicable!");
        		$message = BOOKING_DETAILS_COUPON_WRONG_CUSTOMER;
            	return false;
        	}            
        }
		// Specific customer
        else if ($this->category == 2 && $this->customerEmail != null && strlen(trim($this->customerEmail)) > 0 && trim(strtolower($this->customerEmail)) != trim(strtolower($email)))
        {
        	$logger->LogDebug("Promo code must be used on a speciifc customer and promo customer: $this->customerEmail and booking customer: $email do not match!");
        	$message = BOOKING_DETAILS_COUPON_WRONG_CUSTOMER;
            return false;        	            
        }
	    // New customer
        else if ($this->category == 3 && $this->customerEmail != null && strlen(trim($this->customerEmail)) > 0)
        {
        	$logger->LogDebug("Promo must be used on a new customer.");
        	$bookings = Booking::fetchForClientEmail($email);
        	$client = Client::fetchFromDbForEmail(trim(strtolower($email)));
        	if (count($bookings) > 0)
        	{
        		$logger->LogDebug("Customer: $email already has ". count($bookings) . " booking(s) so promo is not applicable!");
        		$message = BOOKING_DETAILS_COUPON_ONLY_NEW_CUSTOMER;
            	return false;
        	}        	            
        }
        $logger->LogDebug("Promo is applicable!");
        return true;
    }

    public function getDiscount($amount)
    {
        if ($this->discountAmount > 0)
        {
        	if (!$this->isPercentage)
        	{
            	return min(floatval($this->discountAmount), $amount);
	        }
	        else
	        {
	            return round(min((floatval($amount) * floatval($this->discountAmount) / 100), $amount), 2);
	        }
        }
        return 0;
    }

    public function isValid()
    {
    	global $logger;
    	
		$this->errors = array();		
		if (intval($this->id) <= 0 && !is_int($this->id))
		{
		    $this->setError("Id is invalid");
		}
		if (strlen(trim($this->promoCode)) == 0)
		{
		    $this->setError("Promo code cannot be empty.");
		}
		if (floatval($this->discountAmount) <= 0)
		{
		    $this->setError("Discount amount must be greater than zero");
		}
        if (floatval($this->minAmount) < 0)
		{
	        $this->setError("Minimum amount must be greater than zero");
		}
        if ($this->isPercentage && floatval($this->discountAmount) > 100)
		{
		    $this->setError("Discount percent must be greater than zero and less than 100");
		}    	
        if (sizeof($this->errors) == 0)
        {
            $sql = "SELECT * FROM bsi_promo_codes WHERE TRIM(UPPER(promo_code)) = '" . mysql_escape_string(trim(strtoupper($this->promoCode))) . "' AND id != " . $this->id;
            $query = mysql_query($sql);
            if (!$query)
            {
                $logger->LogError("Error executing query: $sql");
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");			
            }
            else if (mysql_numrows($query) > 0)
            {
                $this->setError("Promo code already exists");
            }
        }
		return count($this->errors) == 0;					
    }
    
    public function save()
    {
		$this->errors = array();
		if (!$this->isValid())
		{
		    return false;
		}

        $this->promoCode = strtoupper(trim($this->promoCode));
        $this->customerEmail = strtolower(trim($this->customerEmail));
		if ($this->id == 0)
	        {
            // Run INSERT
            $sql = "INSERT INTO bsi_promo_codes (promo_code, discount, min_amount, min_nights, is_percentage, customer_email, exp_date, promo_category, reuse_promo) VALUES (";
            $sql.= "'" . mysql_escape_string($this->promoCode) . "', ";
            $sql.= $this->discountAmount . ", ";
            $sql.= $this->minAmount . ", ";
            $sql.= $this->minNights . ", ";
            //$sql.= ($this->isPercentage ? "1" :"0") . ", ";
            $sql.= Utilities::getMySqlValue($this->isPercentage) . ", ";
            $sql.= Utilities::getMySqlValue($this->customerEmail) . ", ";
            $sql.= Utilities::getMySqlValue($this->expirationDate) . ", ";            
            $sql.= Utilities::getMySqlValue($this->category) . ", ";
            $sql.= ($this->isReusable? "1" : "0");
            $sql.= ")";
            $query = mysql_query($sql);
            if (!$query)
            {
                die('Error: ' . mysql_error());
            } 
            $this->id = mysql_insert_id();            
        }
        else 
        {         
	    	// Run UPDATE
	    	$sql = "UPDATE bsi_promo_codes ";
            $sql.= "SET promo_code = '" . mysql_escape_string($this->promoCode) . "', ";
            $sql.= "discount = " . $this->discountAmount . ", ";
            $sql.= "min_amount = " . $this->minAmount . ", ";
            $sql.= "min_nights = " . $this->minNights . ", ";
            $sql.= "is_percentage = " . Utilities::getMySqlValue($this->isPercentage) . ", ";
            $sql.= "customer_email = " . Utilities::getMySqlValue($this->customerEmail) . ", ";
            $sql.= "exp_date = " . Utilities::getMySqlValue($this->expirationDate) . ", ";
            $sql.= "promo_category = " . Utilities::getMySqlValue($this->category) . ", ";            
            $sql.= "reuse_promo = " . Utilities::getMySqlValue($this->isReusable);
            $sql.= " WHERE id = " . $this->id;
		    if (!mysql_query($sql))
		    {
			    die('Error: ' . mysql_error());
		    }												
		}	
		return true;	
    }
    
	public static function delete($id)	
	{		
		global $logger;		
		$logger->LogDebug(__METHOD__ . " Deleting object with id: $id");
		
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_promo_codes WHERE id = " . $id;			 		  	
			if (!mysql_query($sql))
			{
				$logger->LogError("Error executing query: " . $sql);
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");
			}
			
			if ($logger->shouldLogDebug())
			{
				$logger->LogDebug("Deleted: " . mysql_affected_rows() . " row(s).");
			}
			return true;
		}
		else 
		{
			$logger->LogError(__METHOD__ . " Id: $id is not numeric!");
		}
		return false;
	}
    
    private function setError($errorMessage)
    {
	$this->errors[] = $errorMessage;
    }

    private static function setStaticError($errorMessage)
    {
        PromoCode::$staticErrors[] = $errorMessage;
    }
}