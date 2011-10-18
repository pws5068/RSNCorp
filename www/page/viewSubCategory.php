<?

require('functions.php');

$page = new Page();

$sCatID = $_REQUEST['id'];

$sCategory = SubCategory::getNew($sCatID);

if(!is_object($sCategory))
{
	$page->setError('Specified Category Does Not Exist',true);
}

$ssCategories = $sCategory->getSubSubCategories();

$page->setTitle('Viewing Sub-Category: '.$sCategory->getTitle());

$page->setContent('page/viewSubCategory');

$page->assign('sCategory',$sCategory);
$page->assign('category',Category::getNew($sCategory->getCategoryID()));
$page->assign('ssCategories',$ssCategories);

$page->addOnload('
$(".itemListing").fadeTo("slow", 0.65);
	$(".itemListing").hover(function(){
		$(this).fadeTo(400, 1.0);
	},function(){
   		$(this).fadeTo(400, 0.65);
	});
');

$page->create();

?>