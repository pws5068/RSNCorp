<?

require('functions.php');

$page = new Page();

if(Security::isMember() && isset($_POST['checkout']))
{
	$cartID = $_POST['cartID'];
	$cart = Cart::getNew($cartID);
	
	if(!is_object($cart))
	{
		$page->setWarning('Checkout Unsuccessful :(');
	}
	else
	{
		$cart->checkout();
		$page->setSuccess("Purchase Complete!");
	}
}

$page->setTitle('Welcome to RSNCorp');
$page->setContent('index');

if($_GET['logout'] == 'T')
	$page->setSuccess('Logout Successful');

$anItemArray = Item::getRandom(10);
$page->assign('anItemArray', $anItemArray);


$page->create();



?>