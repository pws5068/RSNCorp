<?

require('functions.php');

Security::requireMember();

$page = new Page();

$page->setTitle("Edit Customer Record");

$cid = (int)$_REQUEST['cid'];

$customer = Customer::getNew($cid);

if(!is_object($customer))
	$page->setError('Customer Not Found',true);

if(isset($_POST['name']))
{
	$name 	= $_POST['name'];
	$phone	= $_POST['phone'];
	$email	= $_POST['email'];
	$addr	= $_POST['address'];
	$state	= $_POST['state'];
	$zip	= $_POST['zip'];
	$pass	= $_POST['pass'];
	
	if(strlen($pass) > 1)
		$customer->setPass($pass);
	
	$customer->setName($name);
	$customer->setPhone($phone);
	$customer->setEmail($email);
	$customer->setAddress($addr);
	$customer->setState($state);
	$customer->setZip($zip);
	
	
	$flag = $customer->commit();
	
	if($flag)
		$page->setSuccess('Account Updated!');
		
	else
		$page->setError('Account Update Failed');
}
	
$page->assign('customer',$customer);

$page->setContent('page/editCustomer');

$page->create();


?>