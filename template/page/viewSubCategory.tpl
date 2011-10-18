<h2>Items By Sub-Category: {$sCategory->getTitle()}</h2>

<a href="/page/allCategories.php">All Categories</a> &gt; {$category->getHyperlink()} &gt; {$sCategory->getHyperlink()}

<br />
<br />

{if sizeOf($ssCategories) > 0}
Sub-Sub-Categories: 
	<div class="subtleText">
	{foreach from=$ssCategories item='ssCat'}
		{$ssCat->getHyperlink()} 
	{/foreach}
	</div>
<br />

{/if}

{assign var='itemsAry' value=$sCategory->getAllItems()}

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

	There are no items under {$sCategory->getTitle()}
	
{/foreach}