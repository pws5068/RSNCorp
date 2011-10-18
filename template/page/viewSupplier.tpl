<h2>{$supplier->getTitle()}</h2>

<br />

<a href="/page/suppliers.php">All Suppliers</a> &gt; {$supplier->getHyperlink()}
<br />
{$supplier->showThumbnail()}

<br />
<br />

<div id="supplierDescription">
	
		<strong>Address:</strong> 	{$supplier->getAddress()}		<br />
        <strong>City:</strong> 		{$supplier->getCity()}			<br />
		<strong>State:</strong> 	{$supplier->getState()}			<br />
		<strong>Phone:</strong> 	{$supplier->getPhone()}			<br />
		<strong>Zip:</strong>		{$supplier->getZip()}			<br />
        <a href="/page/viewSupplierReviews.php?id={$supplier->getID()}">Customer Reviews</a><br />
	
<br />
<hr style="width: 600px; float: left" />
<br />

<h2>Items:</h2> <br />

{foreach from=$itemsAry item='item'}
		
        <Strong>{$item->getHyperlink()}</Strong> <br />
        
        
        
{foreachelse}
	No Items Found 
{/foreach}

</div>