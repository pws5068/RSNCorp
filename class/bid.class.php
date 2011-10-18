<?

class Bid extends BidFactory
{
	private $id;
	private $auctionId;
	private $bidder;
	private $amount;
	private $stamp;
	
	function __construct($params=NULL)
	{			
		if(is_array($params))
		{
			$this->setID			( $params['id']				);
			$this->setAuctionID		( $params['auctionID']		);
			$this->setBidder		( $params['bidder']		);
			$this->setAmount		( $params['amount']			);
			$this->setStamp			( $params['stamp']			);
		}
	}
	function getObjectType()
	{
		return AUCTION_OBJ;
	}
    public static function getNew($id)
    {
		return BidFactory::byID($id);
    }
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in Bid.Class~'.__LINE__);
	}
	function setAuctionID($auctionID)
	{	
		$this->auctionID = $auctionID;
	}
	function setBidder($bidder)
	{
		if(!is_object($bidder) || $bidder->getObjectType() != CUSTOMER_OBJ)
		{
			die('NonObj Customer, Bid.Class~'.__LINE__);
		}
		
		$this->bidder = $bidder;
	}
	function setAmount($amount)
	{
		$this->amount = $amount;
	}
	function setSupplier($supObj)
	{
		if(!is_object($supObj) || $supObj->getObjectType() != SUPPLIER_OBJ)
		{
			die('NonObj Supplier, Bid.Class~'.__LINE__);
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
	function setStamp($stamp)
	{	
		$this->stamp = $stamp;
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
	function getAuctionID()
	{	
		return $this->auctionID;
	}
	function getBidder()
	{
		return $this->bidder;
	}
	function getAmount()
	{
		return $this->amount;
	}
	function showThumbnail($params=NULL)
	{
		return '<img src="'.$this->getThumb().'" />';
	}
	function getStamp()
	{	
		return $this->stamp;
	}
	function getDateTime()
	{
		$date = date('F j, Y, g:i a', strtotime($this->getStamp()));
		
		return $date;
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
		if($price < ($this->getHighestBid() + BID_INCREMENT))
		{
			throw new Exception('Your bid must be higher than the current bid + $2'); 
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
		
			submitReport('Bid Creation failed. Bid.Class~'.__LINE__.' MSG: '.$e->getMessage());
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
}

abstract class BidFactory extends dbAbstraction
{
	private static $fields = array(
	
		'id' 				=> 'I.Bid_ID',
		'auctionID'			=> 'I.Auction_ID',
		'bidder'			=> 'I.Bidder_ID',
		'amount'			=> 'I.Amount',
		'stamp'				=> 'I.Bid_Time'
	);
	protected function update()
	{		
		$sql = 'UPDATE Bid SET Auction_ID=?,Bidder_ID=?, Amount=? WHERE Bid_ID=?';
		
		$params = array(	'iiii',
							$this->getAuctionID(),
							$this->getBidder(), 
							$this->getAmount(),     
							$this->getID()
						);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function insert()
	{
		$sql = 'INSERT INTO Bid(Auction_ID,Bidder_ID,Amount) VALUES(?,?,?)';
		
		$params = array(	'iii',
							$this->getAuctionID(),
							$this->getBidder()->getID(),
							$this->getAmount()
						);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function delete()
	{		
		$sql = 'DELETE FROM Bid WHERE Bid_ID=? LIMIT 1';
		
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
			
		$bids = array();
		
		$stmt->bind_result($id,$auctionID,$bidderID,$amount,$stamp);
		
		while($stmt->fetch()) 
		{	
			$bidder = Customer::getNew($bidderID);
			
			$params = array(
				'id'			=> $id,
				'auctionID'		=> $auction,
				'bidder'		=> $bidder,
				'amount'		=> $amount,
				'stamp'			=> $stamp
			);
			
			$bids[] = new Bid($params);
		}
		
		return $bids;
	}
	private static function getFields()
	{	
		$fields = implode(',',self::$fields);
		
		return $fields;
	}
	private static function getSelect()
	{
		$select = 	'SELECT '.self::getFields().' FROM Bid_History AS I ';
		
		return $select;
	}
	// Expects: itemID
	// Returns: item object or array of objects, NULL If none
	protected static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE I.Bid_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // Item object if sucessful
	}
	public function getHighestBid($auctionID)
	{
		$sql = self::getSelect() . 'WHERE I.Auction_ID=?';
		//die($sql);
		
		$params = array('i', $auctionID);
		
		$bids = self::queryDB(get_class(),$sql,$params);
		
		$temp1 = 0;
		$highBid;
		
		foreach($bids as $bid)
		{
			if( $bid->getAmount() > $temp1)
			{
				$temp1 = $bid->getAmount();
				$highBid = $bid;
			}
		}

		return $highBid;
	}
	public function onAuction($bidder, $auction, $price)
	{
		$sql = 'INSERT INTO Bid_History(Auction_ID,Bidder_ID,Amount) VALUES(?,?,?)';
		
		$params = array(	'iii',
							$auction->getID(),
							$bidder->getID(),
							$price
						);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
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
	//	$sql = 'INSERT INTO Bid_History(Bid_ID,Bidder_ID,Amount) VALUES(?,?,?)';
	//	
	//	$params = array('iii',$this->getBidID(),$user->getID(),$bidAmount);
	//					
	//	return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	//}
}

?>