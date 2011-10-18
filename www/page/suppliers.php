<?

include('functions.php');

Security::requireMember();

$page = new Page();

$page->setTitle("Suppliers");

$suppliers = Supplier::getAll();

$page->setContent('page/suppliers');



$page->assign('supplierAry',$suppliers);
$page->create();

?>