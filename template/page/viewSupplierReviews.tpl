<h2>{$supplier->getTitle()} Reviews</h2>

<br />
<br />

{foreach from=$reviewAry item=review}
    <div class="reviewBox">

    	{if is_object($review)}
            <strong>Reviewed By:</strong>  &nbsp; {$review->getCustomer()->getName()} <br />
            {$review->getMessage()}<br />
            <br />
        {/if}
    </div>
{foreachelse}
	There are no reviews.
{/foreach}