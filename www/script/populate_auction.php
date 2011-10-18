<?
	header('Content-type: text/javascript');
	require_once("functions.php");
	
	$supplierAry = Supplier::getAll();
?>

function fillSuppliers(){
<?
	foreach($supplierAry as $supplier)
	{
		echo("addOption(document.auctionForm.supplier,\"{$supplier->getID()}\",\"{$supplier->getTitle()}\",\"\");");
	}	
?>
}

function selectSupplier(){

// ON selection of Supplier this function will work

removeAllOptions(document.auctionForm.item);
addOption(document.auctionForm.item, "", "Item:", "");

<?
	foreach($supplierAry as $supplier)
	{
		echo("if(document.auctionForm.supplier.value == '{$supplier->getID()}'){ ");
		
		$itemAry = $supplier->getItems();
		
		if(is_array($itemAry)) {
			foreach($itemAry as $item)
			{
				echo("addOption(document.auctionForm.item,\"{$item->getID()}\",\"{$item->getTitle()}\");");
			}
		}
		
		echo(" }");
	}	
?>
}

function removeAllOptions(selectbox)
{
	var i;

	for(i=selectbox.options.length-1;i>=0;i--)
	{
		//selectbox.options.remove(i);
		selectbox.remove(i);

	}
}

function addOption(selectbox, value, text )
{
	var optn = document.createElement("OPTION");
	optn.text = text;
	optn.value = value;

	selectbox.options.add(optn);
}