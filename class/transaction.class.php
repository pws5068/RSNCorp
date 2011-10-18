<?

class Transaction extends TransactionFactory
{
	private $id;
	private $title;
	private $address;
	private $city;
	
	function __construct($params=NULL)
	{
		if(is_array($params))
		{
			$this->setID			( $params['id']				);
			$this->setTitle			( $params['title']			);
			$this->setAddress		( $params['address']		);
			$this->setCity			( $params['city']			);
			$this->setState			( $params['state']		 	);
		}
	}
	function getObjectType()
	{
		return SUPPLIER_OBJ;
	}
    public static function getNew($id)
    {
		return TransactionFactory::byID($id);
    }
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in Transaction.Class~'.__LINE__);
	}
	function setTitle($title)
	{	
		$this->title = $title;
	}
	function setAddress($addr)
	{
		$this->address = $addr;
	}
	function setCity($city)
	{
		$this->city = $city;
	}
	function setState($state)
	{
		$this->state = $state;
	}
	function setPhone($phone)
	{
		$this->phone = $phone;
	}
	function setZip($zip)
	{
		$this->zip = (int)$zip;
	}
	function setCountry($country)
	{	
		$this->country = $country;
	}
	function setThumb($thumb)
	{	
		$this->thumb = $thumb;
	}
	function setPass($pass)
	{
		// Only set when updating pass and createing (not typical retrieval)
		$this->tempPass = $pass;
	}
	
	/*******************************************
		Section: GET METHODS
	*******************************************/
	function canEdit()
	{
		$owner = $this->getOwner();
		
		if((is_object($owner) && $owner->getID() == MY_ID) || Security::isAdmin())
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
	function getAddress()
	{
		return $this->address;
	}
	function getCity()
	{
		return $this->city;
	}
	function getState()
	{
		return $this->state;
	}
	function getPhone()
	{
		return $this->phone;
	}
	function getZip()
	{
		return $this->zip;
	}
	function getCountry()
	{	
		return $this->country;
	}
	function getThumb()
	{	
		return $this->thumb;
	}
	function showThumbnail($params=NULL)
	{
		return '<img src="'.$this->getThumb().'" />';
	}
	function getPass()
	{
		return $this->tempPass;
	}
	function getOwner()
	{
		return $this; // for Polymorphism
	}
	function getURL()
	{
		return '/page/viewTransaction.php?id='.$this->getID();
	}
	function getHyperlink($params=NULL)
	{
		return '<a href="'.$this->getURL().'">'.$this->getTitle().'</a>';
	}
	function getItems()
	{
		return Item::getByTransaction($this);
	}
	function update()
	{
		if($this->canEdit())
			return parent::update();
	}
	private function readyForInsert()
	{
		if((int)$this->getID() > 0)
			throw new Exception('Failed Insert Non-New Transaction, Transaction.Class `~'.__LINE__);
		
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
		
			submitReport('Transaction Creation failed. Transaction.Class~'.__LINE__.' MSG: '.$e->getMessage());
			return false;
		}
		
		return parent::insert();
	}
	public static function authenticate($userName,$pass)
	{
		$transaction = parent::byLogin($userName,$pass);
		
		if(!is_object($transaction)) // Login Failed
			return false;
			
        $_SESSION["MemberID"]			= $transaction->getID();
        $_SESSION["MemberAccess"] 		= $transaction->getAccess();
		
		return true;
	}
	function delete()
	{
		if($this->canEdit())
			return parent::delete();
	}
}

