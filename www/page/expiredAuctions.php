<?

require('functions.php');

$page = new Page();

$page->setTitle('All Auctions');

$auctionAry = Auction::getExpired();


$page->setContent('page/expiredAuctions');
$page->assign('auctionAry',$auctionAry);

$page->create();

?>