<?

class Cart extends CartFactory
{
	private $id;
	private $active;
	private $itemAry;
	private $customer;
	
	function __construct($params=NULL)
	{			
		if(is_array($params))
		{
			$this->setID			( $params['id']				);
			$this->setIsActive		( $params['isActive']		);
			$this->setCustomer		( $params['customer']		);
			$this->setItemAry		( Item::getByCart($this)	);
		}
	}
	
	function getObjectType()
	{
		return CART_OBJ;
	}
    public static function getNew($id)
    {
		return CartFactory::byID($id);
    }
	function addToCart($item,$quantity=1)
	{
		if(!is_object($item))
			die('non obj item cart.class~'.__LINE__);
		
		$item->setQuantity($quantity);
		
		$this->itemAry[] = $item;
	}
	function removeItem($item)
	{
		if(!is_object($item))
			die('non obj item cart.class~'.__LINE__);
			
		foreach($this->itemAry as $key => $tempItem)
		{
			if($tempItem->getID() == $item->getID())
			{
				if($this->itemAry[$key]->getQuantity() > 1)
				{
					$this->itemAry[$key]->setQuantity($tempItem->getQuantity() -1);
				}
				else
					unset($this->itemAry[$key]);
					
				return true;
			}
		}
		
		return false;
	}
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in Cart.Class~'.__LINE__);
	}
	function setIsActive($bool)
	{
		$this->isActive = (bool)$bool;
	}
	function setItemAry($itemAry)
	{	
		$this->itemAry = $itemAry;
	}
	function setCustomer($customer)
	{
		$this->customer = $customer;
	}
	
	function getURL()
	{
		return '/page/viewAuction.php?id='.$this->getID();
	}
	function getHyperlink($params=NULL)
	{
		return '<a href="'.$this->getURL().'">'. $this->getItem()->getTitle() . ' &nbsp; $' . $this->getMin() . '  &nbsp;  ' . $this->getExpiration(). '</a>';
	}

	
	/*******************************************
		Section: GET METHODS
	*******************************************/
	/*function canEdit()
	{
		$owner = $this->getOwner();
		
		if((is_object($owner) && $owner->getID() == MY_ID) || Security::isModerator())
			return true;
			
		return false;
	}*/
	function getID()
	{
		return $this->id;
	}
	function getIsActive()
	{
		return $this->isActive;
	}
	function getItemAry()
	{	
		return $this->itemAry;
	}
	function getCustomer()
	{	
		return $this->customer;
	}
	function getDateTime()
	{
		$date = date('F j, Y, g:i a', strtotime($this->getStamp()));
		
		return $date;
	}
	

	/*******************************************
		Section: ACTION METHODS
	*******************************************/

	function update()
	{
			return parent::update();

	}
	private function readyForInsert()
	{
		if((int)$this->getID() > 0)
			throw new Exception('Failed Insert Non-New Item, Cart.Class `~'.__LINE__);
		
		return true;
	}
	function checkout()
	{
		$sql = 'UPDATE Cart SET Is_Active=0 WHERE Cart_ID=?';
		$iDB = new mysqliDB();
		
		$stmt = $iDB->prepare($sql);
		$stmt->bind_param('i',$this->getID());
		
		$error = $stmt->execute();
		
		$stmt->close();
		$iDB->close();
		
		//die("$sql id={$this->getID()}");
		
		return (strlen($error) < 1);
	}
	function commit()
	{
		if((int)$this->getID() > 0)
			return $this->update();
		
		try {
			$flag = $this->readyForInsert();
		}
		catch(Exception $e) {
		
			submitReport('Cart Creation failed. Cart.Class~'.__LINE__.' MSG: '.$e->getMessage());
			return false;
		}
		
		return parent::insert();
	}
	function delete()
	{
			return parent::delete();
	}
	public static function getByCategory($cat)
	{
		if(!is_object($cat) || $cat->getObjectType() != CATEGORY_OBJ)
		{
			die("Error, NonObj Category in Cart.Class~".__LINE__);
		}
		
		return parent::byCategory($cat);
	}

}

