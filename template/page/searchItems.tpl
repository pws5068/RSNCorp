<h2>Item Search</h2>

<form id="searchForm" name="searchForm" method="post" action="{$smarty.server.PHP_SELF}">

	Search For: <input type="text" id="searchString" />
	
	<br />
	<br />
	
	Price Between: <input type="text" id="minPrice" size="10" /> - <input type="text" id="maxPrice" size="10" />
	
	<br />
	<br />
	
	<input type="submit" name="searchItemsButton" value="Search" onclick="searchItems(); return false;" />
</form>

<div id="searchResults" style="display:none"></div>{* Populated by ajax, see script.js:searchItems() *}