 <?
 require('functions.php');
 
 Security::requireMember();
 
 $page = new Page();

 $supplierID = $_REQUEST['id'];
 
 $supplier = Supplier::getNew($supplierID);

 $reviewAry = $supplier->getReviews();

 
 $page->assign('supplier',$supplier);
 $page->assign('reviewAry',$reviewAry);

 $page->setContent('page/viewSupplierReviews');
 
 $page->create();
 
 ?>