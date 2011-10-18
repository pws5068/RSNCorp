{* 
	Display all items in passed $itemArry 
	
	Expects:
		$itemArry
		$headerTitle
*}

<h2>{$headerTitle}</h2>

{foreach from=$itemArry name='item'}

	<div id="{$item->getID()}" class="itemListing" >
		
		<h3>{$item->getName()}</h3>
		
		<strong>Description: </strong> {$item->getDescription()}
	</div>

{/foreach}