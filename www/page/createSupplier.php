<?

require('functions.php');

$page = new Page();

$page->setTitle('Create New Supplier');

$page->setContent('page/createSupplier');

if(isset($_POST['title']))
{
	$title 	= $_POST['title'];
	$addr	= $_POST['address'];
	$city	= $_POST['city'];
	$state	= $_POST['state'];
	$phone	= $_POST['phone'];
	$zip	= $_POST['zip'];
	$country	= $_POST['country'];
	$thumb	= $_POST['thumb'];
	
	$supplier = new Supplier();
	
	$supplier->setTitle($title);
	$supplier->setCountry($country);
	$supplier->setAddress($addr);
	$supplier->setCity($city);
	$supplier->setState($state);
	$supplier->setPhone($phone);
	$supplier->setZip($zip);
	$supplier->setThumb($thumb);
	
	$flag = $supplier->commit();
	
	if($flag)
		$page->setSuccess('Account Created!');
		
	else
		$page->setError('Account Creation Failed');
}

$page->create();

?>