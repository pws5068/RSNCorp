<?

require('functions.php');

$page = new Page();

$page->setTitle('All Item Categories');

$categoryAry = Category::getAll();

$page->setContent('page/allCategories');
$page->assign('categoryAry',$categoryAry);

$page->create();

?>