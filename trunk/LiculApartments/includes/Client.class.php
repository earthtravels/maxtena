<?php
class Client
{  	
	public $id = 0;
	public $firstName = "";
	public $middleName = "";
	public $lastName = "";
	public $streetAddress = "";
	public $city = "";	
	public $state = "";
	public $zip = "";
	public $country = "";
	public $phone = "";
	public $email = "";
	public $ipAddress = "";
	
	public static $EMAIL_REGEX = '/^[A-Za-z0-9\._\%\+\-]+@[A-Za-z0-9\.\-]+\.[A-Za-z]{2,4}$/';
	
	public $errors = array();
    public static $staticErrors = array();
	
	public static function fetchFromParameters($params) 
	{
		global $logger;		
		$logger->LogDebug(__METHOD__ . " Creating new " . __CLASS__ . " object from parameters ...");
		$logger->LogDebug("Parameters are:");
		$logger->LogDebug($params);
		
		$client = new Client();
		if (isset($params['id']) && is_numeric($params['id']))
		{
			$client->id = intval($params['id']);
		}
		if (isset($params['first_name']))
		{
			$client->firstName = $params['first_name'];
		}		
		if (isset($params['middle_name']))
		{
			$client->middleName = $params['middle_name'];
		}
		if (isset($params['last_name']))
		{
			$client->lastName = $params['last_name'];
		}			
		if (isset($params['street_address']))
		{
			$client->streetAddress = $params['street_address'];
		}		
		if (isset($params['city']))
		{
			$client->city = $params['city'];
		}		
		if (isset($params['state']))
		{
			$client->state = $params['state'];
		}		
		if (isset($params['zip']))
		{
			$client->zip = $params['zip'];
		}		
		if (isset($params['country']))
		{
			$client->country = $params['country'];
		}		
		if (isset($params['phone']))
		{
			$client->phone = $params['phone'];
		}		
		if (isset($params['email']))
		{
			$client->email = $params['email'];
		}		
		if (isset($params['ip']))
		{
			$client->ipAddress = $params['ip'];
		}
        else
        {
            $client->ipAddress = $_SERVER['REMOTE_ADDR'];
        }
		return $client;
	}
	
