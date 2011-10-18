<?

require('functions.php');
Security::requireMember();

$page 		= new Page();


$customerID = $me->getID();

$cart		= Cart::getByCustomer($me);
$customer 	= Customer::byID($customerID);

$page->assign('customer', $customer);

if(is_object($cart))
 {
 	$itemAry = $cart->getItemAry();

 	//die($cart->getID());
 	
 	$page->assign('itemAry',$itemAry);
 
 	$total = 0;
 
 	foreach($itemAry as $item)
 	{
		 $total += $item->getPrice() * $item->getQuantity();
 	}
 
 	$page->assign('total', $total);
  }

if (strlen($_POST['dCode']) > 0)
{
	$discountResultAry = Transaction::proDeal($_POST['dCode']);
	if(sizeOf($discountResultAry) > 0)
	{		
		foreach($discountResultAry as $key => $value)
		{
			$discountSupplierID = $key;
			$discountAmount = $value;
		}
		
	$page->setSuccess('Discount Applied!');
	
	$amtSaved = $total * $value;
	$newTotal = $total - $amtSaved;
	$accountCode = 13;
	
	$page->assign('newTotal', $newTotal);
	$page->assign('amtSaved', $amtSaved);
	$page->assign('accountCode', $accountCode);
	
	}
	else
		$page->setWarning('Discount Code Does Not Exist');
}
else
{
	$accountCode = 2;
	$page->assign('accountCode', $accountCode);
}

$page->setContent('page/checkout');
$page->assign('cart',$cart);

$page->create();

?>