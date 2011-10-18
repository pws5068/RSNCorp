<?

class Customer extends CustomerFactory
{
	private $id;
	private $email;
	private $name;
	private $address;
	private $state;
	private $phone;
	private $zip;
	private $dob;
	private $access;
	private $timeStamp;
	
	function __construct($params=NULL)
	{
		if(is_array($params))
		{
			$this->setID			( $params['id']				);
			$this->setEmail			( $params['email']			);
			$this->setName			( $params['name']			);
			$this->setAddress		( $params['address']		);
			$this->setState			( $params['state']		 	);
			$this->setPhone			( $params['phone']			);
			$this->setZip			( $params['zip']			);
			$this->setTimeStamp 	( $params['stamp']			);
			$this->setDOB		 	( $params['dob']			);
			$this->setAccess		( $params['access']			);
		}
	}
	function getObjectType()
	{
		return CUSTOMER_OBJ;
	}
    public static function getNew($id)
    {
		return CustomerFactory::byID($id);
    }
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in Customer.Class~'.__LINE__);
	}
	function setEmail($email)
	{
		$this->email = $email;
	}
	function setName($name)
	{	
		$this->name = $name;
	}
	function setAddress($addr)
	{
		$this->address = $addr;
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
	function setDescription($descr)
	{	
		$this->description = $descr;
	}
	function setQuantity($quantity)
	{	
		$this->quantity = (int)$quantity;
	}
	function setAccess($access)
	{
		if($access > 0 && $access < 10)
			$this->access = (int)$access;
	}
	function setTimeStamp($stamp)
	{
		$this->timeStamp = $stamp;
	}
	function setDOB($dob)
	{
		$this->dob = $dob;
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
	function getEmail()
	{
		return $this->email;
	}
	function getName()
	{	
		return $this->name;
	}
	function getAddress()
	{
		return $this->address;
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
	function getThumb()
	{
		return $this->thumb;
	}
	function getDescription()
	{	
		return $this->description;
	}
	function getQuantity()
	{	
		return $this->quantity;
	}
	function getDOB()
	{
		return $this->dob;
	}
	function getPass()
	{
		return $this->tempPass;
	}
	function getAccess()
	{
		return $this->access;
	}
	function getTimeStamp()
	{
		return $this->timeStamp;
	}
	function getOwner()
	{
		return $this; // for Polymorphism
	}
	function update()
	{
		if($this->canEdit())
			return parent::update();
	}
	private function readyForInsert()
	{
		if((int)$this->getID() > 0)
			throw new Exception('Failed Insert Non-New Customer, Customer.Class `~'.__LINE__);
		
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
		
			submitReport('Customer Creation failed. Customer.Class~'.__LINE__.' MSG: '.$e->getMessage());
			return false;
		}
		
		return parent::insert();
	}
	public static function authenticate($userName,$pass)
	{
		$customer = parent::byLogin($userName,$pass);
		
		if(!is_object($customer)) // Login Failed
			return false;
			
        $_SESSION["MemberID"]			= $customer->getID();
        $_SESSION["MemberAccess"] 		= $customer->getAccess();
		
		return true;
	}
	function delete()
	{
		if($this->canEdit())
			return parent::delete();
	}
}

abstract class CustomerFactory extends dbAbstraction
{
	private static $fields = array(
	
		'id' 				=> 'C.Customer_ID',
		'email'				=> 'C.Email',
		'name'				=> 'C.Name',
		'address'			=> 'C.Address',
		'state'				=> 'C.State',
		'phone'				=> 'C.Telephone',
		'zip'				=> 'C.Zip',
		'dob'				=> 'C.DOB',
		'access'			=> 'C.Access',
		'stamp'				=> 'C.Created'
	);
	protected function update()
	{		
		$sql = 'UPDATE Customer SET Email=?,Name=?,Address=?,State=?,Telephone=?,Zip=?,DOB=?,Access=? WHERE Customer_ID=?';
		
		$params = array(	'ssssissii',
							$this->getEmail(),
							$this->getName(),
							$this->getAddress(),
							$this->getState(),
							$this->getPhone(),
							$this->getZip(),
							$this->getDOB(),
							$this->getAccess(),
							$this->getID()
						);
						
		if(strlen($this->getPass()) > 0)
		{
			$sql2 = 'UPDATE Customer SET Pass=AES_ENCRYPT(?,?) WHERE Customer_ID=?';
			
			$params2 = array(	'ssi',
								Security::getEncryptionKey(),
								$this->getPass(),
								$this->getID()
							);
							
			$passUpdate = self::insertUpdate(get_class(),$sql2,$params2);
		}
		
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function insert()
	{
		$sql = 'INSERT INTO Customer(Email,Name,Address,State,Telephone,Zip,Access,DOB) VALUES(?,?,?,?,?,?,?,?)';
		
		$params = array(	'sssssisi',
							$this->getEmail(),
							$this->getName(),
							$this->getAddress(),
							$this->getState(),
							$this->getPhone(),
							$this->getZip(),
							$this->getAccess(),
							' '
						);
						
		//die($sql . " " . print_r($params));
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function delete()
	{		
		$sql = 'DELETE FROM Customer WHERE Customer_ID=? LIMIT 1';
		
		$params = array('i',$this->getID());
						
		return self::insertUpdate(get_class(),$sql,$params);
	}
				
	// Purpose: Build Array of Customers
	// Expects: iDB stmt object from an Customer SELECT query
	// Returns: Customer Object(s) or NULL		
	protected function build($stmt)
	{
		if(!is_object($stmt))
			return false;
			
		$customers = array();
		
		$stmt->bind_result($id,$email,$name,$address,$state,$phone,$zip,$dob,$access,$stamp);
		
		while($stmt->fetch()) 
		{	
			$params = array(
				'id'			=> $id,
				'email'			=> $email,
				'name'			=> $name,
				'address'		=> $address,
				'state'			=> $state,
				'phone'			=> $phone,
				'zip'			=> $zip,
				'dob'			=> $dob,
				'access'		=> $access,
				'stamp'			=> $stamp
			);
			
			$customers[] = new Customer($params);
			
		}
		
		return $customers;
	}
	private static function getFields()
	{	
		$fields = implode(',',self::$fields);
		
		return $fields;
	}
	private static function getSelect()
	{
		$select = 'SELECT '.self::getFields().' FROM Customer AS C ';
				
		return $select;
	}
	// Expects: customerID
	// Returns: customer object or array of objects, NULL If none
	public static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE C.Customer_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // Customer object(s) if sucessful
	}
	public static function getAll()
	{
    	$sql = self::getSelect();
    
        return self::queryDB(get_class(),$sql); // Customer object(s) if sucessful
	}
	public static function byLogin($username,$pass)
	{
		$sql = self::getSelect() . 'WHERE C.Email=? AND C.Pass=AES_ENCRYPT(?,?) LIMIT 1';
		
		//die($sql . " $username Pass=".Security::getEncryptionKey(). " Pass=".$pass);
		
		$params = array('sss',$username,Security::getEncryptionKey(),$pass);
		
		return self::queryDb(get_class(),$sql,$params,true);
	}
}

?>