abstract class TransactionFactory extends dbAbstraction
{
	private static $fields = array(
	
		'id' 				=> 'T.Transaction_ID',
		'customerID'		=> 'T.Customer_ID',
		'shippingAddr'		=> 'T.Shipping_Address',
		'billingAddr'		=> 'T.Billing_Address',
		'purchaseTime'		=> 'T.Purchase_Time',
		'tracking'			=> 'T.Tracking_Number',
		'discountCode'		=> 'T.ProDeal_ID'
		
	);
	/*
		Transaction_ID	int(10)		UNSIGNED	No	None	AUTO_INCREMENT	 	 	 	 	 	 	
		Customer_ID	int(10)		UNSIGNED	No	None		 	 	 	 	 	 	
		Shipping_Address	varchar(255)	utf8_unicode_ci		No	None		 	 	 	 	 	 	
		Billing_Address	varchar(255)	utf8_unicode_ci		No	None		 	 	 	 	 	 	
		Purchase_Time	timestamp			No	CURRENT_TIMESTAMP		 	 	 	 	 	 	
		Tracking_Number	varchar(50)	utf8_unicode_ci		No	None		 	 	 	 	 	 	
		Discount_Code	varchar(50)	utf8_unicode_ci		No	None		 	 	 	 	 	 	
		Item_ID
	*/
	/*
	protected function update()
	{		
		$sql = 'UPDATE Transaction SET Title=?,Address=?,City=?,State=?,Telephone=?,Zip=?,Country=?,Thumbnail=? WHERE Transaction_ID=?';
		
		$params = array(	'sssssissi',
							$this->getTitle(),
							$this->getAddress(),
							$this->getCity(),
							$this->getState(),
							$this->getPhone(),
							$this->getZip(),
							$this->getCountry(),
							$this->getThumb(),
							$this->getID()
						);
		
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	*/
	protected function insert()
	{
		$sql = 'INSERT INTO Customer_Transaction '.
				'(Customer_ID,Supplier_Address,Billing_Address,Tracking_Number,ProDeal_ID) '.
				'VALUES(?,?,?,?,?)';
		
		$params = array(	'issis',
							$this->getTitle(),
							$this->getAddress(),
							$this->getCity(),
							$this->getState()
						);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function delete()
	{		
		$sql = 'DELETE FROM Transaction WHERE Transaction_ID=? LIMIT 1';
		
		$params = array('i',$this->getID());
						
		return self::insertUpdate(get_class(),$sql,$params);
	}
				
	// Purpose: Build Array of Transactions
	// Expects: iDB stmt object from an Transaction SELECT query
	// Returns: Transaction Object(s) or NULL		
	protected function build($stmt)
	{
		if(!is_object($stmt))
			return false;
			
		$transactions = array();
		
		$stmt->bind_result($id,$title,$address,$city,$state,$phone,$zip,$country,$thumb);
		
		while($stmt->fetch()) 
		{
			$params = array(
				'id'			=> $id,
				'title'			=> $title,
				'address'		=> $address,
				'city'			=> $city,
				'state'			=> $state,
				'phone'			=> $phone,
				'zip'			=> $zip,
				'country'		=> $country,
				'thumb'			=> $thumb
			);
			
			$transactions[] = new Transaction($params);
		}
		
		return $transactions;
	}
	private static function getFields()
	{	
		$fields = implode(',',self::$fields);
		
		return $fields;
	}
	private static function getSelect()
	{
		$select = 'SELECT '.self::getFields().' FROM Transaction AS C ';
				
		return $select;
	}
	// Expects: transactionID
	// Returns:  transaction object or array of objects, NULL If none
	public static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE C.Transaction_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // Transaction object(s) if sucessful
	}
	public static function getAll()
	{	
    	$sql = self::getSelect();

        return self::queryDB(get_class(),$sql); // Transaction object(s) if sucessful
	}
	public static function proDeal($dCode)
	{
		$sql = "SELECT Supplier_ID, Discount_Percent
				FROM Pro_Deal
				WHERE Coupon_Code = ?";
		
		$rtrn = array();
		
		$iDB = new mysqliDB();
		
		$stmt = $iDB->prepare($sql);
		
		$stmt->bind_param('s',$dCode);
		$stmt->execute();
		$stmt->bind_result($supplierID,$discount);
		
		while($stmt->fetch())
		{
			$rtrn[$supplierID] = $discount;
		}
		return $rtrn;
	}
}

?>