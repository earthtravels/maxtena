<?php
class Content
{	
    public $id = 0;    
    public $title = "";
    public $contents = null;    
    public $isVisible = false;          
	
    public $errors = array();	
    public static $staticErrors = array();

    public function __construct()
    {		
		$this->contents = new LocalizedText("contents_");
    }    
	
    public static function fetchFromParameters($params) 
    {
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Creating new " . __CLASS__ . " object from parameters ...");
		$logger->LogDebug("Parameters are:");
		$logger->LogDebug($params);
		
		$content = new Content();
		if (isset($params['id']) && is_numeric($params['id']))
		{
		    $content->id = intval($params['id']);
		}
    	if (isset($params['cont_title']))
		{
		    $content->title = trim($params['cont_title']);
		}		
		$content->contents = LocalizedText::fetchFromParameters($params, "contents_");
    	if (isset($params['status']))
		{
		    $content->isVisible = strtoupper(trim($params['status'])) == "Y";
		}    	
		return $content;
    } 
    
	private static function fetchFromSqlMultiple($sql) 
    {				
    	global $logger;		
		$logger->LogDebug(__METHOD__ . " Fetching multiple " .__CLASS__ . " objects for SQL: " . $sql);
		
    	$contents = array();		
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogFatal("Error executing query!");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		    die("There was an error connecting to the database. Please try your request again or contact the system administrator.");			
		}
		while ($row = mysql_fetch_assoc($query))
		{
		    $contents[] = Content::fetchFromParameters($row);				    
		}
		$logger->LogDebug("Fetched " . count($contents) . " contents.");
		return $contents;		
    }

	public static function fetchFromDbAllActive() 
    {		
	    global $logger;    		
    	$contents = array();		
		$sql = "SELECT * FROM bsi_contents WHERE status = 'Y' ORDER BY id";
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogFatal("Error executing query: $sql");
			$logger->LogFatal("Database error: " . mysql_errno() . ". Message: " . mysql_error());
		    die("Database error: " . mysql_errno() . ". Message: " . mysql_error());			
		}
		while ($row = mysql_fetch_assoc($query))
		{
		    $contents[] = Content::fetchFromParameters($row);				    
		}
		return $contents;		
    }    
    
	public static function fetchFromDbActiveForId($id) 
    {		
    	Content::$staticErrors = array();				
    	if (!is_numeric($id))
    	{
    		Content::$staticErrors[] = "Id: $id is not a number";
    		return null;	
    	}
    	$contents = array();
		$sql = "SELECT * FROM bsi_contents WHERE status = 'Y' AND id = $id";
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
		    return Content::fetchFromParameters($row);		    
		}
		else
		{
			Content::$staticErrors[] = "Page with id: $id does not exist";
			return null;
		}					
    }  

	public static function fetchAllFromDb() 
    {				
    	$contents = array();		
		$sql = "SELECT * FROM bsi_contents ORDER BY cont_title";
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
		    $contents[] = Content::fetchFromParameters($row);				    
		}
		return $contents;		
    }
    
	public static function fetchFromDbForId($id) 
    {		
    	Content::$staticErrors = array();				
    	if (!is_numeric($id))
    	{
    		Content::$staticErrors[] = "Id: $id is not a number";
    		return null;	
    	}
    	
		$sql = "SELECT * FROM bsi_contents WHERE id = $id";
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
		    return Content::fetchFromParameters($row);		    
		}
		else
		{
			Content::$staticErrors[] = "Page with id: $id does not exist";
			return null;
		}					
    }
    
    
	public static function fetchFromDbForName($name) 
    {    
    	Content::$staticErrors = array();
		$sql = "SELECT * FROM bsi_contents WHERE cont_title = '" . mysql_escape_string($name) . "'";
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
		    return Content::fetchFromParameters($row);		    
		}
		else
		{
			Content::$staticErrors[] = "Page with name: $name does not exist";
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
    	if (strlen(trim($this->title)) == 0)		
		{
			$this->setError("Title cannot be empty");
		}		
    	if ($this->contents->areAnyValuesEmpty())
		{
			$this->setError("Contents text cannot be empty");
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
	    
//	    $this->fileName = trim($this->fileName);
//	    $this->url = trim($this->url);        
		if ($this->id == 0)
        {
            // Run INSERT
            $sql = "INSERT INTO bsi_contents (cont_title, ";            
            $sql.= $this->contents->getMySqlFields(false);
            $sql.= "status) VALUES (";            
            $sql.= "'" . mysql_escape_string($this->title) . "', ";
            $sql.= $this->contents->getMySqlValues(false);                        
            $sql.= $this->isVisible ? "'Y', " : "'N')";            
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
	    	$sql = "UPDATE bsi_contents SET ";
            $sql.= "cont_title = '" . mysql_escape_string($this->title) . "', ";
            $sql.= $this->contents->getMySqlValuesForSet(false);                        
            $sql.= "status = " . ($this->isVisible ? "'Y'" : "'N'");            	                       
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
        Content::$staticErrors[] = $errorMessage;
    }
}