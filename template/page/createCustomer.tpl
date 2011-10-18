<h2>Create New Customer</h2>


<form name="createCustomer" method="post" action="{$smarty.server.PHP_SELF}">
	Name: 		<input type="text" name="name" value="" />			<br />
	Phone: 		<input type="text" name="phone" value="" />			<br />
	Email:		<input type="text" name="email" value="" />			<br />
	Address: 	<input type="text" name="address" value="" />		<br />	
	State: 		<input type="text" name="state" value="" />			<br />
	Zip: 		<input type="text" name="zip" value="" />			<br />
	Access:		<select name="accessLvl">
					<option value="4">Member</option>
					<option value="9">Admin</option>
				</select>
	<br />
	<br />
	<br />

	<input type="submit" name="Save" value="Save" />
</form>
	