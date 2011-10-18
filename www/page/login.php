<?

require('functions.php');

$page = new Page();

/*
if(Security::isMember())
	$page->setError('You are already logged in,',true);
*/

if(isset($_POST['userField']) && isset($_POST['passField']))
{
	$user		= $_POST['userField'];
	$pass 		= $_POST['passField'];
	
	if(Customer::authenticate($user,$pass))
	{
		$page->setSuccess('You are now logged in.');
	}
		
	else
		$page->setWarning('Login Failed, try again');
}

if(Security::isMember())
{
	$me = Customer::getNew($_SESSION['MemberID']);	
	$page->assign('me',$me);
}

$page->setTitle('Customer Login');

$page->setContent('page/login');

$page->create();

?>