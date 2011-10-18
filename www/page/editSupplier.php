<?

require('functions.php');

Security::requireMember();

$page = new Page();

$page->setTitle("Edit Supplier Record");

$cid = (int)$_REQUEST['cid'];

$supplier = Supplier::getNew($cid);

if(!is_object($supplier))
	$page->setError('Supplier Not Found',true);

if(isset($_POST['title']))
{
	$title 	= $_POST['title'];
	$phone	= $_POST['phone'];
	$addr	= $_POST['address'];
	$city	= $_POST['city'];
	$state	= $_POST['state'];
	$zip	= $_POST['zip'];
	$country = $_POST['country'];
	$thumb = $_POST['thumb'];
	
	
	$supplier->setTitle($title);
	$supplier->setPhone($phone);
	$supplier->setCountry($country);
	$supplier->setAddress($addr);
	$supplier->setCity($city);
	$supplier->setState($state);
	$supplier->setZip($zip);
	$supplier->setThumb($thumb);
	
	
	$flag = $supplier->commit();
	
	if($flag)
		$page->setSuccess('Account Updated!');
		
	else
		$page->setError('Account Update Failed');
}
	
$page->assign('supplier',$supplier);

$page->setContent('page/editSupplier');

$page->create();


?>