
<h2>Edit Customer Record #{$customer->getID()}</h2>

<br />

<a href="/page/customers.php">&lt; Back</a>{*javascript:history.go(-1)*}

<br />
<br />

<form name="editCustomerForm" method="post">

	Name: 		<input type="text" name="name" value="{$customer->getName()}" />			<br />
	Pass:		<input type="password" name="pass" value="" />	(Change Optional)			<br />
	Phone: 		<input type="text" name="phone" value="{$customer->getPhone()}" />			<br />
	Email:		<input type="text" name="email" value="{$customer->getEmail()}" />			<br />
	Address: 	<input type="text" name="address" value="{$customer->getAddress()}" />		<br />	
	State: 		<input type="text" name="state" value="{$customer->getState()}" />			<br />
	Zip: 		<input type="text" name="zip" value="{$customer->getZip()}" />				<br />
	
	<br />
	
	<input type="submit" name="Save" value="Save" />
</form>