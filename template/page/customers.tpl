<h2>Customers</h2>

<br />

{foreach from=$customerAry item='customer'}

	<div id="obj-{$customer->getID()}-{$smarty.const.CUSTOMER_OBJ}">
		<strong>Name:</strong> 		{$customer->getName()} 			<br />
		<strong>Address:</strong> 	{$customer->getAddress()}		<br />
		<strong>State:</strong> 	{$customer->getState()}			<br />
		<strong>Phone:</strong> 	{$customer->getPhone()}			<br />
		<strong>Zip:</strong>		{$customer->getZip()}			<br />
		
		<a href="/page/editCustomer.php?cid={$customer->getID()}">Edit</a> | 
		
		{if is_object($me) && $me->getID() != $customer->getID()}
		<a href="javascript:deleteObj({$customer->getID()},{$smarty.const.CUSTOMER_OBJ})">Delete</a>
		{/if}
		
		<br />
		<hr style="width: 600px; float: left" />
		<br />
	</div>
{foreachelse}
	No Customers Found :(
{/foreach}

<br />
<br />

<a href="/page/createCustomer.php">Create New</a>