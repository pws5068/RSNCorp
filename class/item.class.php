<?

class Item extends ItemFactory
{
	private $id;
	private $title;
	private $thumb;
	private $supplier;
	private $category;
	private $description;
	private $price;
	private $quantity;
	
	function __construct($params=NULL)
	{			
		if(is_array($params))
		{
			$this->setID			( $params['id']				);
			$this->setTitle			( $params['title']			);
			$this->setThumb			( $params['thumb']			);
			$this->setSupplier		( $params['supplier']		);
			$this->setCategory		( $params['category']		);
			$this->setDescription	( $params['description']	);
			$this->setPrice			( $params['price']			);
			$this->setQuantity		( $params['quantity']		);
		}
	}
	function getObjectType()
	{
		return ITEM_OBJ;
	}
    public static function getNew($id)
    {
		return ItemFactory::byID($id);
    }
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in Item.Class~'.__LINE__);
	}
	function setTitle($title)
	{	
		$this->title = $title;
	}
	function setThumb($fName)
	{
		$this->thumb = $fName;
	}
	function setSupplier($supObj)
	{
		if(!is_object($supObj) || $supObj->getObjectType() != SUPPLIER_OBJ)
		{
			die('NonObj Supplier, Item.Class~'.__LINE__);
		}
		
		$this->supplier = $supObj;
	}
	function setCategory($cat)
	{
		if(is_object($cat) && $cat->getObjectType() == CATEGORY_OBJ)
			$this->category = $cat;
			
		else
			die('Error Setting Category... :( ');
	}
	function setDescription($descr)
	{	
		$this->description = $descr;
	}
	function setPrice($price)
	{
		$this->price = (float)$price;
	}
	function setQuantity($quantity)
	{
		$this->quantity = $quantity;
	}
	function getURL()
	{
		return '/page/viewItem.php?id='.$this->getID();
	}
	function getHyperlink($params=NULL)
	{
		return '<a href="'.$this->getURL().'">'.$this->getTitle().'</a>';
	}
	/*
	function setQuantity($quantity)
	{	
		$this->quantity = (int)$quantity;
	}
	private function setHighestBid($bidPrice)
	{
		$this->highestBid = (int)$bidPrice;
	}
	function setTimeStamp($stamp)
	{
		$this->timeStamp = $stamp;
	}
	*/
	
	/*******************************************
		Section: GET METHODS
	*******************************************/
	function canEdit()
	{
		$owner = $this->getOwner();
		
		if((is_object($owner) && $owner->getID() == MY_ID) || Security::isModerator())
			return true;
			
		return false;
	}
	function getID()
	{
		return $this->id;
	}
	function getTitle()
	{	
		return $this->title;
	}
	function getQuantity()
	{	
		if(is_null($this->quantity))
		{
			return 1;
		}
		return (int) $this->quantity;
	}
	function getThumb()
	{
		return $this->thumb;
	}
	// Smarty doesn't allow creating associative arrays... 
	// hence this shortcut
	function showThumbnailSmall()
	{
		$args = array('width'=>'20%','height'=>'20%');
		
		return $this->showThumbnail($args);
	}
	function showThumbnail($params=NULL)
	{
		if(isset($params))
		{
			if(isset($params['height']) && isset($params['width']))
			{
				$extra = "height=\"{$params['height']}\" width=\"{$params['width']}\"";
			}
		}
		
		return '<img src="'.$this->getThumb().'" '.$extra.' />';
	}
	function getDescription()
	{	
		return $this->description;
	}
	function getPrice()
	{
		return $this->price;
	}
	function getSupplier()
	{
		return $this->supplier;
	}
	function getCategory()
	{
		return $this->category;
	}
	function getSubCategory()
	{
		return SubCategory::byItem($this);
	}
	function getSubSubCategory()
	{
		return SubSubCategory::byItem($this);
	}
	/*
	function getQuantity()
	{	
		return $this->quantity;
	}
	function getHighestBid()
	{
		return $this->highestBid;
	}
	function getTimeStamp()
	{
		return $this->timeStamp;
	}
	*/
	/*******************************************
		Section: ACTION METHODS
	*******************************************/
	function bid($price)
	{
		global $me;
		
		if(!is_object($me))
		{
			throw new Exception('You must be logged in to place a bid');
		}
		if($price < $this->getHighestBid())
		{
			throw new Exception('Your bid must be higher than the current bid'); 
		}
		
		$this->setHighestBid($price);
		
		return parent::bid($me,$price);
	}
	function update()
	{
		if($this->canEdit())
			return parent::update();
	}
	private function readyForInsert()
	{
		if((int)$this->getID() > 0)
			throw new Exception('Failed Insert Non-New Item, Item.Class `~'.__LINE__);
		
		return true;
	}
	function commit()
	{
		if((int)$this->getID() > 0)
			return $this->update();
		
		try {
			$flag = $this->readyForInsert();
		}
		catch(Exception $e) {
		
			submitReport('Item Creation failed. Item.Class~'.__LINE__.' MSG: '.$e->getMessage());
			return false;
		}
		
		return parent::insert();
	}
	function delete()
	{
		if($this->canEdit())
			return parent::delete();
	}
	public static function getByCategory($cat)
	{
		if(!is_object($cat) || $cat->getObjectType() != CATEGORY_OBJ)
		{
			die("Error, NonObj Category in Item.Class~".__LINE__);
		}
		
		return parent::byCategory($cat);
	}
	public static function getBySubCategory($cat)
	{
		if(!is_object($cat))
		{
			die("Error, NonObj SubCategory in Item.Class~".__LINE__);
		}
		
		return parent::bySubCategory($cat);
	}
	public static function getBySubSubCategory($cat)
	{
		if(!is_object($cat))
		{
			die("Error, NonObj SSCategory in Item.Class~".__LINE__);
		}
		
		return parent::bySubSubCategory($cat);
	}
	public static function getRandom($limit=25)
	{
		return parent::random($limit);
	}
	public static function getBySupplier($supplier)
	{
		if(!is_object($supplier) || $supplier->getObjectType() != SUPPLIER_OBJ)
		{
			die("Error, NonObj Supplier in Item.Class~".__LINE__);
		}
		
		return parent::bySupplier($supplier);
	}
	public static function getByCart($cart)
	{
		if(!is_object($cart) || $cart->getObjectType() != CART_OBJ)
		{
			die("Error, NonObj CART in Item.Class~".__LINE__);
		}
		
		return parent::byCart($cart);
	}
	public static function search($str,$minPrice=NULL,$maxPrice=NULL)
	{
		return parent::bySearch($str,$minPrice,$maxPrice);
	}
}

