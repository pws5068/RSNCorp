<?

require('functions.php');

Security::requireMember();

$page = new Page();

$page->setTitle('All Auctions');

$auctionAry = Auction::getCurrent();


$page->setContent('page/allAuctions');
$page->assign('auctionAry',$auctionAry);

$page->create();

?>