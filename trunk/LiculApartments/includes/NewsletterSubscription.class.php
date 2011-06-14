<?php
class NewsletterSubscription
{
	public $id = 0;
	public $email = 0;    
	public $isActive = false;
	public $subscriptionDate = "";	
	
	public $errors = array();
    public static $staticErrors = array();
    
    public function __construct()
    {
    	$this->subscriptionDate = new Date();
    }
	
	public static function fetchFromParameters($params) 
	{
        $newsletterSubscription = new NewsletterSubscription();
		if (isset($params['id']) && is_numeric($params['id']))
		{
			$newsletterSubscription->id = intval($params['id']);
		}        
		if (isset($params['email']))
		{
			$newsletterSubscription->email = trim($params['email']);
		}		
		if (isset($params['is_active']))
		{
			$newsletterSubscription->isActive = intval($params['is_active']) == 1;
		}		
		if (isset($params['subscription_date']))		
		{
			$newsletterSubscription->subscriptionDate = Date::parse($params['subscription_date']);			
		}	
		return $newsletterSubscription;		
	}

    public static function fetchAllFromDb() 
	{
        $newsletterSubscriptions = array();
        NewsletterSubscription::$staticErrors = array();
				
		$sql = "SELECT * FROM bsi_newsletter_subscriptions ORDER BY subscription_date DESC";
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
            $newsletterSubscription = NewsletterSubscription::fetchFromParameters($row);
            $newsletterSubscriptions[] = $newsletterSubscription;
		}
		return $newsletterSubscriptions;
	}
	
	public static function fetchAllFromDbForPage($pageNumber) 
	{
		if (!is_numeric($pageNumber) || $pageNumber <= 0)
		{
			NewsletterSubscription::$staticErrors[] = "Invalid page number: $pageNumber";
			return null;
		}
		
		global $systemConfiguration;
		$pageLimit = intval($systemConfiguration->getAdminItemsPerPage());
		$start = $pageLimit * $pageNumber;
		
        $newsletterSubscriptions = array();
        NewsletterSubscription::$staticErrors = array();
				
		$sql = "SELECT * FROM bsi_newsletter_subscriptions LIMIT $start, $pageLimit ORDER BY subscription_date DESC";
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
            $newsletterSubscription = NewsletterSubscription::fetchFromParameters($row);
            $newsletterSubscriptions[] = $newsletterSubscription;
		}
		return $newsletterSubscriptions;
	}
	
	public static function fetchFromDb($id) 
	{
        NewsletterSubscription::$staticErrors = array();
		if (!is_numeric($id))
		{
            array_push(NewsletterSubscription::$staticErrors, "Id : $id is not numeric");
			return null;
		}
		
		$sql = "SELECT * FROM bsi_newsletter_subscriptions WHERE id = $id";
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
            $newsletterSubscription = NewsletterSubscription::fetchFromParameters($row);
            if ($newsletterSubscription == null)
            {
                return null;
            }
			return $newsletterSubscription;
		}
		else
		{
            array_push(NewsletterSubscription::$staticErrors, "No newsletter subscription with id: $id exists");
			return null;
		}		
	}
	
	public static function fetchFromDbForEmail($email) 
	{
        NewsletterSubscription::$staticErrors = array();		
		$sql = "SELECT * FROM bsi_newsletter_subscriptions WHERE email = '" . mysql_escape_string(strtolower(trim($email))) . "'";
		$query = mysql_query($sql);
        if (!$query)
        {
            die ("Error: " . mysql_error());
        }
		if ($row = mysql_fetch_assoc($query))
		{
            $newsletterSubscription = NewsletterSubscription::fetchFromParameters($row);            
			return $newsletterSubscription;
		}
		else
		{
            array_push(NewsletterSubscription::$staticErrors, "No newsletter subscription with email: $email exists");
			return null;
		}		
	}

	public function isValid()
	{
		$this->errors = array();        
		if (!preg_match(Client::$EMAIL_REGEX, $this->email))
		{
			$this->setError(BOOKING_DETAILS_EMAIL_INVALID);
		}
		else
		{
			$sub = NewsletterSubscription::fetchFromDbForEmail($this->email);
			if ($sub != null && $sub->id != $this->id)
			{
				$this->setError("Subscription with email: " . $this->email . " already exists");
			}
		}		
        return sizeof($this->errors) == 0;
	}
	
	public function save()
	{
		if (!$this->isValid())
		{
			return false;
		}
		
		$this->email = strtolower(trim($this->email));
		if ($this->id == 0)
		{
			// Run INSERT
			$sql = "INSERT INTO bsi_newsletter_subscriptions (";            
            $sql.= "email, is_active, subscription_date) VALUES (";
            $sql.= "'" . mysql_escape_string($this->email) . "', ";
            $sql.= ($this->isActive ? "1" : "0") . ", ";
            $sql.= "'" . $this->subscriptionDate->formatMySql() . "'";
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
			$sql = "UPDATE bsi_newsletter_subscriptions SET ";			
            $sql.= "email = '" . mysql_escape_string($this->email) . "', ";
            $sql.= "is_active = " . ($this->isActive ? "1" : "0") . ", ";
            $sql.= "subscription_date = '" . $this->subscriptionDate->formatMySql() . "'";
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
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_newsletter_subscriptions WHERE id = " . $id;			 		  	
			if (!mysql_query($sql))
			{
				die('Error: ' . mysql_error());
			}
			return true;
		}
		return false;
	}
	
	public static function deactivate($id)	
	{
		if (is_numeric($id))
		{
			// Run UPDATE
			$sql = "UPDATE bsi_newsletter_subscriptions SET is_active = 0 WHERE id = " . $id;			 		  	
			if (!mysql_query($sql))
			{
				die('Error: ' . mysql_error());
			}
			return true;
		}
		return false;
	}
	
	public static function deleteByEmail($email)	
	{
		// Run DELETE
		$sql = "DELETE FROM bsi_newsletter_subscriptions WHERE email = '" . mysql_escape_string($email) . "'";			 		  	
		if (!mysql_query($sql))
		{
			die('Error: ' . mysql_error());
		}		
	}

    private function setError($errorMessage)
	{
		$this->errors[] = $errorMessage;
	}
}