 <?
 require('functions.php');
 
 Security::requireMember();
 
 $page = new Page();
 $page->setTitle("My Cart");
 
 $cart = Cart::getByCustomer($me);
 
 if(isset($_POST['itemID']))
 {
	 // User is adding an item to their cart
	$itemID = (int)$_POST['itemID'];
	$item = Item::getNew($itemID); 
	
	$cart->addToCart($item);
	$cart->commit();
	
	$page->setSuccess('Item Added');
 }
 
 
 
 
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
  $page->assign('cart',$cart);
 $page->setContent('page/cart');
 
 $page->create();
 
 ?>