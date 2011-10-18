<h1>Today's Featured Items!</h1>
{foreach from=$anItemArray item='item'}
	<h2>{$item->getTitle()}</h2>
    <div id="indexImages"> <a href="{$item->getURL()}">{$item->showThumbnail()}</a></div>
    <br  />
    {$item->getDescription()}
    <br />
{foreachelse}

	Erroar!
	
{/foreach}


<br />
<br />
<br />
<br />
<br />

<h1>A "Kitten Mittens" Production!</h1>

<br />