<?

require('functions.php');

$page = new Page();

$catID = $_REQUEST['id'];

$category = Category::getNew($catID);

if(!is_object($category))
{
	$page->setError('Specified Category Does Not Exist',true);
}

$subCategories = $category->getSubCategories();

$page->setTitle('Viewing Category: '.$category->getTitle());

$page->setContent('page/viewCategory');

$page->assign('category',$category);
$page->assign('subCategories',$subCategories);

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