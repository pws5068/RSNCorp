<?

require('functions.php');

Security::requireMember();

$page = new Page();

$page->setTitle('Post Supplier Comment');

$page->setContent('page/writeReview');

if(isset($_POST['supplier']))
{
	
	$seller	= $_POST['supplier'];
	$review = $_POST['review'];
	$customer	= $_POST['customer'];
	
	$Review = new Review();
	
	$Review->setSupplier(Supplier::getNew($seller));
	$Review->setMessage( $review);
	$Review->setCustomer(Customer::getNew($customer));
	
	$flag = $Review->commit();
	
	$page->setSuccess('Review Posted!');
}

$page->addJS('populate_auction.php');
$page->addOnload('fillSuppliers();');
$page->create();

?>