<?

class Category extends CategoryFactory
{
	private $id;
	private $title;
	
	function __construct($params=NULL)
	{			
		if(is_array($params))
		{
			$this->setID			( $params['id']				);
			$this->setTitle			( $params['title']			);
		}
	}
	function getObjectType()
	{
		return CATEGORY_OBJ;
	}
    public static function getNew($id)
    {
		return CategoryFactory::byID($id);
    }
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in Category.Class~'.__LINE__);
	}
	function setTitle($title)
	{	
		$this->title = $title;
	}
	function setThumb($fName)
	{
		$this->thumb = $fName;
	}
	function setSupplierID($supID)
	{
		$this->supplierID = (int)$supID;
	}
	function setCategoryID($catID)
	{
		$this->categoryID = (int)$catID;
	}
	function setDescription($descr)
	{	
		$this->description = $descr;
	}
	
	/*******************************************
		Section: GET METHODS
	*******************************************/
	function getID()
	{
		return $this->id;
	}
	function getTitle()
	{	
		return $this->title;
	}
	function getURL()
	{
		return '/page/viewCategory.php?id='.$this->getID();
	}
	function getHyperlink($params=NULL)
	{
		return '<a href="'.$this->getURL().'">'.$this->getTitle().'</a>';
	}
	public static function getAll($lowerLim=NULL,$perPage=NULL)
	{
		return parent::all($lowerLim,$perPage);
	}
	function getAllItems()
	{
		return Item::getByCategory($this);
	}
	function getSubCategories()
	{
		$subCats = SubCategory::getByCategory($this);
		
		//die('  ==== ' . print_r($subCats));
		
		return $subCats;
	}
}

abstract class CategoryFactory extends dbAbstraction
{
	private static $fields = array(
	
		'id' 				=> 'C.Category_ID',
		'title'				=> 'C.Title',
	);
		
	// Purpose: Build Array of Categories
	// Expects: iDB stmt object from an Category SELECT query
	// Returns: Category Object(s) or NULL		
	protected function build($stmt)
	{
		if(!is_object($stmt))
			return false;
			
		$categories = array();
		
		$stmt->bind_result($id,$title);
		
		while($stmt->fetch()) 
		{	
			$params = array(
				'id'			=> $id,
				'title'			=> $title,
			);
			
			$categories[] = new Category($params);
		}
		
		return $categories;
	}
	private static function getFields()
	{	
		$fields = implode(',',self::$fields);
		
		return $fields;
	}
	private static function getSelect()
	{
		$select = 	'SELECT '.self::getFields().' FROM Category AS C ';
		
		return $select;
	}
	// Expects: categoryID
	// Returns: category object or array of objects, NULL If none
	public static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE C.Category_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // Category object(s) if sucessful
	}
	public static function all($lowerLim,$perPage)
	{
		$sql = self::getSelect();
    	
    	if(isset($lowerLim) && isset($perPage))
    	{
    		$sql .= 'LIMIT ?,?';
    		$params = array('ii',$lowerLim,$perPage);
    		
    		return self::queryDB(get_class(),$sql,$params);
    	}
    	
        return self::queryDB(get_class(),$sql);
	}
}

?>