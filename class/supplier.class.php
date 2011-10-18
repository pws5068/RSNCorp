<?

class Supplier extends SupplierFactory
{
	private $id;
	private $title;
	private $address;
	private $city;
	private $state;
	private $phone;
	private $zip;
	private $country;
	private $thumb;

	
	function __construct($params=NULL)
	{
		if(is_array($params))
		{
			$this->setID			( $params['id']				);
			$this->setTitle			( $params['title']			);
			$this->setAddress		( $params['address']		);
			$this->setCity			( $params['city']			);
			$this->setState			( $params['state']		 	);
			$this->setPhone			( $params['phone']			);
			$this->setZip			( $params['zip']			);
			$this->setCountry   	( $params['country']		);
			$this->setThumb		   	( $params['thumb']			);
		}
	}
	function getObjectType()
	{
		return SUPPLIER_OBJ;
	}
    public static function getNew($id)
    {
		return SupplierFactory::byID($id);
    }
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in Supplier.Class~'.__LINE__);
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
		return '/page/viewSupplier.php?id='.$this->getID();
	}
	function getHyperlink($params=NULL)
	{
		return '<a href="'.$this->getURL().'">'.$this->getTitle().'</a>';
	}
	function getItems()
	{
		return Item::getBySupplier($this);
	}
	function update()
	{
		if($this->canEdit())
			return parent::update();
	}
	private function readyForInsert()
	{
		if((int)$this->getID() > 0)
			throw new Exception('Failed Insert Non-New Supplier, Supplier.Class `~'.__LINE__);
		
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
		
			submitReport('Supplier Creation failed. Supplier.Class~'.__LINE__.' MSG: '.$e->getMessage());
			return false;
		}
		
		return parent::insert();
	}
	public static function authenticate($userName,$pass)
	{
		$supplier = parent::byLogin($userName,$pass);
		
		if(!is_object($supplier)) // Login Failed
			return false;
			
        $_SESSION["MemberID"]			= $supplier->getID();
        $_SESSION["MemberAccess"] 		= $supplier->getAccess();
		
		return true;
	}
	function delete()
	{
		if($this->canEdit())
			return parent::delete();
	}
	function getReviews()
	{
		return Review::getbySupplier($this);
	}
}

abstract class SupplierFactory extends dbAbstraction
{
	private static $fields = array(
	
		'id' 				=> 'C.Supplier_ID',
		'title'				=> 'C.Title',
		'address'			=> 'C.Address',
		'city'				=> 'C.City',
		'state'				=> 'C.State',
		'phone'				=> 'C.Telephone',
		'zip'				=> 'C.Zip',
		'country'			=> 'C.Country',
		'thumb'				=> 'C.Thumbnail'
		
	);
	protected function update()
	{
		$sql = 'UPDATE Supplier SET Title=?,Address=?,City=?,State=?,Telephone=?,Zip=?,Country=?,Thumbnail=? WHERE Supplier_ID=?';
		
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
	protected function insert()
	{
		$sql = 'INSERT INTO Supplier(Title,Address,City,State,Telephone,Zip,Country,Thumbnail) VALUES(?,?,?,?,?,?,?,?)';
		
		$params = array(	'sssssis',
							$this->getTitle(),
							$this->getAddress(),
							$this->getCity(),
							$this->getState(),
							$this->getPhone(),
							$this->getZip(),
							$this->getCountry(),
							$this->getThumb()
						);
						
		//die($sql . " Title {$this->getTitle()} Addr: {$this->getAddress()} State {$this->getState()} Zip {$this->getZip()} Country {$this->getCountry()}");
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function delete()
	{		
		$sql = 'DELETE FROM Supplier WHERE Supplier_ID=? LIMIT 1';
		
		$params = array('i',$this->getID());
						
		return self::insertUpdate(get_class(),$sql,$params);
	}
				
	// Purpose: Build Array of Suppliers
	// Expects: iDB stmt object from an Supplier SELECT query
	// Returns: Supplier Object(s) or NULL		
	protected function build($stmt)
	{
		if(!is_object($stmt))
			return false;
			
		$suppliers = array();
		
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
			
			$suppliers[] = new Supplier($params);
			
		}
		
		return $suppliers;
	}
	private static function getFields()
	{	
		$fields = implode(',',self::$fields);
		
		return $fields;
	}
	private static function getSelect()
	{
		$select = 'SELECT '.self::getFields().' FROM Supplier AS C ';
				
		return $select;
	}
	// Expects: supplierID
	// Returns:  supplier object or array of objects, NULL If none
	public static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE C.Supplier_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // Supplier object(s) if sucessful
	}
	public static function getAll()
	{	
    	$sql = self::getSelect();

        return self::queryDB(get_class(),$sql); // Supplier object(s) if sucessful
	}
//	public static function byLogin($username,$pass)
//	{
//		$sql = self::getSelect() . 'WHERE C.Email=? AND C.Pass=AES_ENCRYPT(?,?) LIMIT 1';
//		
		//die($sql . " $username Pass=".Security::getEncryptionKey(). " Pass=".$pass);
//		
//		$params = array('sss',$username,Security::getEncryptionKey(),$pass);
//		
//		return self::queryDb(get_class(),$sql,$params,true);
//	}
}

?>