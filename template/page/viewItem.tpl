<h2>{$item->getTitle()}</h2>

<a href="/page/allCategories.php">All Categories</a> &gt; {$item->getCategory()->getHyperlink()} &gt;
{$item->getSubCategory()->getHyperlink()} &gt; {$item->getSubSubCategory()->getHyperlink()} &gt;
 {$item->getHyperlink()}
<br />
{$item->showThumbnail()}

<br />
<br />

<div id="itemDescription">
	
	<h3>Product Description:</h3>
	
	{$item->getDescription()}
	
	<br />
	<br />
	
	Supplier: {$item->getSupplier()->getHyperlink()} <br />
	
	Price: Only ${$item->getPrice()}!
	<br />
	<br />
	<form name="buyForm" action="/page/cart.php" method="post">
	
    	<input type="hidden" name="itemID" value="{$item->getID()}" />
		<input type="submit" value="Add to Cart" />
		
	</form>
</div>

<h2> Users who bought this item also bought... </h2>
<div id = "otherItemsBought">
	<ul>
    	{foreach from=$itemsBoughtArray item='item'}
    		<li> <a href="{$item->getURL()}">{$item->showThumbnailSmall()}</a></li>
		{foreachelse}
			No one else bought this item, be the first!
        {/foreach}
   </ul>
</div>
<br class="clear" />