<h2>Suppliers</h2>

<br />

{foreach from=$supplierAry item='supplier'}

	<div id="obj-{$supplier->getID()}-{$smarty.const.SUPPLIER_OBJ}">
		<strong>Title:</strong> 	{$supplier->getHyperlink()} 		<br />
		<strong>Address:</strong> 	{$supplier->getAddress()}		<br />
        <strong>City:</strong> 		{$supplier->getCity()}			<br />
		<strong>State:</strong> 	{$supplier->getState()}			<br />
		<strong>Phone:</strong> 	{$supplier->getPhone()}			<br />
		<strong>Zip:</strong>		{$supplier->getZip()}			<br />
        
		<a href="/page/editSupplier.php?cid={$supplier->getID()}">Edit</a> | 
		
		{if is_object($me) && $me->getID() != $supplier->getID()}
		<a href="javascript:deleteObj({$supplier->getID()},{$smarty.const.SUPPLIER_OBJ})">Delete</a>
		{/if}
		
		<br />
		<hr style="width: 600px; float: left" />
		<br />
	</div>
{foreachelse}
	No Suppliers Found :(
{/foreach}


<br />
<br />

<a href="/page/createSupplier.php">Create New</a>