<?

require('functions.php');

$page = new Page();

$itemID = $_REQUEST['id'];

$item = Item::getNew($itemID);

if(!is_object($item))
	$page->setError('Item Not Found',true);

$page->setTitle("Item Listing: " . $item->getTitle());

$page->assign('item',$item);

$page->setContent('page/viewItem');


$itemsBoughtArray = Item::getOthersBought($itemID);
$page->assign('itemsBoughtArray', $itemsBoughtArray);

$page->create();

?>