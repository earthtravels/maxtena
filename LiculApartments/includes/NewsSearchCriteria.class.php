<?php
require_once ("SystemConfiguration.class.php");

class NewsSearchCriteria
{  		
	public $id = 0;
	public $categoryId = 0;
	public $monthNumber = 0;
	public $yearNumber = 0;
	public $keywords = "";	
	public $page = 1;
	public $totalPages = 0;
	
	private static $titleFieldPrefix = "title_";
	private static $contentsFieldPrefix = "contents_";
	
	private static $titleField = "";
	private static $contentsField = "";
	
	public $errors = array();	
	
	public function __construct($languageCode = null)
	{
		if ($languageCode == null)
		{
			$defaultLanguage = Language::fetchDefaultLangauge();
			$languageCode = $defaultLanguage->languageCode;
		}
		$languageCode = strtolower(trim($languageCode));
		$titleField = NewsSearchCriteria::$titleFieldPrefix . $languageCode;
		$contentsField = NewsSearchCriteria::$contentsFieldPrefix . $languageCode;			
	}	
	
	public static function fetchFromParameters($params) 
	{
	    $searchCriteria = new NewsSearchCriteria();
		if (isset($params['id']))
		{
			$searchCriteria->id = intval($params['id']);
		}
		if (isset($params['category_id']))
		{
			$searchCriteria->categoryId = intval($params['category_id']);
		}		
		if (isset($params['month']))
		{
			$searchCriteria->monthNumber = intval($params['month']);
		}		
		if (isset($params['year']))
		{
			$searchCriteria->yearNumber = intval($params['year']);
		}
		if (isset($params['keywords']))
		{
			$searchCriteria->keywords = urldecode($params['keywords']);
		}
		if (isset($params['page']))
		{
			$searchCriteria->page = intval($params['page']);
		}
		return $searchCriteria;			
	}	
	
	public function runSearch()
	{
		global $systemConfiguration;
		global $logger;
		
		$newsPosts = array();
		$sql = "SELECT * FROM bsi_news_posts ";
		$where = "";
		if ($this->id > 0)
		{
			$where.= "id = " . $this->id; 
		}
		else if ($this->categoryId > 0)
		{
			$where.= "category_id = " . $this->categoryId;
		}
		else if ($this->monthNumber > 0 && $this->yearNumber >0)
		{
			$where.= "CAST(DATE_FORMAT(date_posted, '%Y') as UNSIGNED) = " . $this->yearNumber . " AND CAST(DATE_FORMAT(date_posted,'%m') as UNSIGNED) = " . $this->monthNumber;
		}
		else if (strlen(trim($this->keywords)) > 0)
		{
			$keywords = str_ireplace(" ", "%", $this->keywords);
			$where.= NewsSearchCriteria::$titleField . " LIKE '%" . mysql_escape_string($keywords) . "%' OR " . NewsSearchCriteria::$contentsField . " LIKE '%" . mysql_escape_string($keywords) . "%'";
		}
		
		if ($where != "")
		{
			$where = " WHERE " . $where;
			$sql.= $where;
		}
		$sql.= " ORDER BY date_posted DESC ";
		
		$postsPerPage = $systemConfiguration->getNewsItemsPerPage();
		$count = NewsPost::count($where);
		$this->totalPages = max(ceil($count/$postsPerPage), 1);				
		if ($this->page < 1)
		{
			$this->page = 1;
		}
		else if ($this->page > $this->totalPages)
		{
			$this->page = $this->totalPages;
		}
				
		
		$sql.= "  LIMIT " . ($this->page - 1) * $postsPerPage . "," .$postsPerPage;
		$query = mysql_query($sql);
		if (!$query)
		{
			$logger->LogError("Error returning news posts for page $page.");
			$logger->LogError("Error: " . mysql_errno() . ". Error message: " . mysql_error());
			$logger->LogError("SQL: $sql");
			die ("Error: " . mysql_errno() . ". Error message: " . mysql_error());			
		}
		
		while ($row = mysql_fetch_assoc($query))
		{
			$newsPost = NewsPost::fetchFromParameters($row, true);
			$newsPosts[] = $newsPost;
		}
		return $newsPosts;
	}	
	
		
	private function setError($errorMessage)
	{
		$this->errors[sizeof($this->errors)] = $errorMessage;
	}
}