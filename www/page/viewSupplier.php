<?

require('functions.php');

$page = new Page();

$supplierID = $_REQUEST['id'];

$supplier = Supplier::getNew($supplierID);

$items = $supplier->getItems();

if(!is_object($supplier))
	$page->setError('Supplier Not Found',true);

$page->setTitle("Supplier: " . $supplier->getTitle());

$page->assign('supplier',$supplier);

$page->assign('itemsAry',$items);

$page->setContent('page/viewSupplier');

$page->create();

?>