<?

include('functions.php');

Security::requireAdmin();

$page = new Page();

$page->setTitle("Customers");

$page->assign('customerAry',Customer::getAll());

$page->setContent('page/customers');

$page->addOnload("setSuccess('test!');");

$page->create();

?>