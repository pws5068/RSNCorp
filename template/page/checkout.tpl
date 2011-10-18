<h1>Your Information:</h1>

<h2>Items you are buying:</h2>
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
	<br />
	<br />
{/foreach}
	</table>
	<br />
    <br />
	<b>Total Price:    &nbsp;     &nbsp;     &nbsp;  ${$total}</b>

<br />
<br />

{if $accountCode neq 13}
<h7>Dicount Code: (1 Per transaction)</h7><br />
<form name="buyForm" action="{$smarty.server.PHP_SELF}" method="post">
	<input type="text" name="dCode" value="" />
	<input type="submit" value="Apply ProDeal!" />
</form>
{else if}
<h3>Amount Saved: ${$amtSaved}</h3>
<h2>New Total: ${$newTotal} </h2>
{/if}

<br />
<br />

<h2>Will be shipped to:</h2>
<div id="obj-{$customer->getID()}-{$smarty.const.CUSTOMER_OBJ}">
		<strong>Name:</strong> 		{$customer->getName()} 			<br />
		<strong>Address:</strong> 	{$customer->getAddress()}		<br />
		<strong>State:</strong> 	{$customer->getState()}			<br />
		<strong>Phone:</strong> 	{$customer->getPhone()}			<br />
		<strong>Zip:</strong>		{$customer->getZip()}			<br />
		
		
		
		<br />
		<hr style="width: 600px; float: left" />
		<br />
</div>

<form name="buyForm" action="/index.php" method="post">
	
	<input type="hidden" name="checkout" value="true" />
	<input type="hidden" name="cartID" value="{$cart->getID()}" />
	<input type="submit" onclick="alert('Thanks for shopping RSNCorp.com!'); return true;" value="Check Out" />
		
</form>