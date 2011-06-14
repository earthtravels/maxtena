<?php
class PageContents
{	
    public $id = 0;
    public $parentId = 0;
    public $title = null;
    public $contents = null;
    public $isVisible = false;
    public $fileName = "";
    public $url = "";
    public $templateType = 0;
    public $displayOrder = 0;
              
	
    public $errors = array();	
    public static $staticErrors = array();

    public function __construct()
    {
		$this->title = new LocalizedText("title");
		$this->contents = new LocalizedText("contents_");
    }    
    
    public function getUrl()
    {
    	global $systemConfiguration;
    	
    	if (trim($this->url) == "#")
    	{
    		$url = "#";
    		return $url;
    	}
    	else if (strlen($this->url) != 0)
    	{
    		$url = $systemConfiguration->getSiteAddress() . $this->url;
    		return $url;
    	}
    	else if ($this->templateType == 1)
    	{
    		$url = $systemConfiguration->getSiteAddress() . "page2.php?id=" . $this->id;
    		return $url;    		 
    	}    	
    	else
    	{
    		$url = $systemConfiguration->getSiteAddress() . "page.php?id=" . $this->id;
    		return $url;    		 
    	}
    }    
	
    public static function fetchFromParameters($params) 
    {    	
		$pageContents = new PageContents();
		if (isset($params['id']) && is_numeric($params['id']))
		{
		    $pageContents->id = intval($params['id']);
		}
		$pageContents->title = LocalizedText::fetchFromParameters($params, "title_");
		$pageContents->contents = LocalizedText::fetchFromParameters($params, "contents_");
    	if (isset($params['status']))
		{
		    $pageContents->isVisible = strtoupper(trim($params['status'])) == "Y";
		}
    	if (isset($params['parent_id']) && is_numeric($params['parent_id']))
		{
		    $pageContents->parentId = intval($params['parent_id']);
		}
    	if (isset($params['file']))
		{
		    $pageContents->file = trim($params['file']);
		}
    	if (isset($params['url']))
		{
		    $pageContents->url = trim($params['url']);
		}					        
    	if (isset($params['ord']))
		{
		    $pageContents->displayOrder = intval($params['ord']);
		}
    	if (isset($params['template_type']))
		{
		    $pageContents->templateType = intval($params['template_type']);
		}
		return $pageContents;
    } 

	public static function fetchFromDbAllActive() 
    {				
    	$pages = array();		
		$sql = "SELECT * FROM bsi_site_contents WHERE status = 'Y' ORDER BY ord";
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
		    $pages[] = PageContents::fetchFromParameters($row);				    
		}
		return $pages;		
    }	
    
    public static function fetchFromDbActiveRoot() 
    {
    	return PageContents::fetchFromDbActiveForParent(0);
    }
    
	public static function fetchFromDbActiveForParent($parentId) 
    {		
    	PageContents::$staticErrors = array();				
    	if (!is_numeric($parentId))
    	{
    		PageContents::$staticErrors[] = "Parent id: $parentId is not a number";
    		return null;	
    	}
    	$pages = array();
		$sql = "SELECT * FROM bsi_site_contents WHERE status = 'Y' AND parent_id = $parentId ORDER BY ord";
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
		    $pages[] = PageContents::fetchFromParameters($row);		    
		}
		return $pages;			
    }
    
	public static function fetchFromDbActiveForId($id) 
    {		
    	PageContents::$staticErrors = array();				
    	if (!is_numeric($id))
    	{
    		PageContents::$staticErrors[] = "Id: $id is not a number";
    		return null;	
    	}
    	$pages = array();
		$sql = "SELECT * FROM bsi_site_contents WHERE status = 'Y' AND id = $id ORDER BY ord";
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
		    return PageContents::fetchFromParameters($row);		    
		}
		else
		{
			PageContents::$staticErrors[] = "Page with id: $id does not exist";
			return null;
		}					
    }
    
	public static function fetchFromDb($id) 
    {		
    	PageContents::$staticErrors = array();				
    	if (!is_numeric($id))
    	{
    		PageContents::$staticErrors[] = "Id: $id is not a number";
    		return null;	
    	}
    	$pages = array();
		$sql = "SELECT * FROM bsi_site_contents WHERE id = $id ORDER BY ord";
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
		    return PageContents::fetchFromParameters($row);		    
		}
		else
		{
			PageContents::$staticErrors[] = "Page with id: $id does not exist";
			return null;
		}					
    }
    
    public static function fetchFromDbForUrl($url) 
    {						
		$sql = "SELECT * FROM bsi_site_contents WHERE url = '" . mysql_escape_string($url) . "' ORDER BY ord" ;
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
		    $pageContents = PageContents::fetchFromParameters($row);			
		    return $pageContents;
		}
		else
		{
		    PageContents::setStaticError("No page contents with url " . $url . " could be found.");
		    return NULL;
		}		
    }       

    public function isValid()
    {
		$this->errors = array();		
		if (!is_int($this->id))
		{
		    $this->setError("Id is invalid");
		}
		if (strlen($this->url) == 0 && $this->contents->areAnyValuesEmpty())		
		{
			$this->setError("Contents cannot be empty when no URL is specified");
		}
    	if ($this->title->areAnyValuesEmpty())		
		{
			$this->setError("Title cannot be empty");
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
	    
	    $this->fileName = trim($this->fileName);
	    $this->url = trim($this->url);        
		if ($this->id == 0)
        {
            // Run INSERT
            $sql = "INSERT INTO bsi_site_contents (";
            $sql.= $this->title->getMySqlFields(false);
            $sql.= $this->contents->getMySqlFields(false);
            $sql.= "status, file, url, parent_id, ord, template_type) VALUES (";            
            $sql.= $this->title->getMySqlValues(false);
            $sql.= $this->contents->getMySqlValues(false);                        
            $sql.= $this->isVisible ? "'Y', " : "'N', ";
            $sql.= "'" . mysql_escape_string($this->fileName) . "', ";
            $sql.= "'" . mysql_escape_string($this->url) . "', ";
            $sql.= $this->parentId .", ";
            $sql.= $this->displayOrder . ", ";
            $sql.= $this->templateType . ")";
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
	    	$sql = "UPDATE bsi_site_contents SET ";
            $sql.= $this->title->getMySqlValuesForSet(false);
            $sql.= $this->contents->getMySqlValuesForSet(false);                        
            $sql.= "status = " . ($this->isVisible ? "'Y', " : "'N', ");
            $sql.= "file = '" . mysql_escape_string($this->fileName) . "', ";
            $sql.= "url = '" . mysql_escape_string($this->url) . "', ";
            $sql.= "parent_id = " . $this->parentId .", ";
            $sql.= "ord = " . $this->displayOrder . ", ";
            $sql.= "template_type = " . $this->templateType;	                       
            $sql.= " WHERE id = " . $this->id;
            global $logger;
	    	$logger->LogDebug("Updating using SQL:");
	    	$logger->LogDebug($sql);	    	
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
        PageContents::$staticErrors[] = $errorMessage;
    }
}