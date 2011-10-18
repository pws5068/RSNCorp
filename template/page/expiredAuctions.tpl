<h2>Expired Auctions</h2>

<br />
<br />

<table>
<tr>
<td><b>Item</b></td>
<td><b>Winning Bid&nbsp; &nbsp;</b></td>
<td><b>Date Won</b></td></tr>

{foreach from=$auctionAry item='auction'}
	{if is_object($auction)}
		<tr><td>{$auction->getHyperlink()} &nbsp; </td>
        <td>{if is_object(Bid::getHighestBid($auction->getID()))}
    						 ${Bid::getHighestBid($auction->getID())->getAmount()}
                         {else}
                         	  N/A
                         {/if}</td>
        <td>{$auction->getExpiration()}</td></tr>
	{/if}
{foreachelse}

	<tr><td>No Expired Auctions Exist</td><td></td><td></td></tr>

{/foreach}

</table>
<br />
<br />
<a href="/page/allAuctions.php">Current Auctions</a>
<br />
<br />
