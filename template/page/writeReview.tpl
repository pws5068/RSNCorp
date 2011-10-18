<h2>Create New Auction</h2>


<form name="auctionForm" method="post" action="{$smarty.server.PHP_SELF}">
	Supplier: 	<select name="supplier" onchange="selectSupplier()"></select>				<br />
    Review: &nbsp;	<textarea rows="4" cols="20" name="review" value="" ></textarea>	<br />
    			<input type="hidden" name="customer" value="{$me->getID()}">

	<br />
	<br />
	<br />

	<input type="submit" name="Save" value="Post" />
</form>