abstract class CartFactory extends dbAbstraction
{
	private static $fields = array(
	
		'id' 				=> 'A.Cart_ID',
		'customer'			=> 'A.Customer_ID',
		'isActive'			=> 'A.Is_Active'
		
	);
	// Expects: cartID
	// Returns: cart object or array of objects, NULL If none
	protected static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE A.Cart_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // Cart object if sucessful
	}
	protected function update()
	{		
		//
		// Delete all items from the cart first
		//
		$sql = 'DELETE FROM Cart_Link WHERE Cart_ID=?';
	
		$iDB = new mysqliDB();
		
		$stmt = $iDB->prepare($sql);
		$stmt->bind_param('i',$this->getID());
		
		$error = $stmt->execute();
		
		$stmt->close();
		
		$sql = 'INSERT INTO Cart_Link(Cart_ID,Item_ID,Quantity) VALUES(?,?,?)';
		
		foreach($this->getItemAry() as $item)
		{
			$stmt = $iDB->prepare($sql);
			$stmt->bind_param('iii',$this->getID(),$item->getID(),$item->getQuantity());
			
			$error = $stmt->execute();
			
			$error .= $stmt->close();
		}
		
		$iDB->close();
		
		return (strlen($error) < 1);
		
	}
	protected function insert()
	{
		// CREATE CART
		$sql = 'INSERT INTO Cart(Customer_ID,Is_Active) VALUES(?,1)';
		
		$params = array(	'i',
							$this->getCustomer()
						);
						
		$cartID = self::insertUpdate(get_class(),$sql,$params); // ID if sucessful
		
		if(!$cartID)
		{
			die('Insert into Cart failed in Cart.Class'.__LINE__);
			return false;
		}
		
		// Repeated in update() above... apply all changes to both
		$sql = 'INSERT INTO Cart_Link(Cart_ID,Item_ID,Quantity) VALUES(?,?,?)';
		
		$iDB = new mysqliDB();
		
		foreach($this->getItemAry() as $item)
		{
			$stmt = $iDB->prepare($sql);
			$stmt->bind_param('iii',$this->getID(),$item->getID(),$item->getQuantity());
			
			$error = $stmt->execute();
			
			$error .= $stmt->close();
		}
		
		$iDB->close();
		
		return (strlen($error) < 1);
	}
	protected function delete()
	{	
			$sql = 'DELETE FROM Cart WHERE Cart_ID=? LIMIT 1';
			
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
			
		$carts = array();
		
		$stmt->bind_result($id, $customer,$isActive);
		
		while($stmt->fetch()) 
		{	
			$params = array(
				'id'			=> $id,
				'customer'		=> $customer,
				'isActive'		=> $isActive
			);
			
			$carts[] = new Cart($params);
		}
		
		return $carts;
	}
	private static function getFields()
	{	
		$fields = implode(',',self::$fields);
		
		return $fields;
	}
	public static function getByCustomer($customer)
	{	
		if(!is_object($customer))
			return false;

		$sql = self::getSelect() . 'WHERE (Customer_ID=? AND Is_Active=1) ORDER BY Cart_ID DESC';
		
		$params = array('i',$customer->getID());

        $cart = self::queryDB(get_class(),$sql,$params, true); 
		
		if(!is_object($cart))
		{	
			// CREATE CART
			$sql = 'INSERT INTO Cart(Customer_ID,Is_Active) VALUES(?,1)';
		
			$params = array(	'i',
							$customer->getID()
						);
						
			$cartID = self::insertUpdate(get_class(),$sql,$params); // ID if sucessful
			
			$sql = self::getSelect() . 'WHERE (Customer_ID=? && Is_Active=1)';
		
			$params = array('i',$customer->getID());
        
        	$cart = self::queryDB(get_class(),$sql,$params, true); 
		}
		
		return $cart;
	}
	public static function getOld($customer)
	{	
		if(!is_object($customer))
			return false;

		$sql = self::getSelect() . 'WHERE (Customer_ID=? AND Is_Active=0) ORDER BY A.Cart_ID DESC';
		
		$params = array('i',$customer->getID());

        $cart = self::queryDB(get_class(),$sql,$params); 
		
		return $cart;
	}

	public static function getAll()
	{
		$sql = self::getSelect();
		//$params = array('i',$supplierObj->getID());
        
        return self::queryDB(get_class(),$sql); // Cart object(s) if sucessful
	}

	private static function getSelect()
	{
		$select = 	'SELECT ' . self::getFields() . ' FROM Cart AS A ';
		return $select;
	}


}

?>