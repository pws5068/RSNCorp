<h2>Items By Category: {$category->getTitle()}</h2>

<a href="/page/allCategories.php">All Categories</a> &gt; {$category->getHyperlink()}

<br />
<br />

{if sizeOf($subCategories) > 0}
SubCategories: 
	<div class="subtleText">
	{foreach from=$subCategories item='sCat'}
		{$sCat->getHyperlink()} 
	{/foreach}
	</div>
<br />

{/if}

{assign var='itemsAry' value=$category->getAllItems()}

{foreach from=$itemsAry item='item'}

	<div id="item_{$item->getID()}" class="itemListing" >
		
		<a href="{$item->getURL()}">
		
			<div class="itemThumb">
				{$item->showThumbnailSmall()}
			</div>
			<div class="itemText">
				<h2>{$item->getTitle()}</h2>
				{$item->getDescription()}
			</div>
		</a>
	</div>

{foreachelse}

	There are no items under {$category->getTitle()}
	
{/foreach}