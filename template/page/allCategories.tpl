<h2>Items By Category</h2>

{if sizeOf($categoryAry) > 0}
	<ul class="prettyUL">
{/if}

{foreach from=$categoryAry item='category'}

	<li>{$category->getHyperlink()}</li>
{foreachelse}

	No Categories Exist :(

{/foreach}

{if sizeOf($categoryAry) > 0}
	</ul>
{/if}