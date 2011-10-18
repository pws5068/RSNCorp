<?

class Auction extends AuctionFactory
{
	private $id;
	private $seller;
	private $min;
	private $stamp;
	private $item;
	
	function __construct($params=NULL)
	{			
		if(is_array($params))
		{
			$this->setID			( $params['id']				);
			$this->setSeller		( $params['seller']			);
			$this->setMin			( $params['min']			);
			$this->setStamp			( $params['stamp']			);
			$this->setItem			( $params['item']			);
		}
	}
	function getObjectType()
	{
		return AUCTION_OBJ;
	}
    public static function getNew($id)
    {
		return AuctionFactory::byID($id);
    }
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in Auction.Class~'.__LINE__);
	}
	function setMin($min)
	{
		$this->min = $min;
	}
	function setSeller($supObj)
	{	
		$this->seller = $supObj;
	}
	function setCategory($cat)
	{
		if(is_object($cat) && $cat->getObjectType() == CATEGORY_OBJ)
			$this->category = $cat;
			
		else
			die('Error Setting Category... :( ');
	}
	function setStamp($stamp)
	{	
		$this->stamp = $stamp;
	}
	function setItem($item)
	{
		if(!is_object($item) || $item->getObjectType() != ITEM_OBJ)
		{
			die('NonObj Item, Auction.Class~'.__LINE__);
		}
		
		$this->item = $item;
	}
	function getURL()
	{
		return '/page/viewAuction.php?id='.$this->getID();
	}
	function getHyperlink($params=NULL)
	{
		return '<a href="'.$this->getURL().'">'. $this->getItem()->getTitle() . '</a>';
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
	function getSeller()
	{	
		return $this->seller;
	}
	function getMin()
	{
		return $this->min;
	}
	function showThumbnail($params=NULL)
	{
		return '<img src="'.$this->getThumb().'" />';
	}
	function getStamp()
	{	
		return $this->stamp;
	}
	function getItem()
	{
		return $this->item;
	}
	function getDateTime()
	{
		$date = date('F j, Y, g:i a', strtotime($this->getStamp()));
		
		return $date;
	}
	function getExpiration()
	{
		list($date, $time) = explode(' ', $this->stamp);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);
	
		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		$timestamp = strtotime('+ 20 days', $timestamp); 
		
		$timestamp = date('Y-m-d H:i:s', $timestamp);
		
		return $timestamp;
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
			if(is_object(Bid::getHighestBid($this->getID())) && $price <= (Bid::getHighestBid($this->getID())->getAmount()))
			{
				throw new Exception('Your bid must be higher than the current bid!'); 
			}
			if( $price <= $this->getMin())
			{
				throw new Exception('Your bid must be higher than the minimum bid!'); 
			}
		
		return Bid::onAuction($me,$this,$price);
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
		
			submitReport('Auction Creation failed. Auction.Class~'.__LINE__.' MSG: '.$e->getMessage());
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
	function isExpired()
	{
		list($date, $time) = explode(' ', $this->stamp);
		list($year, $month, $day) = explode('-', $date);
		list($hour, $minute, $second) = explode(':', $time);
	
		$timestamp = mktime($hour, $minute, $second, $month, $day, $year);
		$timestamp = strtotime('+ 20 days', $timestamp); 

		if($timestamp < time()) 
		{
   			return true;			
		}   
		else 
		{
   			return false;
		}
	}
}

abstract class AuctionFactory extends dbAbstraction
{
	private static $fields = array(
	
		'id' 				=> 'A.Auction_ID',
		'seller'			=> 'A.Seller_ID',
		'min'				=> 'A.Min_Price',
		'stamp'				=> 'A.Time_Created',
		'item'				=> 'A.Item_ID'
	);
	protected function update()
	{		
		$sql = 'UPDATE Auction SET Seller_ID=?,Min_Price=?,Item_ID=? WHERE Auction_ID=?';
		
		$params = array(	'iiii',
							$this->getSeller(),
							$this->getMin(), 
							$this->getItem(),        
							$this->getID()
						);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function insert()
	{
		$sql = 'INSERT INTO Auction(Seller_ID,Min_Price,Item_ID) VALUES(?,?,?)';
		
		$params = array(	'iii',
							$this->getSeller()->getID(),
							$this->getMin(),
							$this->getItem()->getID()
						);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function delete()
	{		
		$sql = 'DELETE FROM Auction WHERE Auction_ID=? LIMIT 1';
		
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
			
		$auctions = array();
		
		$stmt->bind_result($id,$seller,$min,$stamp,$itemID);
		
		while($stmt->fetch()) 
		{	
			
			$supplier = Supplier::getNew($supplierID);
			$item = Item::getNew($itemID);
			//die($itemID . 'item ID');
			$params = array(
				'id'			=> $id,
				'seller'		=> $supplier,
				'min'			=> $min,
				'stamp'			=> $stamp,
				'item'			=> $item
			);
			
			$auctions[] = new Auction($params);
		}
		
		return $auctions;
	}
	private static function getFields()
	{	
		$fields = implode(',',self::$fields);
		
		return $fields;
	}
	public static function getAll()
	{
		$sql = self::getSelect();
		//$params = array('i',$supplierObj->getID());
        
        return self::queryDB(get_class(),$sql); // Auction object(s) if sucessful
	}
	public static function getCurrent()     //All un-ended auctions
	{
		$auctions = self::getAll();
		
		if(sizeOf($auctions) < 1 || !is_object($auctions[0]))
			return false;
			
		foreach($auctions as $key => $auction)
		{
			if ($auction->isExpired())
			{
				unset($auctions[$key]);
			}
		}
		
		//$params = array('i',$supplierObj->getID());
        
        return $auctions; // Item object(s) if sucessful
	}
	public static function getExpired()     //All un-ended auctions
	{
		$auctions = self::getAll();
		
		if(sizeOf($auctions) < 1 || !is_object($auctions[0]))
			return false;
			
		foreach($auctions as $key => $auction)
		{
			if (!$auction->isExpired())
			{
				unset($auctions[$key]);
			}
		}
		
		//$params = array('i',$supplierObj->getID());
        
        return $auctions; // Item object(s) if sucessful
	}

	private static function getSelect()
	{
		$select = 	'SELECT ' . self::getFields() . ' FROM Auction AS A ';
		return $select;
	}
	 //Expects: itemID
	 //Returns: item object or array of objects, NULL If none
	protected static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE A.Auction_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // Item object if sucessful
	}
	
	//protected static function byCategory($catObj)
	//{
	//	$sql = self::getSelect() . 'WHERE I.Category_ID=?';
	//	
	//	$params = array('i',$catObj->getID());
    //    
    //    return self::queryDB(get_class(),$sql,$params); // Item object(s) if sucessful
	//}
	//protected function bid($user,$bidAmount)
	//{
	//	$sql = 'INSERT INTO Bid_History(Auction_ID,Bidder_ID,Amount) VALUES(?,?,?)';
	//	
	//	$params = array('iii',$this->getAuctionID(),$user->getID(),$bidAmount);
	//					
	//	return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	//}
}

?>