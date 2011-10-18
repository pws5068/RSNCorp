<h2>Auction: {$auction->getItem()->getTitle()}</h2>

<br />
<br />

{if !$expired}
	Minimum Bid: &nbsp; ${$auction->getMin()} <br />
    Current Bid: &nbsp; ${if is_object($highestBid)}
    						 {$highestBid->getAmount()}
                         {else}
                         	  N/A
                         {/if} <br />

	
	<br />
    
	<form name="buyForm" action="{$smarty.server.PHP_SELF}" method="post">
	
    	<input type="hidden" name="id" value="{$auction->getID()}" />
		<input type="submit" value="Place Bid" />
        &nbsp;$<input type="text" name="bid" value="{if is_object($highestBid)}{$highestBid->getAmount() + 2}{else}{$auction->getMin()}{/if}" />
		
	</form>
    <br />
{else}

	EXPIRED<br /><br />
	Winning Bid: &nbsp; ${if is_object($highestBid)}
    						 {$highestBid->getAmount()}
                         {else}
                         	  N/A
                         {/if} <br /><br />

{/if}    



{$auction->getItem()->showThumbnail()}

<br />
<br />

<div id="itemDescription">
	
	<h3>Product Description:</h3>
	
	{$auction->getItem()->getDescription()}
	
	<br />
	<br />
	
	Supplier: {$auction->getItem()->getSupplier()->getHyperlink()} <br />
    

</div>
