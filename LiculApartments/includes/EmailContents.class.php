<?php
class EmailContents
{ 
    public $id = 0;
    public $emailCode = "";
    public $emailSubject = "";
    public $emailText = "";      
	
    public $errors = array();	
    public static $staticErrors = array();

    public function __construct()
    {
		$this->emailSubject = new LocalizedText("email_subject_");
		$this->emailText = new LocalizedText("email_text_");
    }    
	
    public static function fetchFromParameters($params) 
    {
		$emailContents = new EmailContents();
		if (isset($params['id']) && is_numeric($params['id']))
		{
		    $emailContents->id = intval($params['id']);
		}
    	if (isset($params['email_code']))
		{
		    $emailContents->emailCode = $params['email_code'];
		}
		$emailContents->emailSubject = LocalizedText::fetchFromParameters($params, "email_subject_");
		$emailContents->emailText = LocalizedText::fetchFromParameters($params, "email_text_");			        
		return $emailContents;
    }    
    
    public static function fetchFromDb($id) 
    {                                   
		EmailContents::$staticErrors = array();
		if (!is_numeric($id))
		{
		    EmailContents::setStaticError("Id " . $id . " is not numeric.");			
		    return null;
		}
				
		$sql = "SELECT * FROM bsi_email_contents WHERE id = " . $id;
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
		    $emailContents = EmailContents::fetchFromParameters($row);			
		    return $emailContents;
		}
		else
		{
		    EmailContents::setStaticError("No email contents with id " . $id . " could be found.");
		    return NULL;
		}		
    }

    public static function fetchFromDbForCode($emailContentsCode) 
    {                                   
		EmailContents::$staticErrors = array();
		$sql = "SELECT * FROM bsi_email_contents WHERE trim(lower(email_code)) = '" . strtolower(trim(mysql_escape_string($emailContentsCode))) . "'";
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
		    $emailContents = EmailContents::fetchFromParameters($row);			
		    return $emailContents;
		}
		else
		{
		    EmailContents::setStaticError("Email contents with code: " . $emailContentsCode . " does not exist.");
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
		if ($this->emailSubject->areAnyValuesEmpty())
		{
			$this->setError("Email subject cannot be empty");
		}		
    	if ($this->emailText->areAnyValuesEmpty())
		{
			$this->setError("Email text cannot be empty");
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

        $this->emailCode = strtolower(trim($this->emailCode));
		if ($this->id == 0)
        {
            // Run INSERT
            $sql = "INSERT INTO bsi_email_contents (email_code";
            $sql.= $this->emailSubject->getMySqlFields(true);
            $sql.= $this->emailText->getMySqlFields(true);
            $sql.= " VALUES (";
            $sql.= "'" . mysql_escape_string($this->emailCode) . "'";
            $sql.= $this->emailSubject->getMySqlValues(true);
            $sql.= $this->emailText->getMySqlValues(true);                        
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
	    	$sql = "UPDATE bsi_email_contents SET ";	    	
            $sql.= "email_code = '" . mysql_escape_string($this->emailCode) . "'";
            $sql.= $this->emailSubject->getMySqlValuesForSet(true);
            $sql.= $this->emailText->getMySqlValuesForSet(true);                        
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
    
    private function setError($errorMessage)
    {
		$this->errors[] = $errorMessage;
    }

    private static function setStaticError($errorMessage)
    {
        EmailContents::$staticErrors[] = $errorMessage;
    }
}