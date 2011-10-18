<?

class Security {
	
	public static $minAuth = 0;
	
	public static function setReporting() 
	{
		//if (DEVELOPMENT_ENVIRONMENT == true) {
		
		if(true) { 
			error_reporting(E_ALL&~E_NOTICE);
			ini_set('display_errors','On');
		} else {
			error_reporting(E_ALL);
			ini_set('display_errors','Off');
			ini_set('log_errors', 'On');
			//ini_set('error_log', '/tmp/logs/error.log');
		}
	}
	public static function requireAdmin() {
		
            if(!Security::isAuthed(ADMIN))
            {
                header("Location: /page/pageNotFound.php");
                exit();
            }
            self::$minAuth = ADMIN;
	}
	public static function requireMember() {
		
            if(!Security::isAuthed(MEMBER))
            {
                header("Location: /page/login.php");
                exit();
            }
            self::$minAuth = MEMBER;
	}
	public static function minAuth()
	{
		return self::$minAuth;
	}
	public static function verboseLow()
	{
		return (VERBOSE >= V_MINIMUM);
	}
	public static function verboseModerate()
	{
		return (VERBOSE >= V_MODERATE);
	}
	public static function getEncryptionKey()
	{
		return 'l3s8z%mQzPd';
	}
	public static function verboseHigh()
	{
		return (VERBOSE >= V_MAXIMUM);
	}
	// Combines paths, regardless of /'s, so Security::joinPath("/www/","/images/")
	function joinPath() 
	{
		$path = '';
		$arguments = func_get_args();
		$args = array();
		foreach($arguments as $a) if($a !== '') $args[] = $a;//Removes the empty elements
		
		$arg_count = count($args);
		for($i=0; $i<$arg_count; $i++) {
			$folder = $args[$i];
			
			if($i != 0 and $folder[0] == DIRECTORY_SEPARATOR) $folder = substr($folder,1); 
			//Remove the first char if it is a '/' - and its not in the first argument
			
			if($i != $arg_count-1 and substr($folder,-1) == DIRECTORY_SEPARATOR) $folder = substr($folder,0,-1); 
			//Remove the last char - if its not in the last argument
			
			$path .= $folder;
			if($i != $arg_count-1) $path .= DIRECTORY_SEPARATOR; //Add the '/' if its not the last element.
		}
		return $path;
	}
	// Makes relative path like /images/member/... to ROOT_DIR/speedcountry.com/www/images/member/...
	function convertRelativePath($relativePath)
	{
		$absPath = Security::joinPath(ROOT_DIR,$relativePath);
		
		return $absPath;
	}
	public static function containsSpecialCharacters($string)
	{
		$flag = preg_match('![^a-z0-9_]!i', $string);
		
		return $flag;
	}
	public static function generateKey($user)
	{
		return md5( AES_ENCRYPTION_KEY . $user );
	}
	public static function getRealIpAddr() // ByPass Most Proxy Users
		{
			if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
			{
				$ip=$_SERVER['HTTP_CLIENT_IP'];
			}
			elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
			{
				$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
			}
			else
			{
				$ip=$_SERVER['REMOTE_ADDR'];
			}
			return $ip;
	}
    public static function isAuthed($levelRequired)
    {
            if(($_SESSION["MemberAccess"] >= (int)$levelRequired))
                 return true;
                 
            return false;
	}
	public static function isMember()
	{
		if(($_SESSION["MemberAccess"] >= MEMBER))
               return true;
				 
		return false;
	}
	public static function isAdmin()
	{
		if(($_SESSION["MemberAccess"] >= ADMIN))
                return true;
			 
		return false;
	}
    public static function cleanStr($str) // Preferred for general purpose (No HTML Allowed)
    {
    	$str = trim($str);
        $str = strip_tags($str);
        $str = mysql_escape_string($str);

        return $str;
    }
        // This is an updated version of CleanStr ONLY for prepared statements (escape_string is not needed)
    public static function sanitize($str)
    {
    	$str = strip_tags($str);
        $str = trim($str);
        	
        return $str;
    }		
    public static function isValidEmail($email) 
	{
	 	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $email)) 
	 	{
    		return false;
  		}
  		return true;
	}
	public static function xssProtect($str)
	{
		$purifier = new HTMLPurifier($htmlPurifierConfigGlobal);
		
		$str = trim($str);
		
		if(strpos($str,'<p>') === 0  && strcmp(substr($str,-1,4),'</p>'))
		{
			$str = substr($str,3,strlen($str)-4); 
			$str = trim($str); // Trim again (Space between removed tags and content)
		}
		
		$str = strip_tags($str,'<b><a><strong><b><i><ul><ol><li><em><img>');
    	$str = $purifier->purify($str);
    	
    	return $str;
	}
	// REPORTING FUNCTIONS
	public static function submitReport($desc,$priority = ERROR_PRIORITY_NORMAL)
	{
		$desc = Security::cleanStr($desc);
	
		if(isset($_SESSION['MemberID']))
			$userID = $_SESSION['MemberID'];
		
		// FIX FOR RSN
		$ip = getRealIpAddr();
	
		$desc = Security::cleanStr($desc);
	
		$priority = (int)$priority;
		
		//Generate ErrorLog
		$sql = "INSERT INTO Error_Report(User_ID,Description,Priority,User_IP) VALUES(?,?,?,?)";

		$iDB = new mysqliDB();
	
		$stmt = $iDB->prepare($sql);
		$stmt->bind_param('isii',$userID,$desc,$priority,$ip);
		$stmt->execute();
	
		$error = $stmt->error;
	
		$stmt->close();
		$iDB->close();
	
		if(strlen($error) > 1)
		{
			die("Error Reporting Problem. `$desc`");
			return false;
		}
	}
	public static function createRandomPassword()
	{
    	$chars = "abcdefghijkmnpqrstuvwxyz23456789";
    	srand((double)microtime()*1000000);
    	$i = 0;
    	$pass = "";

    	while ($i <= 7) {
        	$num = rand() % 33;
        	$tmp = substr($chars, $num, 1);
        	$pass = $pass . $tmp;
    	    $i++;
    	}
    	return $pass;
	}
}

// Not preferred, use Security::submitReport
function submitReport($desc,$priority = ERROR_PRIORITY_NORMAL)
	{
		$desc = Security::cleanStr($desc);
	
		if(isset($_SESSION['MemberID']))
			$userID = $_SESSION['MemberID'];
		
		// FIX FOR RSN
		$ip = Security::getRealIpAddr();
	
		$desc = Security::cleanStr($desc);
	
		$priority = (int)$priority;
		
		//Generate ErrorLog
		$sql = "INSERT INTO Error_Report(Customer_ID,Description,Priority,User_IP) VALUES(?,?,?,?)";

		$iDB = new mysqliDB();
	
		$stmt = $iDB->prepare($sql);
		$stmt->bind_param('isii',$userID,$desc,$priority,$ip);
		$stmt->execute();
	
		$error = $stmt->error;
	
		$stmt->close();
		$iDB->close();
	
		if(strlen($error) > 1)
		{
			die("Error Reporting Problem. `$desc`");
			return false;
		}
	}
		
?>