<h2>Items By Sub-Category: {$ssCategory->getTitle()}</h2>

<a href="/page/allCategories.php">All Categories</a> &gt; {$category->getHyperlink()} &gt; {$sCategory->getHyperlink()} &gt; {$ssCategory->getHyperlink()}

<br />
<br />

{assign var='itemsAry' value=$ssCategory->getAllItems()}

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

	There are no items under {$ssCategory->getTitle()}
	
{/foreach}