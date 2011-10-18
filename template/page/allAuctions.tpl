<h2>Current Auctions</h2>

<br />
<br />

<table>
<tr>
<td><b>Item</b></td>
<td><b>Current Bid&nbsp; &nbsp;</b></td>
<td><b>Expiration Date</b></td></tr>

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

	<tr><td>No Auctions Exist</td><td></td><td></td></tr>

{/foreach}

</table>
<br />
<br />
<a href="/page/expiredAuctions.php">Expired Auctions</a>
<br />
<br />