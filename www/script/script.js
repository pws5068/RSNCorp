function setSuccess(message)
{
	$('#notice').removeClass('warningNotice errorNotice');
	$('#notice').addClass('successNotice');
	$('#notice').html(message);
	
	$('#notice').slideDown();
}
function setWarning(message)
{	
	$('#notice').removeClass('successNotice errorNotice');
	$('#notice').addClass('warningNotice');
	$('#notice').html(message);
	
	$('#notice').slideDown();
}
function setError(message)
{
	$('#notice').removeClass('warningNotice successNotice');
	$('#notice').addClass('errorNotice');
	$('#notice').html(message);
	
	$('#notice').slideDown();
}
function deleteObj(oid,tid)
{
	var params = "oid="+oid+"&tid="+tid;
	
	$.ajax({
       type: "POST",
       url: "/script/ajax/deleteObj.php",
       data: params,
       success: function(msg){
       
       $("#obj-"+oid+"-"+tid).slideUp();
       
       setSuccess("Customer Removed");
       }
    });
}
function searchItems()
{
	$("#searchResults").show();
	$("#searchResults").slideUp();
	$("#searchResults").html('<img src="/image/layout/ajax-loader.gif" />');
	
	var searchString	= $("#searchString").val();
	var minPrice		= $("#minPrice").val();
	var maxPrice		= $("#maxPrice").val();

	var params = "searchString="+searchString+"&minPrice="+minPrice+"&maxPrice="+maxPrice;
	
	$.ajax({
       type: "POST",
       url: "/script/ajax/searchItems.php",
       data: params,
       success: function(msg){
       
       $("#searchResults").slideDown();
       $("#searchResults").html(msg);

       }
    });
}