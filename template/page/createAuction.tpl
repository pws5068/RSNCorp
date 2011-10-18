<h2>Create New Auction</h2>


<form name="auctionForm" method="post" action="{$smarty.server.PHP_SELF}">
	Supplier: 	<select name="supplier" onchange="selectSupplier()"></select>				<br />
	Item:		<select name="item"></select>					<br />
    Minimum Bid: 	<input type="text" name="min" value="" />	<br />

	<br />
	<br />
	<br />

	<input type="submit" name="Save" value="Save" />
</form>