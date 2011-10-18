<?

require('functions.php');

$page = new Page();

//die(print_r($_REQUEST));
$auctionID = $_REQUEST['id'];

$auction = Auction::getNew($auctionID);

if(!is_object($auction))
	$page->setError('Auction Not Found',true);

if(isset($_POST['bid']))
 {
	$storedException;
	try
	{
		$auction->bid($bid);
	}
	catch(Exception $e)
	{	
		$page->setError($e->getMessage());
		$storedException = e;
	}
	if(!is_object($storedExeption))
		$page->setSuccess("Your Bid has been Placed!");

 }

$page->setTitle("Auction: " . $auction->getItem()->getTitle());

$page->assign('auction',$auction);

$expired = $auction->isExpired();

$page->assign('expired', $expired);

$highestBid = Bid::getHighestBid($auction->getID());

$page->assign('highestBid', $highestBid);

$page->setContent('page/viewAuction');


$page->create();

?>