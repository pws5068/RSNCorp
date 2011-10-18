<?

class SubCategory extends SubCategoryFactory
{
	private $id;
	private $title;
	private $cid;
	
	function __construct($params=NULL)
	{	
		if(is_array($params))
		{
			$this->setID			( $params['id']				);
			$this->setTitle			( $params['title']			);
			$this->setCategoryID	( $params['cID']			);
		}
	}
	function getObjectType()
	{
		return SUB_CATEGORY_OBJ;
	}
    public static function getNew($id)
    {
   		return SubCategoryFactory::byID($id);
    }
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in SubCategory.Class~'.__LINE__);
	}
	function setTitle($title)
	{	
		$this->title = $title;
	}
	function setCategoryID($cid)
	{
		$this->cid = (int)$cid;
	}
	function setThumb($fName)
	{
		$this->thumb = $fName;
	}
	function setSupplierID($supID)
	{
		$this->supplierID = (int)$supID;
	}
	function setSubCategoryID($catID)
	{
		$this->subCategoryID = (int)$catID;
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
	function getCategoryID()
	{
		return $this->cid;
	}
	function getURL()
	{
		return '/page/viewSubCategory.php?id='.$this->getID();
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
		return Item::getBySubCategory($this);
	}
	function getByCategory($cat)
	{
		if(!is_object($cat))
			die("NonObj on SubCat.Class~".__LINE__);
			
		return parent::byCategory($cat);
	}
	function getSubSubCategories()
	{
		return SubSubCategory::getBySubCategory($this);
	}
}

abstract class SubCategoryFactory extends dbAbstraction
{
	/* Subcategory_ID	Category_ID	Title */
	private static $fields = array(
	
		'id' 				=> 'S.SubCategory_ID',
		'cID'				=> 'S.Category_ID',
		'title'				=> 'S.Title'
	);
		
	// Purpose: Build Array of Subcategories
	// Expects: iDB stmt object from an SubCategory SELECT query
	// Returns: SubCategory Object(s) or NULL		
	protected function build($stmt)
	{
		if(!is_object($stmt))
			return false;
			
		$sCategories = array();
		
		$stmt->bind_result($id,$cID,$title);
		
		while($stmt->fetch()) 
		{	
			$params = array(
				'id'			=> $id,
				'cID'			=> $cID,
				'title'			=> $title,
			);
			
			$sCategories[] = new SubCategory($params);
		}
		
		return $sCategories;
	}
	private static function getFields()
	{	
		$fields = implode(',',self::$fields);
		
		return $fields;
	}
	private static function getSelect()
	{
		$select = 	'SELECT '.self::getFields().' FROM Subcategory AS S ';
		
		return $select;
	}
	// Expects: subCategoryID
	// Returns: subCategory object or array of objects, NULL If none
	public static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE S.Subcategory_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // SubCategory object(s) if sucessful
	}
	public static function byCategory($cat)
	{
		if(!is_object($cat))
			die('NonObj Cat, SubCat.class~'.__LINE__);
			
		$sql = self::getSelect() . 'WHERE S.Category_ID=?';
		
		$params = array('i',$cat->getID());
		
		return self::queryDB(get_class(),$sql,$params);
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
	public static function byItem($item)
	{
		$sql = self::getSelect() . 'INNER JOIN (SELECT SS.Subcategory_ID, SS.Subsubcategory_ID, Itm.Item_ID
            									FROM  Item Itm
            									INNER JOIN Subsubcategory SS
           										ON Itm.Subsubcategory_ID = SS.Subsubcategory_ID) T1            
									ON T1.Subcategory_ID = S.Subcategory_ID 
									WHERE T1.Item_ID = ?';
				
        $params = array('i',$item->getID());
        
        return self::queryDB(get_class(),$sql,$params,true);
	}
}

?>