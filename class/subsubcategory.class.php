<?

class SubSubCategory extends SubSubCategoryFactory
{
	private $id;
	private $title;
	
	function __construct($params=NULL)
	{			
		if(is_array($params))
		{
			$this->setID			( $params['id']				);
			$this->setTitle			( $params['title']			);
			$this->setSubSSubID		( $params['ssID']			);
		}
	}
	function getObjectType()
	{
		return SUB_CATEGORY_OBJ;
	}
    public static function getNew($id)
    {
		return SubSubCategoryFactory::byID($id);
    }
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in SubSubCategory.Class~'.__LINE__);
	}
	function setTitle($title)
	{	
		$this->title = $title;
	}
	function setSubSSubID($id)
	{
		$this->ssID = (int)$id;
	}
	function setThumb($fName)
	{
		$this->thumb = $fName;
	}
	function setSupplierID($supID)
	{
		$this->supplierID = (int)$supID;
	}
	function setSubSubCategoryID($catID)
	{
		$this->subSubCategoryID = (int)$catID;
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
	function getSubSSubID()
	{
		return $this->ssID;
	}
	function getURL()
	{
		return '/page/viewSubSubCategory.php?id='.$this->getID();
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
		return Item::getBySubSubCategory($this);
	}
	function getBySubCategory($cat)
	{
		return parent::bySubCategory($cat);
	}
}

abstract class SubSubCategoryFactory extends dbAbstraction
{
	/* Subsubcategory_ID	Subcategory_ID	Title */
	private static $fields = array(
	
		'id' 				=> 'SS.SubSubCategory_ID',
		'ssID'				=> 'SS.SubCategory_ID',
		'title'				=> 'SS.Title',
	);
		
	// Purpose: Build Array of SubSubcategories
	// Expects: iDB stmt object from an SubSubCategory SELECT query
	// Returns: SubSubCategory Object(s) or NULL		
	protected function build($stmt)
	{
		if(!is_object($stmt))
			return false;
			
		$ssCategories = array();
		
		$stmt->bind_result($id,$ssID,$title);
		
		while($stmt->fetch()) 
		{	
			$params = array(
				'id'			=> $id,
				'ssID'			=> $ssID,
				'title'			=> $title,
			);
			
			$ssCategories[] = new SubSubCategory($params);
		}
		
		return $ssCategories;
	}
	private static function getFields()
	{	
		$fields = implode(',',self::$fields);
		
		return $fields;
	}
	private static function getSelect()
	{
		$select = 	'SELECT '.self::getFields().' FROM Subsubcategory AS SS ';
		
		return $select;
	}
	// Expects: subSubCategoryID
	// Returns: subSubCategory object or array of objects, NULL If none
	public static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE SS.Subsubcategory_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // SubSubCategory object(s) if sucessful
	}
	public static function bySubCategory($sCat)
	{
		if(!is_object($sCat))
			die('NonObj Cat, SubSubCat.class~'.__LINE__);
			
		$sql = self::getSelect() . 'WHERE SS.Subcategory_ID=?';
		
		$params = array('i',$sCat->getID());
		
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
		$sql = self::getSelect() . 'INNER JOIN Item Itm
									ON Itm.Subsubcategory_ID = SS.Subsubcategory_ID
									WHERE Itm.Item_ID = ?';
    	
        $params = array('i',$item->getID());
        
        return self::queryDB(get_class(),$sql,$params,true);
	}
}

?>