<?

require('functions.php');

Security::requireAdmin();

$page = new Page();

$page->setTitle('Create New Customer');

$page->setContent('page/createCustomer');

if(isset($_POST['name']))
{
	$name 	= $_POST['name'];
	$email	= $_POST['email'];
	$addr	= $_POST['address'];
	$state	= $_POST['state'];
	$phone	= $_POST['phone'];
	$zip	= $_POST['zip'];
	$access	= $_POST['accessLvl'];
	
	$customer = new Customer();
	
	$customer->setName($name);
	$customer->setEmail($email);
	$customer->setAddress($addr);
	$customer->setState($state);
	$customer->setPhone($phone);
	$customer->setZip($zip);
	$customer->setAccess($access);
	
	$flag = $customer->commit();
	
	if($flag)
		$page->setSuccess('Account Created!');
		
	else
		$page->setError('Account Creation Failed');
}

$page->create();

?>