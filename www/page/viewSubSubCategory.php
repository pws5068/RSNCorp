<?

require('functions.php');

$page = new Page();

$ssCatID = $_REQUEST['id'];

$ssCategory = SubSubCategory::getNew($ssCatID);

if(!is_object($ssCategory))
{
	$page->setError('Specified Sub-Sub-Category Does Not Exist',true);
}

$page->setTitle('Viewing Sub-Category: '.$ssCategory->getTitle());

$page->setContent('page/viewSubSubCategory');

$sCategory = SubCategory::getNew($ssCategory->getSubSSubID());
$category = Category::getNew($sCategory->getCategoryID());

$page->assign('ssCategory',$ssCategory);
$page->assign('sCategory',$sCategory);
$page->assign('category',$category);

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