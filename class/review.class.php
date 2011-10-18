<?

class Review extends ReviewFactory
{
	private $customer;
	private $supplier;
	private $message;
	
	function __construct($params=NULL)
	{			
		if(is_array($params))
		{
			$this->setCustomer		( $params['customer']		);
			$this->setSupplier		( $params['supplier']		);
			$this->setMessage		( $params['message']		);
		}
	}
	function getObjectType()
	{
		return REVIEW_OBJ;
	}
	/*
    public static function getNew($id)
    {
		return ReviewFactory::byID($id);
    }
    */
    
	/*******************************************
		Section: SET METHODS
	*******************************************/
	/*
	function setID($id)
	{
		if((int)$id > 0)
			$this->id = $id;
			
		else
			submitReport('Bad ID Set in Review.Class~'.__LINE__);
	}*/
	function setCustomer($customer)
	{
		$this->customer = $customer;
	}
	function setMessage($msg)
	{
		$this->message = $msg;
	}
	function setSupplier($supObj)
	{	
		$this->supplier = $supObj;
	}
	function getURL()
	{
		return $this->getCustomer()->getURL();
	}
	function getHyperlink($params=NULL)
	{
		return '<a href="'.$this->getURL().'">'. $this->getItem()->getTitle() . '</a>';
	}
	
	/*******************************************
		Section: GET METHODS
	*******************************************/
	function getID()
	{
		return $this->id;
	}
	function getCustomer()
	{
		return $this->customer;
	}
	function getSupplier()
	{	
		return $this->supplier;
	}
	function getMessage()
	{
		return $this->message;
	}
	/*
	function getDateTime()
	{
		$date = date('F j, Y, g:i a', strtotime($this->getStamp()));
		
		return $date;
	}*/	
	/*******************************************
		Section: ACTION METHODS
	*******************************************/
	
	function update()
	{
		if($this->canEdit())
			return parent::update();
	}
	private function readyForInsert()
	{
		return true;
	}
	function commit()
	{		
		try {
			$flag = $this->readyForInsert();
		}
		catch(Exception $e) {
		
			submitReport('Review Creation failed. Review.Class~'.__LINE__.' MSG: '.$e->getMessage());
			return false;
		}
		
		return parent::insert();
	}
	function delete()
	{
		if($this->canEdit())
			return parent::delete();
	}
}

abstract class ReviewFactory extends dbAbstraction
{
	private static $fields = array(
	
		'customerID' 		=> 'R.Customer_ID',
		'supplierID'		=> 'R.Supplier_ID',
		'message'			=> 'R.Message'
	);
	/*
	protected function update()
	{		
		$sql = 'UPDATE Review SET Seller_ID=?,Min_Price=?,Item_ID=? WHERE Review_ID=?';
		
		$params = array(	'iiii',
							$this->getSeller(),
							$this->getMin(), 
							$this->getItem(),        
							$this->getID()
						);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	*/
	protected function insert()
	{
		$sql = 'INSERT INTO Supplier_Reviewed_By(Customer_ID,Supplier_ID,Message) VALUES(?,?,?)';
		
		$params = array(	'iis',
							$this->getCustomer()->getID(),
							$this->getSupplier()->getID(),
							$this->getMessage()
						);
						
		return self::insertUpdate(get_class(),$sql,$params); // True if sucessful
	}
	protected function delete()
	{		
		$sql = 'DELETE FROM Supplier_Reviewed_By WHERE Review_ID=? LIMIT 1';
		
		$params = array('i',$this->getID());
						
		return self::insertUpdate(get_class(),$sql,$params);
	}
	
	// Purpose: Build Array of Reviews
	// Expects: iDB stmt object from an Review SELECT query
	// Returns: Reviews Object(s) or NULL		
	protected function build($stmt)
	{
		if(!is_object($stmt))
			return false;
			
		$reviews = array();
		
		$stmt->bind_result($cid,$sid,$msg);
		
		while($stmt->fetch()) 
		{	
			$customer = Customer::getNew($cid);
			$supplier = Supplier::getNew($sid);
			
			$params = array(
				'customer' 		=> $customer,
				'supplier'		=> $supplier,
				'message'		=> $msg
			);
			
			$reviews[] = new Review($params);
		}
		
		return $reviews;
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
        
        return self::queryDB(get_class(),$sql); // Review object(s) if sucessful
	}
	private static function getSelect()
	{
		$select = 	'SELECT ' . self::getFields() . ' FROM Supplier_Reviewed_By AS R ';
		return $select;
	}
	 //Expects: itemID
	 //Returns: item object or array of objects, NULL If none
	protected static function byID($id)
    {
    	$id = (int)$id;
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE R.Review_ID=?';
    	
        $params = array('i',$id);
        
        return self::queryDB(get_class(),$sql,$params,true); // Review object if sucessful
	}
	static function getbySupplier($supplier)
    {
    	if(!is_object($supplier))
    		die('NonObj Supplier, Review.Class~'.__LINE__);
    		
    	$id = (int)$supplier->getID();
    	
    	if($id < 1)
    		return false;
    		
    	$sql = self::getSelect() . 'WHERE R.Supplier_ID=?';
    	
        $params = array('i',$id);
        
        $objs =  self::queryDB(get_class(),$sql,$params); // Review objects if sucessful
        
        return $objs;
	}
}

?>