<?

require('functions.php');

$page = new Page();

$page->setTitle('Create New Customer');

$page->setContent('page/registerCustomer');

if(isset($_POST['name']))
{
	$name 	= $_POST['name'];
	$email	= $_POST['email'];
	$pass1  = $_POST['password'];
	$pass2  = $_POST['password2'];
	$addr	= $_POST['address'];
	$state	= $_POST['state'];
	$phone	= $_POST['phone'];
	$zip	= $_POST['zip'];
	$hidden = $_POST['gonnaGetYa'];
	$access	= MEMBER;
	
	if(strlen($hidden) == 0)
	{
		$customer = new Customer();
		
		$customer->setName($name);
		$customer->setEmail($email);
		$customer->setPass($pass1);
		$customer->setAddress($addr);
		$customer->setState($state);
		$customer->setPhone($phone);
		$customer->setZip($zip);
		$customer->setAccess($access);
		
		if($pass1 == $pass2)
			$flag = $customer->commit();
		else
			$flag = false;
		
		if($flag)
			$page->setSuccess('Account Created!');
			
		else
			$page->setError('Account Creation Failed');
	}
	else
	{
		$page->setError('Account Creation Failed');
	}
}

$page->create();

?>