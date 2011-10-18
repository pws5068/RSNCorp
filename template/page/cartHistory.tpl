<h2>My Purchase History</h2>

<br />
<br />
<table>
<tr>

<td><b>Items </b></td>
<td><b>Price </b></td>
   
</tr>
{foreach from=$cartAry item=cart}
   
    	{if is_object($cart)}
            {foreach from=$cart->getItemAry() item='item'}
                {if is_object($item)}
                    <tr><td>{$item->getTitle()}    &nbsp;     &nbsp;     &nbsp;  </td>
                    <td>{$item->getPrice()}</td></tr>
    			{/if}
            {/foreach}
        {/if}
    
{foreachelse}
	You haven't made any purchases yet!
{/foreach}
</table>