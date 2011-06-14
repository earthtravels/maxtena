<?php
class NewsCategory
{  
	public $id = 0;
	public $title = null;	
	private static $titlePrefix = "title_";	
	
	public $errors = array();
	public static $staticErrors = array();

	public function __construct()
	{
		$this->title = new LocalizedText(NewsCategory::$titlePrefix);
	}
	
	public static function fetchFromParameters($params) 
	{
		$newsCategory = new NewsCategory();
		if (isset($params['id']) && is_numeric($params['id']))
		{
			$newsCategory->id = intval($params['id']);
		}
		$newsCategory->title = LocalizedText::fetchFromParameters($params, NewsCategory::$titlePrefix);
		return $newsCategory;
	}
	
	public static function fetchFromDb($id) 
	{
		global $logger;
		NewsCategory::$staticErrors = array();
		if (!is_numeric($id))
		{
			$logger->LogError("Id " . $id . " is not numeric.");
			NewsCategory::$staticErrors[] = "Id " . $id . " is not numeric.";
			return null;
		}
		
		$id = intval($id);
		$sql = "SELECT * FROM bsi_news_categories WHERE id = " . $id;
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		if ($row = mysql_fetch_assoc($query))
		{
			$newsCategory = NewsCategory::fetchFromParameters($row);
			return $newsCategory;			
		}
		else
		{
			$logger->LogError("No news category with id " . $id . " could be found.");			
			return null;
		}		
	}
	
	public static function fetchAllFromDb($languageCode = null) 
	{
		global $logger;
		NewsCategory::$staticErrors = array();
		
		if ($languageCode == null)
		{
			$defaultLanguage = Language::fetchDefaultLangauge();
			$languageCode = $defaultLanguage->languageCode;
		}
		
		$newsCategories = array();
		$sql = "SELECT * FROM bsi_news_categories ORDER BY title_" . strtolower(trim($languageCode));
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$newsCategory = NewsCategory::fetchFromParameters($row);
			$newsCategories[] = $newsCategory;			
		}				
		return $newsCategories;
	}
	
	public static function fetchFromDbAllUsed($languageCode = null) 
	{
		global $logger;
		NewsCategory::$staticErrors = array();
		
		if ($languageCode == null)
		{
			$defaultLanguage = Language::fetchDefaultLangauge();
			$languageCode = $defaultLanguage->languageCode;
		}
		
		$newsCategories = array();
		$sql = "SELECT DISTINCT  nc.* FROM bsi_news_categories as nc INNER JOIN bsi_news_posts np ON nc.id = np.category_id ORDER BY nc.title_" . strtolower(trim($languageCode));
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());
		}
		while ($row = mysql_fetch_assoc($query))
		{
			$newsCategory = NewsCategory::fetchFromParameters($row);
			$newsCategories[] = $newsCategory;			
		}				
		return $newsCategories;
	}

	public function isValid()
	{
		$this->errors = array();		
		if ($this->title->areAnyValuesEmpty())
		{
			$this->errors[] = "Title cannot be empty";
		}
		return sizeof($this->errors) == 0;
	}
	
	public function save($isValidated = false)
	{
		global $logger;
		if (!$isValidated && !$this->isValid())
		{
			return false;
		}
		
		$currentDate = new Date();
		if ($this->id == 0)
		{
			// Run INSERT			
			$sql = "INSERT INTO bsi_news_categories (";
			$sql.= $this->title->getMySqlFields(false);
			$sql.= "last_updated_date) VALUES (";
			$sql.= $this->title->getMySqlValues(false);
			$sql.= "STR_TO_DATE('" . $currentDate->formatMySql() . "', '%Y-%m-%d') )";		  			  	
			if (!mysql_query($sql))
			{				
				$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
				$logger->LogError("SQL: $sql");
				die('Error: ' . mysql_error());
			} 
			$this->id = mysql_insert_id();
		}
		else 
		{
			// Run UPDATE
			$sql = "UPDATE bsi_news_categories SET ";
			$sql.= $this->title->getMySqlValuesForSet(false);
			$sql.= "last_updated_date = STR_TO_DATE('" . $currentDate->formatMySql() . "', '%Y-%m-%d')";						
		  	$sql.= " WHERE id = " . $this->id;			 		  	
			if (!mysql_query($sql))
			{
				$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
				$logger->LogError("SQL: $sql");
				die('Error: ' . mysql_error());
			}							
		}	
		return true;	
	}
	
	public static function delete($id)	
	{
		global $logger;
		NewsCategory::$staticErrors = array();
		if (is_numeric($id))
		{
			// Run DELETE
			$sql = "DELETE FROM bsi_news_categories WHERE id = " . $id;			 		  	
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
			NewsCategory::$staticErrors[] = "Id is not numeric";
			return false;
		}
	}
}
?>