abstract class ItemFactory extends dbAbstraction
{
	/* Itm.Item_ID,Itm.Title,Price,Supplier_ID,T1.Subsubcategory_ID, T1.SubsubTitle, T1.Subcategory_ID, T1.SubTitle,T1.Category_ID, T1.Title
	*/
	private static $fields = array(
	
		'id' 				=> 'Itm.Item_ID',
		'title'				=> 'Itm.Title',
		'thumb'				=> 'Itm.Thumbnail',
		'description'		=> 'Itm.Description',
		'price'				=> 'Itm.Price',
		'supplierID'		=> 'Itm.Supplier_ID',
		'subCatID'			=> 'Subcategory_ID',
		'subSubCatID'		=> 'T1.Subsubcategory_ID',
		'categoryID'		=> 'T1.Category_ID'
	);
	protected function update()
	{		
		$sql = 'UPDATE Item SET Title=?,Thumb=?,Description=?,Price=? WHERE Item_ID=?';
		
		$params = array(	'sssdi',
							$this->getTitle(),
							$this->getThumb(),
							$this->getDescription(),
							$this->getPrice(),
							$this->getID()
						);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function insert()
	{
		$sql = 'INSERT INTO Item(Subsubcategory_ID,Supplier_ID,Title,Thumbnail,Description,Price) VALUES(?,?,?,?)';
		
		$params = array(	'iisssf',
							$this->getCategoryID(),
							$this->getSupplierID(),
							$this->getTitle(),
							$this->getThumb(),
							$this->getDescription(),
							$this->getPrice()
						);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function delete()
	{		
		$sql = 'DELETE FROM Item WHERE Item_ID=? LIMIT 1';
		
		$params = array('i',$this->getID());
						
		return self::insertUpdate(get_class(),$sql,$params);
	}
				
	// Purpose: Build Array of Items
	// Expects: iDB stmt object from an Item SELECT query
	// Returns: Item Object(s) or NULL		
	protected function build($stmt)
	{
		if(!is_object($stmt))
			return false;
			
		$items = array();
		
		$stmt->bind_result($id,$title,$thumb,$descr,$price,$supplierID,$subCatID,$subsubCatID,$categoryID);
		
		while($stmt->fetch()) 
		{	
			$category = Category::getNew($categoryID);
			$supplier = Supplier::getNew($supplierID);
			
			$params = array(
				'id'			=> $id,
				'category'		=> $category,
				'supplier'		=> $supplier,
				'title'			=> $title,
				'thumb'			=> $thumb,
				'description'	=> $descr,
				'price'			=> $price
			);
			
			$items[] = new Item($params);
		}
		
		return $items;
	}
	private static function getFields()
	{	
		$fields = implode(',',self::$fields);
		
		return $fields;
	}
	private static function getSelect()
	{				
	
		$select =	"SELECT ".self::getFields()." FROM Item Itm
					INNER JOIN  (
						SELECT Subsub.Subsubcategory_ID, Subsub.Title AS 'SubsubTitle',
						T2.Subcategory_ID,T2.SubTitle,T2.Category_ID, T2.Title
             			FROM Subsubcategory Subsub
             			INNER JOIN (
             				SELECT Temp1.Subcategory_ID, Temp1.Title AS 'SubTitle',Temp2.Category_ID, Temp2.Title
                         	FROM Subcategory Temp1
                         	INNER JOIN Category Temp2
                         	ON Temp1.Category_ID = Temp2.Category_ID
                         ) T2
             			ON Subsub.Subcategory_ID = T2.Subcategory_ID) T1
						ON Itm.Subsubcategory_ID = T1.Subsubcategory_ID ";
		return $select;
	}
	// Expects: itemID
	// Returns: item object or array of objects, NULL If none
	protected static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE Item_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // Item object if sucessful
	}
	protected static function bySupplier($supplierObj)
	{
		$sql = self::getSelect() . 'WHERE Supplier_ID=?';
		
		$params = array('i',$supplierObj->getID());
        
        return self::queryDB(get_class(),$sql,$params); // Item object(s) if sucessful
	}
	protected static function byCart($cartObj)
	{
		$sql = self::getSelect() . ", Cart_Link C WHERE Itm.Item_ID=C.Item_ID AND C.Cart_ID=?";
		
		$params = array('i',$cartObj->getID());
        
        return self::queryDB(get_class(),$sql,$params); // Item object(s) if sucessful
	}
	protected static function byAuction($auctionID)
	{
		$sql = self::getSelect() . 'WHERE Item_ID=?';
		
		$params = array('i',$auctionID);
		
		return self::queryDB(get_class(),$sql,$params); //Item object if sucessful
	}
	protected static function bySearch($searchString,$minPrice=NULL,$maxPrice=NULL)
	{
		$sql = self::getSelect() . "WHERE (Itm.Title LIKE CONCAT('%',?,'%') OR 
											Itm.DESCRIPTION LIKE CONCAT('%',?,'%'))";
		
		$params = array('ss',$searchString,$searchString);
		
		if($minPrice >= 0 && $maxPrice > $minPrice)
		{
			$sql .= ' AND Itm.Price BETWEEN ? AND ?';
		
			$params[0] .= 'ii';
			$params[] = $minPrice;
			$params[] = $maxPrice;
		}

		//print_r($params); die($sql);
        
        return self::queryDB(get_class(),$sql,$params); // Item object(s) if sucessful
	}
	protected static function byCategory($catObj)
	{
		$sql = self::getSelect() . 'WHERE Category_ID=?';
		
		$params = array('i',$catObj->getID());
        
        return self::queryDB(get_class(),$sql,$params); // Item object(s) if sucessful
	}
	protected static function bySubCategory($catObj)
	{
		$sql = self::getSelect() . 'WHERE T1.Subcategory_ID=?';
		
		$params = array('i',$catObj->getID());
        
        return self::queryDB(get_class(),$sql,$params); // Item object(s) if sucessful
	}
	protected static function bySubSubCategory($catObj)
	{
		$sql = self::getSelect() . 'WHERE T1.Subsubcategory_ID=?';
		
		$params = array('i',$catObj->getID());
        
        return self::queryDB(get_class(),$sql,$params); // Item object(s) if sucessful
	}
	protected static function random($limit=25)
	{
		$sql = self::getSelect() . 'ORDER BY RAND() LIMIT ?';
		
		$params = array('i',$limit);
        
        return self::queryDB(get_class(),$sql,$params); // Item object(s) if sucessful
	}
	public static function getOthersBought($itemID)
	{
		$sql = 'SELECT DISTINCT CTL.Item_ID
				FROM Cart_Link CTL
				INNER JOIN Cart CT
				ON CTL.Cart_ID = CT.Cart_ID
				WHERE CT.Customer_ID IN
 					(SELECT Crt.Customer_ID
  					FROM Cart Crt
  					INNER JOIN Cart_Link CrtLnk
  					ON Crt.Cart_ID = CrtLnk.Cart_ID
  					WHERE CrtLnk.Item_ID = ?)
				AND CTL.Item_ID != ?
				LIMIT 9';
				
		$itemIDAry = array();
		$itemAry = array();
		
		$iDB = new mysqliDB();
		$stmt = $iDB->prepare($sql);
		$stmt->bind_param('ii', $itemID, $itemID);
		$stmt->execute();
		$stmt->bind_result($itemID);
		
		while($stmt->fetch())
		{
			$itemIDAry[] = $itemID;
		}
		
		$stmt->close();
		$iDB->close();
		
		if(sizeOf($itemIDAry) > 0)
		{
			foreach($itemIDAry as $itemID)
			{
				$itemAry[] = Item::getNew($itemID);
			}
		}
			
		return $itemAry; 
	}
	
	protected function bid($user,$bidAmount)
	{
		$sql = 'INSERT INTO Bid_History(Auction_ID,Bidder_ID,Amount) VALUES(?,?,?)';
		
		$params = array('iii',$this->getAuctionID(),$user->getID(),$bidAmount);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
}

?>