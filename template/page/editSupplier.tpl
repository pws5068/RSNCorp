
<h2>Edit Supplier Record #{$supplier->getID()}</h2>

<br />

<a href="/page/suppliers.php">&lt; Back</a>{*javascript:history.go(-1)*}

<br />
<br />

<form name="editSupplierForm" method="post">

	Title: 		<input type="text" name="title" value="{$supplier->getTitle()}" />			<br />
	Phone: 		<input type="text" name="phone" value="{$supplier->getPhone()}" />			<br />
	Address: 	<input type="text" name="address" value="{$supplier->getAddress()}" />		<br />	
    City: 		<input type="text" name="city" value="{$supplier->getCity()}" />			<br />	
	State: 		<input type="text" name="state" value="{$supplier->getState()}" />			<br />
	Zip: 		<input type="text" name="zip" value="{$supplier->getZip()}" />				<br />
    Country:	<input type="text" name="country" value="{$supplier->getCountry()}" />		<br />
    Thumb:		<input type="text" name="thumb" value="{$supplier->getThumb()}" />			<br />
	
	<br />
	
	<input type="submit" name="Save" value="Save" />
</form>