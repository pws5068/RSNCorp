<?

require('functions.php');

$page = new Page();

$page->setTitle('Create New Auction');

$page->setContent('page/createAuction');

if(isset($_POST['supplier']))
{
	
	$seller	= $_POST['supplier'];
	$item = $_POST['item'];
	$min	= $_POST['min'];
	
	$Auction = new Auction();
	
	$Auction->setSeller(Supplier::getNew($seller));
	$Auction->setItem( Item::getNew($item));
	$Auction->setmin($min);
	
	$flag = $Auction->commit();
	
	if($flag)
		$page->setSuccess('Auction Created!');
		
	else
		$page->setError('Auction Creation Failed');
}

$page->addJS('populate_auction.php');
$page->addOnload('fillSuppliers();');
$page->create();

?>