<?

session_start();

require_once('functions.php');

//Security::requireMember();

$oid = (int)$_POST['oid'];
$tid = (int)$_POST['tid'];

$obj = getObject($tid,$oid);

//die("Found: " . print_r($obj));

if(is_object($obj) && $obj->delete())
	die("1");
	
else
	die("0");

?>