	private static function fetchFromSqlMultiple($sql) 
    {
    	global $logger;
		$logger->LogDebug(__METHOD__ . " line: " . __LINE__);
		$logger->LogDebug("Fetching multiple " .__CLASS__ . " objects for SQL: " . $sql);
		
		if ($sql == null)
		{
			$logger->LogDebug("SQL is null!");
			return null;
		}
		
        Client::$staticErrors = array();
		$clients = array();		
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error executing query!");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");			
		}
		while ($row = mysql_fetch_assoc($query))
		{
		    $client = Client::fetchFromParameters($row);
		    $clients[] = $client;
		}
		$logger->LogDebug("Fetched " . count($clients) . " clients.");
		return $clients;		
    }
    
	private static function fetchFromSqlSingle($sql) 
    {
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Fetching single " .__CLASS__ . " object for SQL: " . $sql);
		
		$clients = Client::fetchFromSqlMultiple($sql);
		if (is_null($clients))
		{
			$logger->LogDebug("Error fetching client!");
			return null;
		}
		
		if (count($clients) > 0)
		{
			return $clients[0];
		}
		else
		{
			$logger->LogWarn(__METHOD__ . " No clients exist.");
		}
		return null;
    }
	
	public static function fetchAllFromDb() 
	{				
		global $logger;
		$logger->LogDebug(__METHOD__ . " line: " . __LINE__);
		$logger->LogDebug("Fetching all " .__CLASS__ . " objects from the database ...");
		
		$sql = "SELECT * FROM bsi_clients ORDER BY first_name" ;
		return Client::fetchFromSqlMultiple($sql);				
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
		$sql = "SELECT * FROM bsi_clients WHERE id = " . $id;
		return Client::fetchFromSqlSingle($sql);				
	}
	
	public static function fetchFromDbForEmail($email) 
	{
		global $logger;
		$logger->LogDebug(__METHOD__ . " line: " . __LINE__);
		$logger->LogDebug("Fetching objects " .__CLASS__ . " objects from the database for email $email");
		
		$sql = "SELECT * FROM bsi_clients WHERE TRIM(LOWER(email)) = TRIM(LOWER('" . mysql_escape_string($email) . "'))";
		return Client::fetchFromSqlSingle($sql);		
	}

	public function isValid()
	{
		global $logger;
		$logger->LogDebug(__METHOD__ . " Checking if object is valid ...");
		
		$this->errors = array();		
		if (strlen(trim($this->firstName)) == 0)
		{
			$logger->LogDebug(__METHOD__ . " First name cannot be empty.");
			$this->setError("First name cannot be empty.");
		}
		if (strlen(trim($this->lastName)) == 0)
		{
			$logger->LogDebug(__METHOD__ . " Last name cannot be empty.");
			$this->setError("Last name cannot be empty.");
		}
		if (strlen(trim($this->streetAddress)) == 0)
		{
			$logger->LogDebug(__METHOD__ . " Street name cannot be empty.");
			$this->setError("Street name cannot be empty.");
		}
		if (strlen(trim($this->city)) == 0)
		{
			$logger->LogDebug(__METHOD__ . " City name cannot be empty.");
			$this->setError("City name cannot be empty.");
		}
		if (strlen(trim($this->country)) == 0)
		{
			$logger->LogDebug(__METHOD__ . " Country name cannot be empty.");
			$this->setError("Country name cannot be empty.");
		}
		if (strlen(trim($this->phone)) == 0)
		{
			$logger->LogDebug(__METHOD__ . " Phone name cannot be empty.");
			$this->setError("Phone name cannot be empty.");
		}
		if (strlen(trim($this->email)) == 0)
		{
			$logger->LogDebug(__METHOD__ . " Email name cannot be empty.");
			$this->setError("Email name cannot be empty.");
		}
		else if (!preg_match(Client::$EMAIL_REGEX, $this->email))
		{
			$logger->LogDebug(__METHOD__ . " Email is invalid.");
			$this->setError("Email is invalid.");
		}
		if (strlen(trim($this->ipAddress)) == 0)
		{
			$logger->LogDebug(__METHOD__ . " IP address name cannot be empty.");
			$this->setError("IP address name cannot be empty.");
		}	
		
		if (count($this->errors) > 0)
    	{
    		$logger->LogError("Object is invalid!");
    		return false;
    	}
    	$logger->LogDebug(__METHOD__ . " Object is valid.");
		return true;					
	}
	
	public function save($isValidated=false)
	{
		global $logger;		
		$logger->LogDebug(__METHOD__ . " Saving object ...");
		
		$this->errors = array();
		if (!$isValidated && !$this->isValid())
		{
			$logger->LogError(__METHOD__ . " Object is not valid!");
			$logger->LogError(__METHOD__ . " Errors:");
			$logger->LogError($this->errors);
			return false;
		}
				
		$this->firstName = trim($this->firstName);
		$this->middleName = trim($this->middleName);
		$this->lastName = trim($this->lastName);
		$this->streetAddress = trim($this->streetAddress);
		$this->city = trim($this->city);	
		$this->state = trim($this->state);
		$this->zip = trim($this->zip);
		$this->country = trim($this->country);
		$this->phone = trim($this->phone);
		$this->email = strtolower(trim($this->email));
		$this->ipAddress = trim($this->ipAddress);
		
		
		if ($this->id == 0)
		{
			// Run INSERT
			$logger->LogDebug(__METHOD__ . " Inserting new object ...");
			$sql = "INSERT INTO bsi_clients (first_name, middle_name, last_name, street_address,  city, state, zip, country, phone, email, ip) VALUES (";
		  	$sql = $sql . "'" . mysql_escape_string($this->firstName) .  "', '" . mysql_escape_string($this->middleName) . "', '" . mysql_escape_string($this->lastName) . "', '" . mysql_escape_string($this->streetAddress) . "', ";
		  	$sql = $sql . "'" . mysql_escape_string($this->city) .  "', '" . mysql_escape_string($this->state) . "', '" . mysql_escape_string($this->zip) . "', '" . mysql_escape_string($this->country) . "', ";
		  	$sql = $sql . "'" . mysql_escape_string($this->phone) .  "', '" . mysql_escape_string($this->email) . "', '" . mysql_escape_string($this->ipAddress) . "'";
		  	$sql = $sql . ")";		  	
			if (!mysql_query($sql))
			{
				$logger->LogError("Error executing query: " . $sql);
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");
			} 
			$this->id = mysql_insert_id();
		}
		else 
		{
			// Run UPDATE
			$logger->LogDebug(__METHOD__ . " Updating existing object with id: $this->id ...");
			$sql = "UPDATE bsi_clients ";
			$sql = $sql . "SET first_name = '" . mysql_escape_string($this->firstName) . "'";
			$sql = $sql . ", middle_name = '" . mysql_escape_string($this->middleName) . "'";
			$sql = $sql . ", last_name = '" . mysql_escape_string($this->lastName) . "'";
			$sql = $sql . ", street_address = '" . mysql_escape_string($this->streetAddress) . "'";			
			$sql = $sql . ", city = '" . mysql_escape_string($this->city) . "'";
			$sql = $sql . ", state = '" . mysql_escape_string($this->state) . "'";
			$sql = $sql . ", zip = '" . mysql_escape_string($this->zip) . "'";
			$sql = $sql . ", country = '" . mysql_escape_string($this->country) . "'";			
			$sql = $sql . ", phone = '" . mysql_escape_string($this->phone) . "'";
			$sql = $sql . ", email = '" . mysql_escape_string($this->email) . "'";
			$sql = $sql . ", ip = '" . mysql_escape_string($this->ipAddress) . "'";			
		  	$sql = $sql . " WHERE id = " . $this->id;			 		  	
			if (!mysql_query($sql))
			{
				$logger->LogError("Error executing query: " . $sql);
				$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
			    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");
			}	
			if ($logger->shouldLogDebug())
			{
				$logger->LogDebug("Updated: " . mysql_affected_rows() . " row(s).");
			}						
		}	
		$logger->LogDebug(__METHOD__ . " Save complete.");
		return true;	
	}
	
	public static function delete($id)	
	{		
		global $logger;		
		$logger->LogDebug(__METHOD__ . " Deleting object with id: $id");
		
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_clients WHERE id = " . $id;			 		  	
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

    private static function setStaticError($errorMessage)
    {
        Client::$staticErrors[] = $errorMessage;
    }

	private function setError($errorMessage)
	{
		$this->errors[] = $errorMessage;
	}
}