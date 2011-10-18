<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="keywords" content="{$pageKeywords}" />
<meta name="description" content="{$pageDescription}" />

<title>RSNCorp | {$pageTitle}</title>

{foreach from=$styleSheets item=style}
<link href="{$style}" rel="stylesheet" media="all" type="text/css" />
{/foreach}

<script type="text/javascript" src="http://www.google.com/jsapi"></script>
<script type="text/javascript">google.load("jquery", "1"); google.load("jqueryui", "1");</script>
<script type="text/javascript" src="/script/rounded-corner.js"></script>

{foreach from=$jsFiles item=js}
<script type="text/javascript" src="{$js}"></script>
{/foreach}

{if $internalJS}
<script type="text/javascript">{foreach from=$internalJS item=codeblock}{$codeblock}{/foreach}</script>
{/if}

<script type="text/javascript">
(function($) {ldelim}
	$(document).ready(function(){ldelim}
	
		{foreach from=$onloadCommands item=command} {$command} {/foreach}

		$("#mainContent").corner("bottom");
		
	{* Add all site-wide jquery between here and </script> *}
{rdelim}); // End Document.Ready
{rdelim})(jQuery);
</script>

<link href="/style/style.css" rel="stylesheet" type="text/css" />

</head>
<body>

<div id="wrapper">

	<div id="banner"><img src="/image/layout/banner.png" alt="Rock Sport Nation" /></div><!-- End banner -->
	<div id="mainContent">

	{if isset($success)}
		{assign var=noticeStyle value='class="successNotice"'}
	{elseif isset($error)}
		{assign var=noticeStyle value='class="errorNotice"'}
	{elseif isset($warning)}
		{assign var=noticeStyle value='class="warningNotice"'}
	{/if}
	
	{* setSuccess, setWarning, setError all appear here if set, otherwise empty.  Also interfaces with js equivalents *}
	<div id="sfNoticeFont" {$noticeStyle}>{$notice}</div><br />
	
		<div id="mainContentLeft">
			 <ul id="menuList">
			 	<li><a href="/index.php">Home</a></li>
			 	<li><a href="/page/allCategories.php">All Items</a></li>
			 	<li><a href="/page/searchItems.php">Search Items</a></li>
			 	{if !Security::isMember()}
			 	<li><a href="/page/register.php">Register</a></li>
			 	<li><a href="/page/login.php">Login</a></li>
			 	
			 	{else}
                {if Security::isAdmin()}
			 	<li><a href="/page/customers.php">Customers</a></li>
                {/if}
			 	<li><a href="/page/suppliers.php">Suppliers</a></li>
                <li><a href="/page/writeReview.php">Write a Review</a></li>
                <li><a href="/page/allAuctions.php">Auctions</a></li>
			 	<li><a href="/page/createAuction.php">Create Auction</a></li>
                <li><a href="/page/cart.php">My Cart</a></li>
                <li><a href="/page/cartHistory.php">Purchase History</a></li>
                <li><a href="/page/logout.php">Logout</a></li>
			 	{/if}
			 </ul>
		</div>
		
		<div id="mainContentRight">
        {if isset($content)}
        {include file="$content.tpl"}
        {/if}
          </div>
          
         <br class="clear" />
         <br class="clear" />
    </div> <!-- End Main Content -->
    
     {include file="footer.tpl"}
     
</div> <!-- End Wrapper-->
   
</body>
</html>