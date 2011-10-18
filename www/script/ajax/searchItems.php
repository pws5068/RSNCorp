<?

session_start();
require_once('functions.php');

	$searchString	= $_REQUEST['searchString'];
	$minPrice		= (int)$_REQUEST['minPrice'];
	$maxPrice		= (int)$_REQUEST['maxPrice'];
	
if(strlen($searchString) > 0 || $maxPrice > 0)
{
	if($minPrice < $maxPrice) // Prices are set
		$resultAry = Item::search($searchString,$minPrice,$maxPrice);
		
	else
		$resultAry = Item::search($searchString);
}
else
	die('You must provide more information to search');
	
if(sizeOf($resultAry) < 1)
{
	die('No items match the specified criteria');
}

if(sizeOf($resultAry) > 0)
{
	foreach($resultAry as $item) {
?>
	<div id="item_<?=$item->getID()?>" class="itemListing" >
		
		<a href="<?=$item->getURL()?>">
		
			<div class="itemThumb">
				<?=$item->showThumbnailSmall()?>
			</div>
			<div class="itemText">
				<h2><?=$item->getTitle()?></h2>
				<?=$item->getDescription()?>
			</div>
		</a>
	</div>
<?
	}
}

?>