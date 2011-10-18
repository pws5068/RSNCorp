<h2>Create New Customer</h2>


<form name="createCustomer" method="post" action="{$smarty.server.PHP_SELF}">
	Name: 				<input type="text" name="name" value="" />				<br />
	Phone: 				<input type="text" name="phone" value="" />				<br />
	Email:				<input type="text" name="email" value="" />				<br />
    Password:			<input type="password" name="password" value="" />		<br />
    Confirm Password:	<input type="password" name="password2" value="" />		<br />
	Address: 			<input type="text" name="address" value="" />			<br />	
	State: 				<input type="text" name="state" value="" />				<br />
	Zip: 				<input type="text" name="zip" value="" />				<br />
    					<input type="hidden" name="gonnaGetYa" value="" />		<br />
	<br />
	<br />

	<input type="submit" name="Save" value="Save" />
</form>