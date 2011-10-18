<h2>My Cart</h2>

<br />
<table>
<tr>

<td><b>Items </b></td>
<td><b>Quantity    &nbsp;     &nbsp;     &nbsp; </b> </td>
<td><b>Price </b></td>
   
</tr>
   
{foreach from=$itemAry item='item'}
	
	{if is_object($item)}
		<tr><td>{$item->getTitle()}    &nbsp;     &nbsp;     &nbsp;  </td>
        <td>{$item->getQuantity()}</td>
        <td>${$item->getQuantity() * $item->getPrice()}</td></tr>
    {/if}
    
{foreachelse}
	&nbsp; &nbsp; <tr><td>Empty Cart</td><td></td><td></td></tr>
    <br />
	<br />
{/foreach}
	</table>
    <br />
	<b>Total Price:    &nbsp;     &nbsp;     &nbsp;  ${$total}</b>
	<br />
    <br />
<form name="buyForm" action="page/checkout" method="post">
	
	<input type="button" onclick="window.location='/page/checkout.php'" value="Check Out" />
		
</form>
