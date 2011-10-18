<?php


//home/svision/public_html/speedcountry.com/class

//set_include_path('/home/svision/public_html/rsncorp.com/class/');

require_once('constants.php');
require('mail.class.php');
require('category.class.php');
require('subcategory.class.php');
require('subsubcategory.class.php');
require('review.class.php');
require('transaction.class.php');
require('item.class.php');	
require(BASE_DIR.'app/smarty/Smarty.class.php');
require('security.class.php');
require('customer.class.php');
require('supplier.class.php');
require('auction.class.php');
require('bid.class.php');
require('cart.class.php');
require('page.class.php');			 // Extends Smarty

if(Security::isAdmin())
{
	define('CALL_FROM','RSN_ADMIN');
	include('admin.class.php');
}
if(Security::isMember())
{
	$me = Customer::getNew($_SESSION['MemberID']);
}

/*
if(Security::isMember())
{
	global $me;
	$me = User::getNew( MY_ID );
}
*/

Security::setReporting(); // Turns Error Reporting ON/OFF According to "DEVELOPMENT_ENVIRONMENT" in constants.php

class mysqliDB extends mysqli {
	 private $exists = false;
	 
	// NEED BASE-64 Encoded
	private static $dbHost = 'bG9jYWxob3N0';
	private static $dbUser = 'c3Zpc2lvbl9yc24=';
	private static $dbPass = 'a2l0dGVuTWl0dGVueg==';
	private static $dbName = 'c3Zpc2lvbl9yc24=';
     
    public function __construct() {
			
        parent::__construct(base64_decode(self::$dbHost),base64_decode(self::$dbUser),base64_decode(self::$dbPass),base64_decode(self::$dbName));

        if (mysqli_connect_error()) {
			
			submitReport('DB Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
			
            die('Connect Error (' . mysqli_connect_errno() . ') '
                    . mysqli_connect_error());
        }
		
		return true;
    }
	function __destruct()
	{
		
	}
}

abstract class dbAbstraction {
	private $exists = false;
	 
	private static $dbHost = '';
	private static $dbUser = '';
	private static $dbPass = '';
	private static $dbName = '';
	
	private $db;
	
	public function __construct()
	{
		$this->db = new mysqliDB();
	}
    
    protected abstract function build($stmt);
    
    private function open() 
    {				
        parent::__construct(base64_decode(self::$dbHost),base64_decode(self::$dbUser),base64_decode(self::$dbPass),base64_decode(self::$dbName));

        if (mysqli_connect_error()) {
			
			submitReport('DB Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
			
            die('Connect Error (' . mysqli_connect_errno() . ') '. mysqli_connect_error());
        }
		
		return true;
    }
    /*
    	UPDATE/DELETE Return BOOLEAN
    	INSERT Returns Insert_ID if TRUE, Else FALSE
    */
    protected static function insertUpdate($abstrClass,$sql,$bindings)
    {
    	if(strpos($sql,'INSERT INTO ') !== false)
    		$insertQry = true;
    		
    	$iDB = new mysqliDB();
		$stmt = $iDB->prepare($sql);
		
		if(!is_object($stmt))
		{
			die("uhhh ohohhhhh".$iDB->error);
			submitReport("SQL Prepare Failed: '$sql' DBAbstractionFactory~".__LINE__." Response: {$iDB->error}");
			return false;
		}
	
		call_user_func_array (array($stmt,'bind_param'),$bindings);
		
		$stmt->execute();
			
		if(!empty($stmt->error))
		{
			$error = true;
			submitReport("MySqli Error DBAbstractFactory:".__LINE__);
		}
		else if($insertQry)
		{
			$insertID = $stmt->insert_id;
		}
			
		$stmt->close();
		$iDB->close();
		
		if(isset($error))
			return false;
    
    	if($insertQry)
    		return $insertID;
    		
    	return true;
    }
    protected static function queryDB($abstrClass,$sql,$bindings = NULL,$single = false)
	{
		if(isset($bindings))
		{
			$paramTypes = $bindings[0]; // Single character type representations for bind_param, i => integer s => string etc..
		
			if(strlen($paramTypes) != sizeof($bindings)-1)
				die("Bad bindings in functions.. Param:".strlen($paramTypes) . " Bindings: " . sizeof($bindings));
		}
		
		$iDB = new mysqliDB();
		$stmt = $iDB->prepare($sql);
		
		if(!is_object($stmt))
		{
			submitReport("SQL Prepare Failed: '$sql' DBAbstractionFactory~".__LINE__." Response: {$iDB->error}");
			return false;
		}
	
		if(isset($bindings))
			call_user_func_array (array($stmt,'bind_param'),$bindings);
		
		//$stmt->bind_param($paramTypes,$bindings);
		
		$stmt->execute();
		
		if(empty($stmt->error))
			$objs = call_user_func(array($abstrClass,'build'),$stmt);
			
		else
		{
			$error = true;
			submitReport("MySqli Error DBAbstractFactory:".__LINE__);
		}
			
		$stmt->close();
		$iDB->close();
		
			if($error)
				return false;
		
		if($single && is_array($objs))
			return $objs[0];
			
		return $objs;
	}
	
	public function __destruct()
	{
		try { if(is_object($this->db)) $this->db->close(); }
		
		catch(Exception $e) { submitReport("Functions.php Destruct DB Failed (May be a one-time glitch)~.".__LINE__); }
	}
}

function getObject($typeID,$objID)
{
    $objID = (int)$objID;

    switch($typeID)
    {
    	case CUSTOMER_OBJ : return Customer::getNew($objID);
            break;
        case ITEM_OBJ : return Item::getNew($objID);
        	break;
        case CATEGORY_OBJ : return Category::getNew($objID);
        	break;
        case SUPPLIER_OBJ : return Supplier::getNew($objID);
       		break;
       	case SUB_CATEGORY_OBJ : return SubCategory::getNew($objID);
       		break;
    }

    return false;
}

?>